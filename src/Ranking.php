<?php
namespace Memory\App;

use PDO;

class Ranking {

	protected function getPdo(): PDO {
		return Database::getInstance()->getPdo();
	}

	public function getTopScores(): array {
		$pdo = $this->getPdo();
		$stmt = $pdo->query("SELECT DISTINCT pairs_count FROM scores ORDER BY pairs_count ASC");
		$pairsCounts = $stmt->fetchAll(PDO::FETCH_COLUMN);
		$allRankings = [];

		foreach ($pairsCounts as $pairsCount) {
			$sql = "
			SELECT
			s.moves_count,
			s.time_taken_seconds,
			s.calculated_score,
			p.username
			FROM
			scores s
			JOIN
			players p ON s.player_id = p.id
			WHERE
			s.pairs_count = :pairs_count
			ORDER BY
			s.calculated_score DESC,
			s.finished_at ASC
			LIMIT 10
			";

			$stmt = $pdo->prepare($sql);

			$stmt->execute(['pairs_count' => $pairsCount]);

			$allRankings[$pairsCount] = $stmt->fetchAll(PDO::FETCH_ASSOC);
		}
		return $allRankings;
	}

	public function getUserBestScores(int $userId): array {
		$pdo = $this->getPdo();

		$sql = "
		SELECT
		s.pairs_count,
		MAX(s.calculated_score) AS best_score,
		(
			SELECT MIN(s2.time_taken_seconds)
			FROM scores s2
			WHERE s2.player_id = s.player_id
			AND s2.pairs_count = s.pairs_count
			AND s2.moves_count = MIN(s.moves_count)
			) AS best_time
		FROM
		scores s
		WHERE
		s.player_id = :user_id
		GROUP BY
		s.pairs_count
		ORDER BY
		s.pairs_count ASC
		";

		$stmt = $pdo->prepare($sql);

		$stmt->execute(['user_id' => $userId]);
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getUserScoreHistory(int $userId): array {
		$pdo = $this->getPdo();

		$sql = "
		SELECT
		pairs_count,
		moves_count AS moves,
		time_taken_seconds AS time_taken,
		calculated_score,
		finished_at
		FROM scores
		WHERE player_id = :user_id
		ORDER BY finished_at DESC
		";

		$stmt = $pdo->prepare($sql);

		$stmt->execute(['user_id' => $userId]);
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
}