
# DocuSign Integration (PHP)

Production-grade **DocuSign** integration with:
- **OAuth authorization code** flow (PKCE-ready)
- **Envelope queue + background worker** (avoid HTTP timeouts; throttle safely)
- **Retries + dead-letter** on transient failures
- **Webhooks (Connect)** with HMAC verification
- **.env config + JSON logging**

**Author:** Boško Radivojević — [BRadivojevic](https://github.com/BRadivojevic)

## Why this is production-shaped
- Long running operations are **queued** and executed by **workers**, not HTTP.
- **Idempotency keys** on envelope creation; safe retries.
- **HMAC validation** for incoming webhooks.
- Clean structure, Composer autoload, and a **minimal Console**.

## Project structure
```
/public
  index.php            # demo links
  oauth/start.php      # redirects to DocuSign
  oauth/callback.php   # handles OAuth
  send-envelope.php    # enqueues envelope
  job-status.php       # check job status
  webhook.php          # DocuSign Connect webhook
/src
  Config/Config.php
  Logger/LoggerFactory.php
  Queue/FileJobStore.php
  Queue/JobStoreInterface.php
  Queue/SqliteJobStore.php
  DocuSign/ClientFactory.php
  Services/EnvelopeService.php
  Webhooks/HmacVerifier.php
/workers
  send_envelope_worker.php
bin/
  ds-send              # optional CLI send wrapper
var/                   # logs, job store, dead-letter
```

## Setup (no bash required)
1. Open in PhpStorm/VS Code, use Composer GUI to **Install**.
2. Copy `.env.example` → `.env` and fill your DocuSign Integrator Key, Secret, Redirect URI.
3. Serve `public/` from IDE (built-in server).
4. Click **“Start OAuth”** → after consent, you’ll land on `oauth/callback.php`.

## Quick demo
- Visit `/` (index) and click **Send Envelope** → it enqueues a job.
- Run **workers/send_envelope_worker.php** from your IDE to process the queue.
- Check **/public/job-status.php?id=...** to see job state.
- Webhooks: configure DocuSign Connect to POST to `https://<your-host>/webhook.php` with the shared secret.

## Notes
- Uses `docusign/esign-client` (PHP SDK) and Guzzle under the hood.
- Switch `JOB_STORE` to `sqlite` if you prefer an embedded DB for jobs.
- Dead-letter and JSON logs are in `var/`.

MIT © 2025 Boško Radivojević
