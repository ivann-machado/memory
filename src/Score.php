<?php

namespace Memory\App;

use Memory\App\Abstract\DatabaseEntity;
use PDO;
use Memory\App\Database;

class Score extends DatabaseEntity {
	protected ?int $playerId = null;
	protected ?int $pairsCount = null;
	protected ?int $movesCount = null;
	protected ?int $timeTakenSeconds = null;
	protected ?float $calculatedScore = null; // New property
	protected ?string $finishedAt = null;

	protected function getTableName(): string {
		return 'scores';
	}

	public function getPlayerId(): ?int { return $this->playerId; }
	public function setPlayerId(int $playerId): void { $this->playerId = $playerId; }

	public function getPairsCount(): ?int { return $this->pairsCount; }
	public function setPairsCount(int $pairsCount): void { $this->pairsCount = $pairsCount; }

	public function getMovesCount(): ?int { return $this->movesCount; }
	public function setMovesCount(int $movesCount): void { $this->movesCount = $movesCount; }

	public function getTimeTakenSeconds(): ?int { return $this->timeTakenSeconds; }
	public function setTimeTakenSeconds(int $timeTakenSeconds): void { $this->timeTakenSeconds = $timeTakenSeconds; }

	public function getCalculatedScore(): ?float { return $this->calculatedScore; }
	public function setCalculatedScore(float $score): void { $this->calculatedScore = $score; }

	public function setFinishedAt(string $finishedAt): void { $this->finishedAt = $finishedAt; }
	public function getFinishedAt(): ?string { return $this->finishedAt; }

	public function saveNewScore(int $userId, int $pairs, int $moves, int $time, float $score): bool {
		$this->playerId = $userId;
		$this->pairsCount = $pairs;
		$this->movesCount = $moves;
		$this->timeTakenSeconds = $time;
		$this->calculatedScore = $score;

		$data = [
			'player_id' => $this->playerId,
			'pairs_count' => $this->pairsCount,
			'moves_count' => $this->movesCount,
			'time_taken_seconds' => $this->timeTakenSeconds,
			'calculated_score' => $this->calculatedScore,
			'finished_at' => date('Y-m-d H:i:s'),
		];

		return $this->insert($data) instanceof self;
	}
}