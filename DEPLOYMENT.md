# Deploying UniNotes (Fly.io + Aiven MySQL + Fly volume storage)

Everything code/config-side is already set up (`Dockerfile`, `fly.toml`, `docker/`). What's left needs your own accounts — I can't sign up for services, verify email/phone, hold your API keys, or add a payment method on your behalf, so these steps are yours to run. `flyctl` is already installed on this machine.

Fly.io's free-tier terms change over time — check [fly.io/pricing](https://fly.io/pricing) before deploying so there are no surprises.

**No payment card required** for this setup — file storage uses a small Fly persistent volume (1GB, free on a trial org) instead of Cloudflare R2/S3, which requires a card to activate.

## Status so far

- Fly app created: `uninotes` (`uninotes.fly.dev`)
- Logged in as `rafsanjani3021@gmail.com`
- `APP_KEY` secret staged
- 1GB volume `uninotes_data` created in the `sin` region, mounted at `storage/app/public` (`fly.toml`'s `[[mounts]]`)

## 1. Aiven MySQL (free tier, no card needed)

[aiven.io](https://aiven.io) → sign up → create a **MySQL** service (free plan). From its console, grab: host, port, database name, username, password.

## 2. Set secrets

```
flyctl secrets set DB_CONNECTION=mysql DB_HOST=<aiven-host> DB_PORT=<aiven-port> DB_DATABASE=<aiven-db> DB_USERNAME=<aiven-user> DB_PASSWORD=<aiven-password>

flyctl secrets set APP_URL=https://uninotes.fly.dev
```

Aiven's free MySQL requires TLS — if you hit a connection error, check Aiven's connection docs for the CA cert / SSL mode.

## 3. Deploy

```
flyctl deploy
```
This builds the Docker image on Fly's remote builders (no local Docker needed) and runs migrations automatically via `docker/entrypoint.sh` on boot.

## 4. Create your first admin account

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
- The 1GB volume is a hard ceiling on total uploaded note storage. If the class outgrows it, `flyctl volumes extend` can grow it (may require adding a card at that point), or you can switch `FILESYSTEM_DISK` back to `s3` and point it at R2/Backblaze B2 later — `league/flysystem-aws-s3-v3` is already installed for that.
- **A volume is tied to a single machine/zone** — this only works cleanly with `min_machines_running = 1` (already set). Don't scale to multiple machines without moving storage to something shared (R2/S3) first, or each machine would see a different, empty disk.
