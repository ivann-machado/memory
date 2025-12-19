<?php
$isWaitingForClear = count($board->getFlippedCardIds()) === 2 && !$board->isGameOver();
$pairsCount = $board->getPairsCount();
$numCols = $pairsCount <= 6 ? 4 : 6;

if (!function_exists('formatTime')) {
	function formatTime(int $seconds): string {
		$minutes = floor($seconds / 60);
		$secs = $seconds % 60;
		return sprintf('%02d:%02d', $minutes, $secs);
	}
}
?>

<p>Mouvements: <strong><?= $board->getMoves() ?></strong></p>

<?php if ($board->isGameOver()): ?>
	<h2 class="success">Partie terminée ! Bravo !</h2>
	<?php
	$finalScore = $_SESSION['calculated_score'] ?? 0;
	$timeTaken = $board->getTimeTakenSeconds();
	?>
	<div class="score-summary">
		<h3>Votre Score</h3>
		<p><strong>Nombre de Paires:</strong> <?= htmlspecialchars($pairsCount) ?></p>
		<p><strong>Mouvements Totaux:</strong> <?= htmlspecialchars($board->getMoves()) ?></p>
		<p><strong>Temps Écoulé:</strong> <span class="score-val-time"><?= formatTime($timeTaken) ?></span></p>
		<p><strong>Score Calculé:</strong> <span class="score-val-calc"><?= number_format($finalScore, 2) ?></span></p>
		<p class="mt-15">Votre score a été enregistré si vous êtes connecté. Consultez le <a href="/ranking" class="inline-link">Classement</a>!</p>
	</div>

	<div class="mt-20">
		<a href="/?new=1" class="reset-link primary">Nouvelle Partie</a>
	</div>
<?php else: ?>
	<div class="mb-10">
		<a href="/?new=1" class="reset-link">Recommencer la partie</a>
	</div>
<?php endif; ?>
<?php if ($isWaitingForClear): ?>
	<p class="error">Pas de correspondance ! Cliquez sur n'importe quelle carte pour continuer.</p>
<?php endif; ?>
<div class="game-board" style="--cols: <?= $numCols ?>;">
	<?php foreach ($board->getCards() as $card):
		$cardId = $card->getId();
		$cardContent = '&#9733;';
		$cardClasses = ['card'];
		$styleAttr = '';

		if ($card->isMatched()) {
			$cardClasses[] = 'matched';
			$cardContent = htmlspecialchars($card->getValue());
		}
		elseif ($card->isFlipped()) {
			$cardClasses[] = 'flipped';
			$cardContent = htmlspecialchars($card->getValue());
		}

		$isClickable = !$board->isGameOver() && (!$card->isFlipped() || $isWaitingForClear);

		if ($isClickable) {
			$cardClasses[] = 'clickable';
		}

		$actionIsClear = $isWaitingForClear; ?>

		<div class="<?= implode(' ', $cardClasses) ?>">
			<?php if ($isClickable): ?>
				<form method="POST" action="/" class="card-form">
					<?php if ($actionIsClear): ?>
						<input type="hidden" name="clear_action" value="1">
					<?php else: ?>
						<input type="hidden" name="card_id" value="<?= $cardId ?>">
					<?php endif; ?>
					<?php $buttonContent = $card->isFlipped() ? htmlspecialchars($card->getValue()) : '&#9733;'; ?>
					<button type="submit"><?= $buttonContent ?></button>
				</form>
			<?php else: ?>
				<span><?= $cardContent ?></span>
			<?php endif; ?>
		</div>
	<?php endforeach; ?>
</div>