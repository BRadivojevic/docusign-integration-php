<?php
require_once __DIR__.'/../../vendor/autoload.php';

use App\Config\Config;

$root = dirname(__DIR__, 2);
$cfg = new Config($root);

$clientId = $cfg->dsClientId();
$redirect = urlencode($cfg->dsRedirectUri());
$baseAuth = 'https://account.docusign.com/oauth/auth'; // demo/prod auto-redirect
$scope = urlencode('signature impersonation');
$state = bin2hex(random_bytes(8));

header('Location: '.$baseAuth.'?response_type=code&scope='.$scope.'&client_id='.$clientId.'&redirect_uri='.$redirect.'&state='.$state);
