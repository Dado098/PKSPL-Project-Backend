# 02 — Docker Setup

Set up or repair Docker-only local development for this PKSPL Laravel 12 backend. Inspect the repository first; preserve compatible existing Docker configuration and make the smallest correction needed.

## Files in scope

- `docker/php/Dockerfile`
- `docker-compose.yml`
- `docker/nginx/default.conf`
- `.env.example` (only values necessary for Docker connectivity)
- `README.md` (Docker start instructions)

## Requirements

- Use a PHP 8.3 FPM image with the Composer binary available.
- Install only extensions Laravel/MySQL needs: `pdo_mysql`, plus standard Laravel runtime requirements. Do not add development tools to the production runtime without a reason.
- Compose services: `app`, `nginx`, `mysql`, and `phpmyadmin`.
- Mount `./src` at `/var/www/html`; Nginx document root must be `/var/www/html/public`; FPM upstream must point to `app:9000`.
- Use MySQL 8 with a named persistent volume, health check, and Compose service dependency that waits for MySQL to become healthy where supported.
- Expose Nginx on `8000` and phpMyAdmin on `8080` unless a conflicting port is already deliberately configured.
- Keep credentials out of hard-coded Docker Compose values where practical. Use `.env` / Compose variable substitution with documented non-secret development defaults.
- The application must be usable with:

```bash
docker compose up -d --build
docker compose exec app composer install
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate
```

If `src/` is empty, stop after making containers capable of running these commands; Laravel creation belongs to `01_Project_Setup.md`.

## Verification

Run `docker compose config` first. If Docker is available, build and start the stack, inspect service status, and verify Nginx can connect to PHP-FPM. Do not run destructive volume deletion commands. State clearly if a host port or daemon issue prevents validation.
