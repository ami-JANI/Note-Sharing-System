# UniNotes — Peer-to-Peer Note Sharing Platform

UniNotes is a university note-sharing platform where students browse, upload, and download lecture notes and previous exam question papers, organized by semester and subject. It's built with Laravel 12 and MySQL, with a credit economy, peer messaging, notifications, reviews, and a full admin moderation panel layered on top of the core note-sharing flow.

The app has two halves:

- **Student Portal** — browse and search notes without an account, or log in to upload, download, review, message, and earn credits.
- **Admin Panel** — approve/reject uploads, moderate reviews, manage users, and broadcast notifications.

---

## Features

### Browsing & Search
- Public browse page, accessible without logging in — full note grid, search by title/course/department/semester, and a full filter panel (department, course, semester, minimum rating, price)
- Note detail page with an auto-generated preview image, credit-gated download, and reviews/average rating
- "This week's top uploader" sidebar, ranked by average review rating (falls back to all-time average if nobody has reviews that week)

### Uploads
- Notes and previous question papers, organized under Semester → Subject
- **Duplicate detection**: every uploaded file is SHA-256 hashed and checked against every existing note before it's stored — exact duplicates are rejected before the file ever touches disk
- Auto-generated first-page preview image (PDF/DOCX) via Ghostscript, generated best-effort at upload time
- New uploads start `pending` and are hidden from Browse until an admin approves them
- **Credit reward**: every 5th total upload (5, 10, 15, ...) earns the uploader 10 credits automatically, independent of admin approval status

### Credits & Payments
- Wallet-style credit balance in the nav bar
- Buy credits (simulated payment flow) and view full transaction history (purchased / spent / earned)
- Unlock a paid note by spending credits before downloading; uploaders earn a share of credits when their notes are unlocked

### Reviews, Favorites & Messaging
- Star rating + written comment on any note, with average rating shown on cards and detail pages
- Favorite/follow a specific uploader from their public profile
- One-to-one direct messaging between students, with an inbox, unread badges, and a thread view

### Notifications
- Bell icon with a live unread-count badge; opening the dropdown automatically marks everything read
- Notification types: new message, note purchased, new review, admin broadcast — each links straight to the relevant page
- A separate unread-message dot on the Messages nav link, independent of the notification bell

### Admin Panel
- Pending-notes approval queue (approve / reject / delete any note, any status)
- Review moderation (hide / delete)
- User directory with suspend / unsuspend
- Broadcast notification composer — send to all users or a hand-picked subset

---

## Tech Stack

| Layer | Technology |
|---|---|
| Backend | Laravel 12 (PHP 8.2+) — Eloquent ORM, Blade templating, middleware-based auth |
| Database | MySQL 8 (Aiven-hosted in production, MySQL via XAMPP for local dev) |
| Frontend | Blade + Tailwind CSS 3 + Alpine.js (no SPA framework — server-rendered with light client-side reactivity) |
| File previews | Ghostscript (shells out via `NotePreviewService` to render the first page of PDF/DOCX uploads) |
| Containerization | Docker — multi-stage build (Node asset build → Composer vendor stage → `php-fpm-alpine` runtime with nginx + supervisord) |
| Hosting | Fly.io, with a persistent volume for uploaded files |
| CI/CD | GitHub Actions — auto-deploys `main` to Fly.io on every merge |
| Project management | Jira (Scrum board, key `NOTE`) |

---

## Architecture

```
Browser (Blade + Alpine.js)
        │  HTTP
        ▼
Laravel Router (routes/web.php, auth.php)
        │
        ▼
Controllers  ──────────────►  NotePreviewService ──► Ghostscript
        │                                                  │
        ▼                                                  ▼
Eloquent Models  ──────────►  MySQL              File Storage (public disk / Fly volume)
        │
        ▼
Notification system ──────►  MySQL (notifications table)
```

Everything above the database runs inside a single Docker container (nginx + php-fpm + supervisord) deployed to Fly.io. See `docs/UniNotes-UML-Diagrams.drawio` for the full set of UML/DFD/schema/ER diagrams (class, use case, activity, sequence, component, DFD levels 0–1, physical schema, and conceptual ER) — open it at [app.diagrams.net](https://app.diagrams.net).

---

## Database Schema

12 core tables:

| Table | Purpose |
|---|---|
| `users` | Auth, role (`student`/`admin`), credit balance, profile fields |
| `semesters` | Academic semester structure (1-1 through 4-2) |
| `subjects` | Courses within a semester |
| `notes` | Uploads — `file_hash` for deduplication, `status` for the approval workflow, `credit_price` |
| `previous_questions` | Exam archive uploads |
| `reviews` | Rating + comment per note |
| `messages` | Sender/recipient direct messages |
| `note_purchases` | Credit-unlock records |
| `note_downloads` | Download history (pivot between users and notes) |
| `payments` | Simulated credit purchase records |
| `favorites` | Pivot table — users following other users |
| `notifications` | Laravel's built-in polymorphic notifications table |

Key relationships: `notes` belong to a `user` (uploader), a `subject`, and a `semester`; `reviews` and `note_purchases` belong to both a `note` and a `user`; `users` self-reference through the `favorites` pivot.

---

## Local Development Setup

**Requirements:** PHP 8.2+, Composer, Node.js, MySQL, and (optionally) Ghostscript for preview generation.

1. Clone the repo and install dependencies:
   ```
   composer install
   npm install
   ```
2. Copy `.env.example` to `.env` and generate an app key:
   ```
   cp .env.example .env
   php artisan key:generate
   ```
3. Create a MySQL database matching `DB_DATABASE` in `.env` (default `note_sharing_system`).
4. Run migrations and seed sample data (creates an admin user, all 8 semesters, and a handful of subjects):
   ```
   php artisan migrate --seed
   php artisan storage:link
   ```
5. Build frontend assets:
   ```
   npm run build
   ```
   The app serves the **pre-built** CSS/JS from `public/build` — it does not run the Vite dev server, so re-run `npm run build` after any styling change (`npm run dev` works too if you want hot-reload during active frontend work).
6. Serve the app:
   ```
   php artisan serve
   ```

Seeded admin login: `admin@note-sharing.test` / `password`.

**Optional — note previews:** without Ghostscript on `PATH` (as `gswin64c`/`gs`), preview generation is skipped silently and browse cards fall back to a placeholder icon. To enable it, install Ghostscript and, on Windows, optionally set `GHOSTSCRIPT_BIN` in `.env` to the full binary path.

---

## Running Tests

```
php artisan test
```

The suite covers uploads (including duplicate-hash rejection and the credit-milestone reward), browsing/search/filters, messaging, notifications, reviews, credits, admin moderation, and auth — 100+ tests across `tests/Feature`. Tests run against an in-memory SQLite database (`phpunit.xml`), which is faster but does **not** enforce MySQL's stricter SQL modes (e.g. `ONLY_FULL_GROUP_BY`) — a query that passes the suite can still fail against real MySQL, so anything with a `GROUP BY` is worth manually verifying against MySQL before merging.

---

## Production Deployment

- **Docker**: multi-stage `Dockerfile` — Node stage builds frontend assets, a Composer stage installs PHP dependencies (`--no-dev`), and the runtime stage is `php-fpm-alpine` running nginx + php-fpm + supervisord together.
- **Fly.io**: hosts the container with a persistent volume (`fly.toml`) mounted at the note-storage path. The volume arrives **root-owned** on every boot, so `docker/entrypoint.sh` re-`chown`s it to `www-data` before starting supervisord — without this, every file upload fails silently.
- **Database**: MySQL hosted on Aiven's free tier.
- **Migrations run automatically** on boot (`entrypoint.sh` runs `php artisan migrate --force`), but **seeding does not** — if you deploy to a fresh database, semesters/admin user need to be created manually (e.g. via `flyctl ssh console` + `artisan tinker`) since re-running the seeder isn't safe on a live app.
- **CI/CD**: `.github/workflows/fly-deploy.yml` runs `flyctl deploy` on every push to `main`, authenticated via the `FLY_API_TOKEN` repo secret.

Known free-tier constraints to be aware of: Fly's trial-org restrictions can force machines to sleep/stop without a card on file, and Aiven's free MySQL instance auto-powers-off during inactivity — both currently need to be resolved (add a card, or migrate to a host without these limits) for the deployment to stay reliably up.

---

## Project Management

Work is tracked in Jira (project key `NOTE`) using Scrum — sprints, epics, stories, and subtasks, with every ticket linked to its implementing pull request. Branch names follow `feature/NOTE-#-short-description` so GitHub activity links automatically to the matching Jira card.

**Team:**
- **Rafsan Jani** — Scrum Master: sprint planning, Jira administration, PR review and merge, production deployment, release management
- **Sazzath Hossen Rafee** — Developer: backend features (duplicate detection, credit rewards, top-uploader ranking, messaging backend, admin endpoints)
- **MD. Shahriar Hossain Prottoy** — Developer: frontend features (browse UI, messaging UI, notification dropdown, progress bars, admin composer views)

## Contributing / Git Workflow

- `main` is production and protected — all changes land via pull request.
- One feature branch per Jira ticket: `feature/NOTE-#-short-description`.
- PRs are squash-merged after review (test suite passes, matches the ticket, no regressions), and the branch is deleted on merge.
- Every merge to `main` auto-deploys to production via GitHub Actions.
