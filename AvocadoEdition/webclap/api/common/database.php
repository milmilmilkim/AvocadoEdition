<?php
if (!defined('_WEBCLAP_')) exit;

class Database
{
	protected static $conn;
	private static $host = WC_HOST;
	private static $db_name = WC_DB;
	private static $username = WC_USER;
	private static $password = WC_PASSWORD;

	private function __construct()
	{
		try {
			self::$conn = new PDO("mysql:host=" . self::$host . ";dbname=" . self::$db_name, self::$username, self::$password);
			self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch (Exception $e) {
			throw new PDOException("DB에 연결할 수 없습니다.");
		}
	}

	public static function getConnection()
	{
		if (!self::$conn) {
			new Database();
		}
		return self::$conn;
	}
}
