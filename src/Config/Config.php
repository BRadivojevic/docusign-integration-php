<?php
namespace App\Config;

use Dotenv\Dotenv;

final class Config {
    private array $env;

    public function __construct(string $rootDir) {
        if (is_file($rootDir.'/.env')) {
            $dotenv = Dotenv::createImmutable($rootDir);
            $dotenv->load();
        }
        $this->env = $_ENV + $_SERVER;
    }

    public function logPath(): string { return $this->env['APP_LOG'] ?? __DIR__.'/../../var/app.log'; }
    public function jobStore(): string { return $this->env['JOB_STORE'] ?? 'file'; }
    public function jobsFile(): string { return $this->env['JOBS_FILE'] ?? __DIR__.'/../../var/jobs.jsonl'; }
    public function deadLetterFile(): string { return $this->env['DEADLETTER_FILE'] ?? __DIR__.'/../../var/deadletter.jsonl'; }

    public function dsClientId(): string { return $this->env['DS_CLIENT_ID'] ?? ''; }
    public function dsClientSecret(): string { return $this->env['DS_CLIENT_SECRET'] ?? ''; }
    public function dsImpersonatedUserGuid(): string { return $this->env['DS_IMPERSONATED_USER_GUID'] ?? ''; }
    public function dsRedirectUri(): string { return $this->env['DS_REDIRECT_URI'] ?? 'http://localhost:8000/oauth/callback.php'; }
    public function dsBasePath(): string { return $this->env['DS_BASE_PATH'] ?? 'https://demo.docusign.net/restapi'; }
    public function dsAccountId(): string { return $this->env['DS_ACCOUNT_ID'] ?? ''; }
    public function dsEnvironment(): string { return $this->env['DS_ENVIRONMENT'] ?? 'demo'; }

    public function webhookSecret(): string { return $this->env['WEBHOOK_SECRET'] ?? ''; }
}
