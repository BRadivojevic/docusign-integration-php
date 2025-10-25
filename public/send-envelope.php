<?php
require_once __DIR__.'/../vendor/autoload.php';

use App\Config\Config;
use App\Logger\LoggerFactory;
use App\Queue\FileJobStore;
use App\Queue\SqliteJobStore;

header('Content-Type: application/json');
$root = dirname(__DIR__);
$cfg = new Config($root);
$logger = LoggerFactory::make('http', $cfg->logPath());

$payload = [
    'type' => 'send_envelope',
    'email' => $_GET['email'] ?? 'recipient@example.com',
    'name' => $_GET['name'] ?? 'Test Signer',
    'pdf_path' => $_GET['pdf'] ?? __DIR__.'/demo.pdf',
    'subject' => 'Please sign (Demo)'
];

try {
    if ($cfg->jobStore() === 'sqlite') {
        $store = new SqliteJobStore($root.'/var/jobs.sqlite');
    } else {
        $store = new FileJobStore($cfg->jobsFile());
    }
    $jobId = $store->enqueue($payload);
    $logger->info('enqueue.ok', ['job_id'=>$jobId, 'type'=>$payload['type']]);
    echo json_encode(['job_id' => $jobId]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
