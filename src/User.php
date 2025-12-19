<?php
namespace Memory\App;

use Memory\App\Abstract\DatabaseEntity;
use Memory\App\Interface\Authenticatable;
use PDOException;

class User extends DatabaseEntity implements Authenticatable {
	protected ?string $username = null;
	protected ?string $passwordHash = null;

	protected function getTableName(): string {
		return 'players';
	}

	public function getUsername(): ?string {
		return $this->username;
	}

	public function setUsername(string $username): void {
		$this->username = $username;
	}

	public function setPasswordHash(string $hash): void {
		$this->passwordHash = $hash;
	}

	public static function logIn(string $login, string $password): ?self {
		$user = new self();
		$pdo = $user->getPdo();

		$stmt = $pdo->prepare("SELECT * FROM {$user->getTableName()} WHERE username = :username");

		$stmt->execute(['username' => $login]);

		$data = $stmt->fetch();

		if (!$data) {
			return null;
		}
		$loggedInUser = new self();
		$loggedInUser->hydrate($data);

		if (password_verify($password, $loggedInUser->passwordHash)) {
			$_SESSION['user_id'] = $loggedInUser->getId();
			return $loggedInUser;
		}
		return null;
	}

	public static function signUp(array $data): bool {
		$user = new self();
		$pdo = $user->getPdo();

		$stmt = $pdo->prepare("SELECT id FROM {$user->getTableName()} WHERE username = :username");
		$stmt->execute(['username' => $data['login'] ?? $data['username']]); // Handle 'login' key from controller

		if ($stmt->fetch()) {
			return false;
		}

		$dataToInsert = [
			'username' => $data['login'] ?? $data['username'],
			'password_hash' => password_hash($data['password'], PASSWORD_DEFAULT),
		];

		try {
			$user->insert($dataToInsert);
			return true;
		} catch (PDOException $e) {
			error_log("User sign up error: " . $e->getMessage());
			return false;
		}
	}

	public static function logOff(): void {
		if (isset($_SESSION['user_id'])) {
			unset($_SESSION['user_id']);
		}
	}

	public static function isLoggedIn(): bool {
		return isset($_SESSION['user_id']) && is_numeric($_SESSION['user_id']);
	}

	public static function getCurrentUser(): ?self {
		if (self::isLoggedIn()) {
			$user = new self();
			$user->findById((int)$_SESSION['user_id']);

			if ($user->getId()) {
				return $user;
			}
			self::logOff();
		}
		return null;
	}

	public function getLogin(): ?string {
		return $this->getUsername();
	}
}