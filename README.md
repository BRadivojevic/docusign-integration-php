# DocuSign Integration (PHP)

Simple, framework-free DocuSign OAuth2 integration and digital document sending system.  
Implements a clean authorization flow, minimal envelope sending, and webhook listener in pure PHP.

---

## 🚀 Overview
This project demonstrates a **production-safe, minimal DocuSign implementation** used in enterprise environments.  
It integrates **OAuth2 Authorization Code Flow**, supports **automatic token refresh**, and enables **envelope sending and webhook processing**.

**Core Concepts**
- 🔐 Secure OAuth2 Authorization Flow  
- 📤 Minimal envelope send via REST API  
- 🔄 Webhook listener for status updates  
- ⚙️ Environment-driven config (`.env`)  
- 🧩 Clear structure (`src/` + `public/`) for enterprise extensibility  

---

## 🧠 Tech Stack
| Layer | Technology |
|:--|:--|
| Language | PHP 8+ |
| HTTP | cURL |
| Autoload | PSR-4 (Composer) |
| Config | `.env` variables (dotenv) |
| Platform | DocuSign REST API (OAuth2 + Envelopes + Webhooks) |

---

## ⚙️ Installation & Setup

```bash
git clone https://github.com/BRadivojevic/docusign-integration-php.git
cd docusign-integration-php
composer install
cp .env.example .env
php -S localhost:8080 -t public
