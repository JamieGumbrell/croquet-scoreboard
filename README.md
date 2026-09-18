# Croquet Scoreboard

A Laravel application for creating and displaying croquet scoreboards.

## Requirements

Install these programs on the new machine:

- PHP 8.3 or newer, with the SQLite and Mbstring extensions enabled
- Composer
- Node.js and npm
- Git, to clone the repository
- GNU Make, optional for the shortcut commands below

The application uses SQLite by default. A different database can be used by changing the `DB_*` values in `.env`.

Check the installed versions:

```bash
php -v
composer -V
node --version
npm --version
```

## Initial Setup

Clone the repository and enter its directory:

```bash
git clone <repository-url> croquet-scoreboard
cd croquet-scoreboard
```

Install PHP and JavaScript dependencies, create the environment file, generate the application key, run migrations, and build the frontend:

```bash
composer run setup
```

The setup script performs these steps:

1. Installs Composer dependencies.
2. Creates `.env` from `.env.example` if it does not exist.
3. Generates `APP_KEY`.
4. Runs database migrations.
5. Installs npm dependencies.
6. Builds the Vite assets.

Review `.env` and update `APP_NAME`, `APP_URL`, and database settings as needed. Do not overwrite an existing `.env` file when setting up an existing installation.

If you prefer to run the steps individually:

```powershell
composer install
Copy-Item .env.example .env
php artisan key:generate
php artisan migrate
npm ci
npm run build
```

## Development

Run the Laravel server in one terminal:

```bash
php artisan serve
```

Run Vite in a second terminal so frontend changes are rebuilt automatically:

```bash
npm run dev
```

Open [http://localhost:8000](http://localhost:8000).

After changing database migrations, run:

```bash
php artisan migrate
```

Run the test suite with:

```bash
php artisan test
```

### Make Shortcuts

If GNU Make is installed, the common commands can be run from the project root:

```bash
make setup       # First-time setup
make dev        # Start Laravel and Vite development processes
make test       # Run tests
make migrate    # Run pending migrations
make build      # Build frontend assets
```

On Windows, run these commands from Git Bash, WSL, or another shell with GNU Make available. The equivalent `php artisan`, `composer`, and `npm` commands above work without Make.

## Production Build

Install production PHP dependencies and compile the frontend assets:

```bash
composer install --no-dev --optimize-autoloader
npm ci
npm run build
```

Configure production values in `.env` before caching configuration:

```dotenv
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.example
```

Run migrations and optimize Laravel caches:

```bash
php artisan migrate --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Deployment

Deploy the repository to the server, then from the application directory:

```bash
composer install --no-dev --optimize-autoloader
npm ci
npm run build
php artisan migrate --force
php artisan storage:link
php artisan optimize
```

Set the web server document root to the project’s `public` directory. Do not serve the project root, because it would expose application files.

The server must be able to write to:

- `storage`
- `bootstrap/cache`

For a new deployment, create and configure `.env` before running Artisan commands that read application configuration. Restart PHP-FPM or the application server after deploying code or changing environment variables.

After deployment, verify the application at `APP_URL` and check `storage/logs/laravel.log` if a request fails.

## Useful Commands

```bash
php artisan route:list       # List routes
php artisan migrate:status   # Show migration status
php artisan optimize:clear   # Clear cached configuration, routes, and views
php artisan optimize         # Rebuild production caches
```

The Makefile also provides these production shortcuts:

```bash
make production # Install production dependencies and build assets
make deploy     # Migrate, link storage, and optimize Laravel
make clear      # Clear Laravel caches
make optimize   # Rebuild Laravel caches
```
