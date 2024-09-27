<?php
if (!defined('_WEBCLAP_')) exit;

require_once BASEDIR . 'libs/jwt/BeforeValidException.php';
require_once BASEDIR . 'libs/jwt/ExpiredException.php';
require_once BASEDIR . 'libs/jwt/SignatureInvalidException.php';
require_once BASEDIR . 'libs/jwt/JWT.php';

use \Firebase\JWT\JWT;

class Token
{
	private static $key = 'S#E^C&RET*KE@Y';
	private static $max_age = 60 * 60; // 1시간
	private static $payload_key = 'jtp';
	private static $signature_key = 'jts';

	public static function generate()
	{
		// 토큰 생성
		$iat = time();
		$expire = $iat + static::$max_age;
		$token = array(
			'iss' => 'webclap', // Issuer
			'iat' => $iat, // Issued at: time when the token was generated
			// 'nbf' => $iat + 10, // Not before
			'exp' => $expire, // Expire
		);
		$jwt = JWT::encode($token, static::$key);

		// 쿠키에 저장
		$tokens = explode('.', $jwt);
		$is_secure = static::isSecure();
		setcookie(static::$payload_key, $tokens[0] . '.' . $tokens[1], $expire, '/', $_SERVER['SERVER_NAME'], $is_secure, false);
		setcookie(static::$signature_key, $tokens[2], 0, '/', $_SERVER['SERVER_NAME'], $is_secure, true);
	}

	public static function verify()
	{
		if (!isset($_COOKIE[static::$payload_key]) || !isset($_COOKIE[static::$signature_key])) {
			return false;
		}
		$payload = $_COOKIE[static::$payload_key];
		$signature = $_COOKIE[static::$signature_key];
		try {
			JWT::decode($payload . '.' . $signature, static::$key, array('HS256'));
			return true;
		} catch (Exception $e) {
			echo $e->getMessage();
			return false;
		}
	}

	public static function protect()
	{
		if (!static::verify()) {
			echo parseJsonError(401, "로그인이 필요합니다.");
			exit;
		} else {
			static::generate();
		}
	}

	public static function refresh()
	{
		if (static::verify()) {
			static::generate();
		}
	}

	public static function destroy()
	{
		$is_secure = static::isSecure();
		setcookie(static::$payload_key, '', time() - 3600, '/', $_SERVER['SERVER_NAME'], $is_secure, false);
		setcookie(static::$signature_key, '', time() - 3600, '/', $_SERVER['SERVER_NAME'], $is_secure, true);
	}

	public static function isSecure()
	{
		return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') || (isset($_SERVER['SERVER_PORT']) && (int) $_SERVER['SERVER_PORT'] === 443);
	}
}
