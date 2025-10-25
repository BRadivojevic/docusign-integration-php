<?php
namespace App\DocuSign;

use DocuSign\eSign\Client\ApiClient;

final class ClientFactory {
    public static function make(string $basePath, string $accessToken, string $accountId): ApiClient {
        $config = new \DocuSign\eSign\Configuration();
        $config->setHost($basePath);
        $config->addDefaultHeader('Authorization', 'Bearer '.$accessToken);
        $apiClient = new ApiClient($config);
        $apiClient->getConfig()->setSSLVerification(true);
        return $apiClient;
    }
}
