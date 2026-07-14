# Deploying UniNotes (Fly.io + Aiven MySQL + Cloudflare R2)

Everything code/config-side is already set up (`Dockerfile`, `fly.toml`, `docker/`). What's left needs your own accounts — I can't sign up for services, verify email/phone, or hold your API keys, so these steps are yours to run. `flyctl` is already installed on this machine.

Fly.io's free-tier terms change over time — check [fly.io/pricing](https://fly.io/pricing) before deploying so there are no surprises.

## 1. Create accounts and get credentials

**Fly.io** — [fly.io/app/sign-up](https://fly.io/app/sign-up), then in a terminal:
```
flyctl auth login
```

**Aiven MySQL** (free tier) — [aiven.io](https://aiven.io), create a MySQL service. From its console, grab: host, port, database name, username, password.

**Cloudflare R2** (free 10GB storage, no egress fees) — [dash.cloudflare.com](https://dash.cloudflare.com) → R2 → Create bucket. Then R2 → "Manage API tokens" → create a token with read/write access to that bucket. You'll get an Account ID, Access Key ID, and Secret Access Key. The R2 endpoint is `https://<account-id>.r2.cloudflarestorage.com`. Under bucket settings, enable public access (or attach a custom domain) to get a public `AWS_URL` — without this, uploaded note files won't be viewable.

## 2. Launch the Fly app

```
flyctl launch --no-deploy
```
This reads `fly.toml` — say **no** to "would you like to set up a Postgres/Redis database" (we're using external MySQL). If the app name `uninotes` is taken, it'll prompt you for another; update `app = "..."` in `fly.toml` to match.

## 3. Set secrets

```
flyctl secrets set APP_KEY="base64:$(openssl rand -base64 32)"

flyctl secrets set DB_CONNECTION=mysql DB_HOST=<aiven-host> DB_PORT=<aiven-port> DB_DATABASE=<aiven-db> DB_USERNAME=<aiven-user> DB_PASSWORD=<aiven-password>

flyctl secrets set AWS_ACCESS_KEY_ID=<r2-access-key> AWS_SECRET_ACCESS_KEY=<r2-secret-key> AWS_DEFAULT_REGION=auto AWS_BUCKET=<bucket-name> AWS_ENDPOINT=https://<account-id>.r2.cloudflarestorage.com AWS_URL=<your-public-r2-url> AWS_USE_PATH_STYLE_ENDPOINT=true

flyctl secrets set APP_URL=https://<your-app-name>.fly.dev
```

Aiven's free MySQL requires TLS — if you hit a connection error, add `MYSQL_ATTR_SSL_CA` handling or check Aiven's connection docs for the CA cert path.

## 4. Deploy

```
flyctl deploy
```
This builds the Docker image on Fly's remote builders (no local Docker needed) and runs migrations automatically via `docker/entrypoint.sh` on boot.

## 5. Create your first admin account

The seeder's default admin (`admin@note-sharing.test` / `password`) is fine for local dev but shouldn't ship to production as-is. After the first deploy, SSH in and create/promote an admin directly:
```
flyctl ssh console
php artisan tinker
>>> App\Models\User::where('email', 'you@example.com')->update(['role' => 'admin']);
```
(Register a normal account first through the site, then promote it.)

## Notes

- `SESSION_DRIVER`, `CACHE_STORE`, and `QUEUE_CONNECTION` are all `database` (set in `fly.toml`), so no Redis is needed.
- Ghostscript is installed in the container, so PDF previews work out of the box.
- `min_machines_running = 1` in `fly.toml` keeps one instance always warm — no cold-start delay for classmates, but check current Fly.io pricing to confirm this stays within any free allowance you're relying on.
