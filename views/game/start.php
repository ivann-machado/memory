<h2>Nouvelle Partie</h2>
<form method="POST" action="/">
	<label for="pairs">Nombre de paires (<?= Controller\GameController::MIN_PAIRS ?>-<?= Controller\GameController::MAX_PAIRS ?>):</label>
	<input type="number" id="pairs" name="pairs"
	min="<?= Controller\GameController::MIN_PAIRS ?>"
	max="<?= Controller\GameController::MAX_PAIRS ?>"
	value="6" required>
	<button type="submit" name="start_game" class="button primary">Démarrer</button>
</form>