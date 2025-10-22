<?php
require __DIR__.'/../vendor/autoload.php';
use App\Api\SendEnvelope;
$token = $_GET['access_token'] ?? '';
$email = $_GET['email'] ?? 'test@example.com';
SendEnvelope::send($token, $email);
