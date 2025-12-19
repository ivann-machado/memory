<?php
namespace Memory\App;

class Card {
	private int $id;
	private string $value;
	private bool $isFlipped = false;
	private bool $isMatched = false;

	public function __construct(int $id, string $value) {
		$this->id = $id;
		$this->value = $value;
	}

	public function getId(): int { return $this->id; }
	public function getValue(): string { return $this->value; }
	public function isFlipped(): bool { return $this->isFlipped; }
	public function isMatched(): bool { return $this->isMatched; }

	// --- State Mutators ---
	public function flip(): void {
		$this->isFlipped = true;
	}

	public function unflip(): void {
		if (!$this->isMatched) {
			$this->isFlipped = false;
		}
	}

	public function match(): void {
		$this->isMatched = true;
		$this->isFlipped = true;
	}

	public function __serialize(): array {
		return [
			'id' => $this->id,
			'value' => $this->value,
			'isFlipped' => $this->isFlipped,
			'isMatched' => $this->isMatched,
		];
	}

	public function __unserialize(array $data): void {
		$this->id = $data['id'] ?? 0;
		$this->value = $data['value'] ?? '';
		$this->isFlipped = $data['isFlipped'] ?? false;
		$this->isMatched = $data['isMatched'] ?? false;
	}
}