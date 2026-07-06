# Note Sharing System

A web app for students to browse and share notes and previous question papers by semester and subject, built with Laravel and MySQL.

## Stack

- Laravel (Blade + Tailwind CSS via Breeze for auth)
- MySQL

## Local setup

1. Clone the repo and install dependencies:
   ```
   composer install
   npm install
   ```
2. Copy `.env.example` to `.env`, generate an app key:
   ```
   cp .env.example .env
   php artisan key:generate
   ```
3. Create a MySQL database matching `DB_DATABASE` in `.env` (default `note_sharing_system`).
4. Run migrations and seed sample data (creates an admin user, semesters, and subjects):
   ```
   php artisan migrate --seed
   php artisan storage:link
   ```
5. Build frontend assets:
   ```
   npm run build
   ```
6. Serve the app:
   ```
   php artisan serve
   ```

Seeded admin login: `admin@note-sharing.test` / `password`.

## Data model

- `users` — students and admins (`role` column)
- `semesters` → `subjects` → `notes` / `previous_questions`

## Workflow

Work is tracked in Jira (key `NOTE`). Branch names follow `feature/NOTE-#-short-description` so GitHub activity links automatically to the matching Jira card. All changes go through a pull request into `main` with at least one approval before merging.
