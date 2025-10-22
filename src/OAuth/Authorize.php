<?php
namespace App\OAuth;
use App\Support\Env;

final class Authorize {
	public static function url(): string {
		$q = http_build_query([
			'response_type' => 'code',
			'client_id' => Env::get('DOCUSIGN_CLIENT_ID'),
			'redirect_uri' => Env::get('DOCUSIGN_REDIRECT_URI'),
			'scope' => 'signature'
		]);
		return Env::get('DOCUSIGN_AUTH_URL').'?'.$q;
	}
}
