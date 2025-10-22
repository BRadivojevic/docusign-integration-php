<?php
require __DIR__.'/../vendor/autoload.php';
use App\OAuth\Authorize;
header('Location: '.Authorize::url());
