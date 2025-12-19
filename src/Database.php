<?php
namespace Memory\App;
use PDO;
use PDOException;

class Database {
	private static ?Database $instance = null;
	private PDO $pdo;

	private function __construct() {
		$config = require __DIR__ . '/../config/db.php';
		$dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8mb4";

		$options = [
			PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
			PDO::ATTR_EMULATE_PREPARES   => false,
		];

		try {
			$this->pdo = new PDO($dsn, $config['user'], $config['password'], $options);
		} catch (PDOException $e) {
			die("Database connection failed: " . $e->getMessage());
		}
	}

	public static function getInstance(): Database {
		if (self::$instance === null) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function getPdo(): PDO {
		return $this->pdo;
	}

	private function __clone() {}
	public function __wakeup() {}
}