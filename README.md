# DocuSign Integration (PHP)

OAuth authorization code flow + minimal send + webhook skeleton in plain PHP. Fully env-driven, no frameworks.

## Features
- OAuth Authorization Code flow (redirect → callback → token exchange)
- Minimal envelope send example
- Clean separation (`src/` + `public/`) and small helpers (Env, Http)
- Safe to extend in enterprise contexts

## Tech
PHP 8+, cURL, PSR-4 autoload (Composer)

## Quick Start
```bash
cp .env.example .env
composer install
php -S localhost:8080 -t public
