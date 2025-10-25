<?php
namespace App\Webhooks;

final class HmacVerifier {
    /** Returns true if the given payload and header signature match using the shared secret. */
    public static function verify(string $rawBody, string $headerSig, string $secret): bool {
        if ($secret === '') return false;
        $calc = base64_encode(hash_hmac('sha256', $rawBody, $secret, true));
        return hash_equals($calc, $headerSig);
    }
}
