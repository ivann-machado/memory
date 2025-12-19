<?php
namespace Controller;

use Memory\App\Board;
use Memory\App\Card;
use Memory\App\User;
use Memory\App\Score;
use Core\View;

class GameController {
	const MIN_PAIRS = 3;
	const MAX_PAIRS = 12;

	public function index() {
		if (isset($_SESSION['board'])) {
			/** @var Board $board */
			$board = $_SESSION['board'];

			if (isset($_GET['new']) && $_GET['new'] == 1) {
				$this->resetGame();
				return;
			}

			$this->renderBoard($board);
		}
		else {
			$this->renderStartForm();
		}
	}

	public function handleAction() {
		if (isset($_SESSION['board'])) {
			$board = $_SESSION['board'];

			if (count($board->getFlippedCardIds()) === 2 && !$board->isGameOver()) {
				$board->clearFlippedCards();
				$_SESSION['board'] = $board;
				header('Location: ./');
				exit();
			}
		}

		if (isset($_POST['start_game']) && isset($_POST['pairs'])) {
			$this->startGame((int)$_POST['pairs']);
		}
		elseif (isset($_POST['card_id']) && isset($_SESSION['board'])) {
			$this->flipCard((int)$_POST['card_id']);
		}
		else {
			header('Location: ./');
			exit();
		}
	}

	private function startGame(int $pairsCount) {
		$pairsCount = max(self::MIN_PAIRS, min(self::MAX_PAIRS, $pairsCount));
		$cardValues = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L'];
		$values = array_slice($cardValues, 0, $pairsCount);

		$board = new Board($pairsCount, $values);
		$_SESSION['board'] = $board;

		header('Location: ./');
		exit();
	}

	private function flipCard(int $cardId) {
		$board = $_SESSION['board'];

		if (count($board->getFlippedCardIds()) === 2) {
			header('Location: ./');
			exit();
		}

		$board->flipCard($cardId);
		$_SESSION['board'] = $board;

		if ($board->isGameOver()) {
			$this->saveScore($board);
		}

		header('Location: ./');
		exit();
	}

	private function renderStartForm() {
		$view = new View('game/start');
		$view->render();
	}

	private function renderBoard(Board $board) {
		$view = new View('game/board', ['board' => $board]);
		$view->render();
	}

	public function resetGame() {
		unset($_SESSION['board']);
		unset($_SESSION['calculated_score']);
		header('Location: ./');
		exit();
	}

	private function saveScore(Board $board) {
		$timeTaken = $board->getTimeTakenSeconds();
		$moves = $board->getMoves();

		if ($moves > 0 && $timeTaken > 0) {
			$calculatedScore = (1 / ($moves * $timeTaken)) * 1000000;
		} else {
			$calculatedScore = 0;
		}

		$_SESSION['calculated_score'] = $calculatedScore;

		$user = User::getCurrentUser();
		if (!$user) {
			return;
		}

		$score = new Score();
		$saved = $score->saveNewScore(
			$user->getId(),
			$board->getPairsCount(),
			$moves,
			$timeTaken,
			$calculatedScore
		);

		if (!$saved) {
			error_log("Failed to save score for user ID: " . $user->getId());
		}
	}
}