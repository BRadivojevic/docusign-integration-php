<?php
namespace App\Support;

final class Env {
	public static function get(string $key, ?string $default = null): ?string {
		return $_ENV[$key] ?? getenv($key) ?: $default;
	}
}
