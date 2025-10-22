<?php
namespace App\Api;
use App\Support\Env;
use App\Support\Http;

final class SendEnvelope {
	public static function send(string $accessToken, string $email): void {
		$url = rtrim(Env::get('DOCUSIGN_BASE_URL'),'/').'/restapi/v2.1/accounts/me/envelopes';
		$payload = [
			'emailSubject' => 'Demo Envelope',
			'documents' => [['documentBase64' => base64_encode('Hello'), 'documentId' => '1', 'name' => 'hello.txt']],
			'recipients' => ['signers' => [['email' => $email, 'name' => 'Recipient', 'recipientId' => '1']]],
			'status' => 'sent'
		];
		$ch = curl_init($url);
		curl_setopt_array($ch, [
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HTTPHEADER => [
				'Authorization: Bearer '.$accessToken,
				'Content-Type: application/json'
			],
			CURLOPT_POST => true,
			CURLOPT_POSTFIELDS => json_encode($payload)
		]);
		$body = curl_exec($ch);
		$err  = curl_error($ch);
		$code = (int)curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
		curl_close($ch);
		Http::json(['status'=>$code, 'raw'=>json_decode($body, true), 'err'=>$err]);
	}
}
