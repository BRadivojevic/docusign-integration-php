<?php
require_once __DIR__.'/../vendor/autoload.php';

use App\Config\Config;
use App\Logger\LoggerFactory;
use App\Webhooks\HmacVerifier;

$root = dirname(__DIR__);
$cfg = new Config($root);
$logger = LoggerFactory::make('webhook', $cfg->logPath());

$raw = file_get_contents('php://input');
$signature = $_SERVER['HTTP_X_DOCUSIGN_SIGNATURE_256'] ?? '';

if (!HmacVerifier::verify($raw, $signature, $cfg->webhookSecret())) {
    http_response_code(401);
    echo 'Bad signature';
    $logger->warning('webhook.bad_signature');
    exit;
}

$payload = json_decode($raw, true);
$logger->info('webhook.ok', ['event' => $payload['event'] ?? 'unknown']);
echo 'ok';
