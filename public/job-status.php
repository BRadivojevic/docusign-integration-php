<?php
require_once __DIR__.'/../vendor/autoload.php';

use App\Config\Config;
use App\Queue\FileJobStore;
use App\Queue\SqliteJobStore;

header('Content-Type: application/json');
$root = dirname(__DIR__);
$cfg = new Config($root);
$id = $_GET['id'] ?? '';

try {
    if ($cfg->jobStore() === 'sqlite') {
        $store = new SqliteJobStore($root.'/var/jobs.sqlite');
    } else {
        $store = new FileJobStore($cfg->jobsFile());
    }
    $row = $store->get($id);
    if (!$row) { http_response_code(404); echo json_encode(['error'=>'not found']); exit; }
    echo json_encode($row);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
