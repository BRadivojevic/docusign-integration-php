# DocuSign Integration (PHP)
OAuth + simple send + webhook skeleton.

## Quick start
```bash
cp .env.example .env
composer install
php -S localhost:8080 -t public
Visit http://localhost:8080/authorize.php to start OAuth.

Paste your access token into send.php?access_token=...&email=... to send a demo envelope.

Do not commit .env. Replace BASE_URL with demo/production as needed.

sql
Copy code

## C) Commit & push
```bash
git add .
git commit -m "Initial: DocuSign OAuth + minimal send (env-driven)"
git push -u origin main
