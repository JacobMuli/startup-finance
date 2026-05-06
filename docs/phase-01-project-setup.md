# Phase 1: Project Setup (Detailed Deployment)

## 🎯 Objective

Establish a professional, production-grade development orchestration for the **startup-finance** engine. This phase ensures the application is scaffolded with hardened security, asset bundling, and reliable environment variables.

## 1.1 Technical Stack Orchestration

- **Framework**: Laravel 10.x (Built for robust financial backend service).
- **Environment**: PHP 8.1+ with optimized extensions (BCMath for high-precision currency management).
- **Asset Pipeline**: **Vite 4.x** with PostCSS for premium UI utility compilation.

## 1.2 Associated Migrations

Initial baseline environment migrations ensuring core system stability:

- `0001_01_01_000000_create_users_table.php`: Core identity management schema.
- `0001_01_01_000001_create_cache_table.php`: High-performance caching layer.
- `0001_01_01_000002_create_jobs_table.php`: Background job processing support.

## 1.3 Setup Commands (Manifest)

The initialization follows a strict sequence to ensure state consistency:

```bash
# Application Scaffolding
laravel new startup-finance --git

# Security & Auth Engine
# We use Breeze (Blade) to provide a snappy, session-based auth layer.
composer require laravel/breeze --dev
php artisan breeze:install blade

# Asset Management
npm install && npm run build
```

## 1.4 Environment & Hardening

A unique application key is generated to enable state encryption (Sessions, Cookies):

- `php artisan key:generate`
- **Database Scope**: Initialization of the `startup_finance` database with UTF8MB4 support to handle complex descriptions and metadata.

## 1.5 Version Control Strategy

FFI development is managed via a strict git branching model:

- `main`: Implements the "Stable Financial Baseline."
- `dev`: High-velocity feature iteration and UI refinement.

---

*Status: **Phase 1 - Project Setup Complete.***
