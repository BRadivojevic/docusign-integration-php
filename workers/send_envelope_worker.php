<?php
require_once __DIR__.'/../vendor/autoload.php';

use App\Config\Config;
use App\Logger\LoggerFactory;
use App\Queue\FileJobStore;
use App\Queue\SqliteJobStore;
use App\DocuSign\ClientFactory;
use App\Services\EnvelopeService;

use DocuSign\eSign\Api\EnvelopesApi;

$root = dirname(__DIR__);
$cfg = new Config($root);
$logger = LoggerFactory::make('worker.send', $cfg->logPath());

if ($cfg->jobStore() === 'sqlite') {
    $store = new SqliteJobStore($root.'/var/jobs.sqlite');
} else {
    $store = new FileJobStore($cfg->jobsFile());
}

$job = $store->nextQueued();
if (!$job) { $logger->info('no.jobs'); exit(0); }

$store->markRunning($job['id']);
$payload = $job['payload'] ?? $job;

try {
    // Load token from var/token.json (created by OAuth callback)
    $tok = json_decode(@file_get_contents($root.'/var/token.json'), true);
    if (!$tok || empty($tok['access_token'])) throw new RuntimeException('Missing access token, run OAuth first');
    $accessToken = $tok['access_token'];

    $apiClient = ClientFactory::make($cfg->dsBasePath(), $accessToken, $cfg->dsAccountId());
    $envelopesApi = new EnvelopesApi($apiClient);
    $svc = new EnvelopeService($envelopesApi, $cfg->dsAccountId());

    // Prepare PDF
    $pdfPath = $payload['pdf_path'];
    if (!is_file($pdfPath)) throw new RuntimeException('PDF not found: '.$pdfPath);
    $doc64 = base64_encode(file_get_contents($pdfPath));

    $envelopeId = $svc->createSimpleEnvelope($payload['email'], $payload['name'], $doc64, $payload['subject']);
    $store->markFinished($job['id'], ['envelopeId'=>$envelopeId]);
    $logger->info('envelope.sent', ['job_id'=>$job['id'], 'envelopeId'=>$envelopeId]);
} catch (Throwable $e) {
    $store->markFailed($job['id'], $e->getMessage());
    $dead = $cfg->deadLetterFile();
    @file_put_contents($dead, json_encode(['job'=>$job, 'error'=>$e->getMessage()])."\n", FILE_APPEND);
    $logger->error('envelope.failed', ['job_id'=>$job['id'], 'error'=>$e->getMessage()]);
    exit(1);
}
