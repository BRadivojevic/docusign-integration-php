<?php
require_once __DIR__.'/../../vendor/autoload.php';

use App\Config\Config;
use App\Logger\LoggerFactory;
use GuzzleHttp\Client;

$root = dirname(__DIR__, 2);
$cfg = new Config($root);
$logger = LoggerFactory::make('oauth', $cfg->logPath());

$code = $_GET['code'] ?? null;
if (!$code) { http_response_code(400); echo 'Missing code'; exit; }

$client = new Client();
$resp = $client->post('https://account.docusign.com/oauth/token', [
    'form_params' => [
        'grant_type' => 'authorization_code',
        'code' => $code,
        'client_id' => $cfg->dsClientId(),
        'client_secret' => $cfg->dsClientSecret(),
        'redirect_uri' => $cfg->dsRedirectUri()
    ]
]);

$data = json_decode((string)$resp->getBody(), true);
file_put_contents($root.'/var/token.json', json_encode($data, JSON_PRETTY_PRINT));
$logger->info('oauth.token.saved', ['expires_in' => $data['expires_in'] ?? null]);

echo "<h3>OAuth complete</h3><pre>".htmlspecialchars(json_encode($data, JSON_PRETTY_PRINT))."</pre>";
