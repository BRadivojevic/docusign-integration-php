<?php
namespace App\OAuth;
use App\Support\Env;
use App\Support\Http;

final class Callback {
	public static function handle(): void {
		$code = $_GET['code'] ?? null;
		if (!$code) { Http::json(['error'=>'missing_code'], 400); return; }
		[$status, $body] = Http::post(
			Env::get('DOCUSIGN_TOKEN_URL'),
			['Content-Type: application/x-www-form-urlencoded'],
			[
				'grant_type' => 'authorization_code',
				'code' => $code,
				'client_id' => Env::get('DOCUSIGN_CLIENT_ID'),
				'client_secret' => Env::get('DOCUSIGN_CLIENT_SECRET'),
				'redirect_uri' => Env::get('DOCUSIGN_REDIRECT_URI')
			]
		);
		Http::json(['status'=>$status, 'raw'=>json_decode($body, true)]);
	}
}
