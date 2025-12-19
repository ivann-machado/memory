<?php
namespace Memory\App;

use Exception;

class Board {
	private array $cards = [];
	private array $flippedCardIds = [];
	private int $moves = 0;
	private int $pairsCount;
	private ?float $startTime = null;
	private ?float $endTime = null;
	private ?int $timeTakenSeconds = null;

	public function __construct(int $pairsCount, array $values) {
		$this->pairsCount = $pairsCount;
		$this->initializeCards($values);
		$this->startTime = microtime(true);
	}

	private function initializeCards(array $values): void {
		$cardId = 1;
		foreach ($values as $value) {
			$this->cards[$cardId] = new Card($cardId++, $value);
			$this->cards[$cardId] = new Card($cardId++, $value);
		}
		shuffle($this->cards);
		$this->cards = array_values($this->cards);
	}

	public function getCards(): array {
		return $this->cards;
	}

	public function getMoves(): int {
		return $this->moves;
	}

	public function getPairsCount(): int {
		return $this->pairsCount;
	}

	public function getFlippedCardIds(): array {
		return $this->flippedCardIds;
	}

	public function isGameOver(): bool {
		$matchedCount = 0;

		foreach ($this->cards as $card) {
			if ($card->isMatched()) {
				$matchedCount++;
			}
		}
		$isOver = $matchedCount === count($this->cards);

		if ($isOver && $this->endTime === null) {
			$this->endTime = microtime(true);
			$this->timeTakenSeconds = (int)round($this->endTime - $this->startTime);
		}

		return $isOver;
	}

	public function getTimeTakenSeconds(): int {
		if ($this->timeTakenSeconds !== null) {
			return $this->timeTakenSeconds;
		}

		if ($this->startTime !== null) {
			return (int)round(microtime(true) - $this->startTime);
		}

		return 0;
	}

	public function setTimeTakenSeconds(int $seconds): void {
		$this->timeTakenSeconds = $seconds;
	}

	public function flipCard(int $cardId): bool {
		$targetCard = null;
		foreach ($this->cards as $card) {
			if ($card->getId() === $cardId) {
				$targetCard = $card;
				break;
			}
		}
		if (!$targetCard) {
			throw new Exception("Card ID {$cardId} not found.");
		}
		if (count($this->flippedCardIds) === 2) {
			$this->clearFlippedCards();
		}

		if ($targetCard->isFlipped()) {
			return false;
		}

		$targetCard->flip();
		$this->flippedCardIds[] = $cardId;

		if (count($this->flippedCardIds) === 2) {
			$this->moves++;
			return $this->checkForMatch();
		}
		return false;
	}

	private function checkForMatch(): bool {
		$card1 = $this->getCardById($this->flippedCardIds[0]);
		$card2 = $this->getCardById($this->flippedCardIds[1]);

		if ($card1 && $card2 && $card1->getValue() === $card2->getValue()) {
			$card1->match();
			$card2->match();
			$this->flippedCardIds = [];
			return true;
		}
		return false;
	}

	public function clearFlippedCards(): void {
		if (count($this->flippedCardIds) === 2) {
			$card1 = $this->getCardById($this->flippedCardIds[0]);
			$card2 = $this->getCardById($this->flippedCardIds[1]);

			if ($card1 && $card2 && !$card1->isMatched() && !$card2->isMatched()) {
				$card1->unflip();
				$card2->unflip();
			}
			$this->flippedCardIds = [];
		}
	}

	private function getCardById(int $id): ?Card {
		foreach ($this->cards as $card) {
			if ($card->getId() === $id) {
				return $card;
			}
		}
		return null;
	}

	public function __sleep(): array {
		return ['cards', 'flippedCardIds', 'moves', 'pairsCount', 'startTime', 'endTime', 'timeTakenSeconds'];
	}

	public function __wakeup(): void {}
}