<?php
namespace Memory\App\Abstract;

use Memory\App\Database;
use PDO;
use Exception;

abstract class DatabaseEntity {
	protected ?int $id = null;

	abstract protected function getTableName(): string;

	protected function getPdo(): PDO {
		return Database::getInstance()->getPdo();
	}

	public function __construct(?int $id = null) {
		if ($id !== null) {
			$this->findById($id);
		}
	}

	public function getId(): ?int {
		return $this->id;
	}

	protected function setId(int $id): void {
		$this->id = $id;
	}

	public function findById(int $id): self {
		$pdo = $this->getPdo();
		$stmt = $pdo->prepare("SELECT * FROM {$this->getTableName()} WHERE id = :id");
		$stmt->execute(['id' => $id]);
		$data = $stmt->fetch();

		if ($data) {
			$this->hydrate($data);
		}
		return $this;
	}

	protected function hydrate(array $data): void {
		foreach ($data as $key => $value) {
			$method = 'set' . str_replace('_', '', ucwords($key, '_'));
			if (method_exists($this, $method)) {
				$this->$method($value);
			}
		}
		if (isset($data['id'])) {
			$this->id = (int)$data['id'];
		}
	}

	public function insert(array $data): self {
		$pdo = $this->getPdo();
		$columns = implode(", ", array_keys($data));
		$placeholders = ":" . implode(", :", array_keys($data));
		$sql = "INSERT INTO {$this->getTableName()} ($columns) VALUES ($placeholders)";
		$stmt = $pdo->prepare($sql);
		$stmt->execute($data);

		$this->setId((int)$pdo->lastInsertId());

		return $this;
	}

	public function update(array $data): self {
		if ($this->id === null) {
			throw new Exception("Cannot update an entity without an ID.");
		}

		$pdo = $this->getPdo();
		$setClauses = [];
		foreach (array_keys($data) as $column) {
			$setClauses[] = "{$column} = :{$column}";
		}

		$sql = "UPDATE {$this->getTableName()} SET " . implode(", ", $setClauses) . " WHERE id = :id";
		$data['id'] = $this->id;

		$stmt = $pdo->prepare($sql);
		$stmt->execute($data);

		return $this;
	}

	public function delete(): self {
		if ($this->id === null) {
			return $this;
		}
		$pdo = $this->getPdo();
		$stmt = $pdo->prepare("DELETE FROM {$this->getTableName()} WHERE id = :id");
		$stmt->execute(['id' => $this->id]);

		$this->id = null;

		return $this;
	}
}