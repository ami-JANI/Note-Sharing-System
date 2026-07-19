<div align="center">

# UniNotes — Note Sharing System

**Every lecture, every course — notes you can trust.**

A web platform for university students to browse, share, download, and rate study notes and previous exam question papers, organised by semester and subject — with a built-in credit economy.

</div>

---

## Tech Stack

| Layer | Technology |
|-------|-----------|
| **Backend** | Laravel 12 (PHP 8.2+) |
| **Frontend** | Blade + Tailwind CSS 3 + Alpine.js |
| **Build Tool** | Vite |
| **Database** | MySQL |
| **Auth** | Laravel Breeze (sessions) |
| **File Storage** | Local / S3-compatible |
| **PDF Previews** | Ghostscript |
| **Queue** | Database-driven |
| **Notifications** | In-app database |
| **Deployment** | Docker + Fly.io |

---

## Features

### Authentication & Users
- Registration (name, email, password, department, batch, roll, phone, photo)
- Login / Logout, Password Reset, Email Verification
- Roles: `admin` and `student`
- Statuses: `active` / `suspended` (admin-controlled)
- Profile editing, account deletion with password confirmation

### Semester & Subject Structure
- Semesters in `year-term` format (1-1 through 4-2)
- Subjects with unique code and name, scoped to a semester
- Browse notes by semester → subject drill-down

### Notes (Core)
- Upload with title, course number, description, file (PDF/DOCX ≤10 MB), department, semester, optional credit price
- SHA-256 hash deduplication — no duplicate uploads
- Moderation workflow: `pending` → `approved` / `rejected`
- Visibility toggle (hide/unhide approved notes)
- PDF preview — Ghostscript auto-renders first 2 pages as PNG
- Download & unlock flows

### Browsing & Search
- Public browsing (guests can view but are prompted to log in to unlock)
- Authenticated browsing with sidebar filters:
  - Keyword search (title, course, department, semester)
  - Department, course number, semester, price (free/paid/any)
  - Minimum rating filter (1–5 stars)
- Paginated results (20 per page)
- Top uploader sidebar (by weekly + all-time average rating)

### Credit Economy
- Every user has a credit balance (shown in navbar)
- Buy credits (simulated payment — instant credit)
- Upload reward: 10 bonus credits every 5 uploads
- Unlock premium notes by spending credits (price set by uploader)
- Uploader earns 10% commission on each unlock
- Transaction history page (purchases, spending, earnings)

### Reviews & Ratings
- Only users who unlocked a note can rate it (1–5 stars + comment)
- Average rating displayed per note
- Uploader notified on new review
- Admin can hide or delete reviews

### Favourites
- Toggle favourite uploaders (cannot favourite yourself)
- Favourite list on public profile

### Messaging
- Send messages to other users (not yourself)
- Inbox grouped by conversation partner, with unread count
- Conversation view — marks messages as read automatically
- Notification on new message

### Notifications (In-App)
- Bell icon in navbar with unread badge
- Mark all read
- Types: note purchased, new review, new message, admin broadcast

### Admin Panel
- Dashboard accessible only by `admin` role
- Note moderation: approve / reject / delete pending notes
- Review management: hide or delete reviews
- User management: suspend / unsuspend (admins cannot be suspended)
- Broadcast notifications to all or specific users
- Pending notes queue

### Public Profiles
- Each user has a public profile showing their uploaded notes

### Landing Page
- Marketing welcome page: hero, stats, "how it works", features grid, popular courses, testimonials, CTA
- Brand colours: cream (`#FBF8F3`), navy (`#1B2A4A`), burgundy (`#8A1C24`), gold (`#C08A3E`)

### Security
- Password hashing (bcrypt)
- CSRF protection
- Admin middleware
- Suspension middleware (logged out on blocked request)
- File validation (type, size, hash)
- Ownership checks on note operations
- Route model binding with authorization

---

## Data Model

```
users (role, department, batch, credits, status, current_semester, …)
  │
  ├── notes (uploader_id, subject_id, semester_id, title, file_path, file_hash,
  │          credit_price, status, hidden, department, …)
  │     ├── reviews (note_id, user_id, rating, comment, is_hidden)
  │     ├── note_purchases (note_id, user_id, credits_spent)
  │     └── note_downloads (note_id, user_id)
  │
  ├── previous_questions (subject_id, uploader_id, year, file_path)
  │
  ├── payments (user_id, credits_purchased, status)
  │
  ├── favorites (follower_id, following_id)
  │
  └── messages (sender_id, recipient_id, body, read_at)

semesters (name, order) → subjects (code, name) → notes / previous_questions
```

**Departments supported:** CSE, EEE, ME, CE, BBA, ENG, ARC, URP, IPE, TE, GMAT, MIS, AIS, FIM, MBA, LLB, PHARM, BIO, CHEM, PHY, MATH, STAT, ECO, SOC, GEO, HIST, PSY, PESS, ISLM (29 total)

---

## Local Setup

**Prerequisites:** PHP 8.2+, Composer, Node.js, MySQL

```bash
# 1. Clone & install dependencies
composer install
npm install

# 2. Environment
cp .env.example .env
php artisan key:generate

# 3. Create a MySQL database matching DB_DATABASE in .env (default: note_sharing_system)

# 4. Migrate & seed (creates admin, semesters, subjects)
php artisan migrate --seed
php artisan storage:link

# 5. Build frontend
npm run build

# 6. Serve
php artisan serve

# Or run all together (server + queue + Vite)
composer dev
```

**Seeded admin:** `admin@note-sharing.test` / `password`

---

## Deployment

The project is Dockerised and configured for [Fly.io](https://fly.io) with Aiven MySQL.

- `Dockerfile` — multi-stage build (Node for assets, Composer for deps, PHP-FPM runtime)
- `docker/nginx.conf` — production Nginx config
- `docker/supervisord.conf` — runs nginx + php-fpm
- `docker/entrypoint.sh` — runs migrations on container start
- `fly.toml` — Fly.io deployment config
- `DEPLOYMENT.md` — full deployment guide

---

## Workflow

- **Issue tracking:** Jira (project key `NOTE`)
- **Branch naming:** `feature/NOTE-#-short-description`
- **PRs:** all changes merged into `main` via pull request with ≥1 approval
