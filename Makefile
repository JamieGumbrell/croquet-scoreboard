.PHONY: help setup install dev serve vite test migrate build production deploy clear optimize

help:
	@echo "Available targets:"
	@echo "  make setup       Install dependencies, configure the app, migrate, and build assets"
	@echo "  make install     Install Composer and npm dependencies"
	@echo "  make dev         Start Laravel and Vite development processes"
	@echo "  make serve       Start the Laravel development server"
	@echo "  make vite        Start the Vite development server"
	@echo "  make test        Run the Laravel test suite"
	@echo "  make migrate     Run pending database migrations"
	@echo "  make build       Build frontend assets for production"
	@echo "  make production  Install production dependencies and build assets"
	@echo "  make deploy      Run migrations, link storage, and optimize Laravel"
	@echo "  make clear       Clear Laravel caches"
	@echo "  make optimize    Build Laravel production caches"

setup:
	composer run setup

install:
	composer install
	npm ci

dev:
	php artisan dev

serve:
	php artisan serve

vite:
	npm run dev

test:
	php artisan test

migrate:
	php artisan migrate

build:
	npm run build

production:
	composer install --no-dev --optimize-autoloader
	npm ci
	npm run build

deploy:
	php artisan migrate --force
	php artisan storage:link
	php artisan optimize

clear:
	php artisan optimize:clear

optimize:
	php artisan optimize
