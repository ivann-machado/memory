<h2>Classements Mondiaux</h2>
<p class="info-box">
	Le score est calculé avec la formule :
	<strong>Score = <span class="equation-fraction">
		<span class="equation-numerator">1</span>
		<span class="equation-denominator">Mouvements &times; Temps (s)</span>
	</span> &times; 1 000 000</strong>.
	Plus le score est élevé, meilleur est le classement.
</p>

<?php
if (empty($allRankings)): ?>
	<p>Aucun score n'a été enregistré pour le moment.</p>
<?php else: ?>
	<?php foreach ($allRankings as $pairsCount => $rankings): ?>
		<div class="ranking-group">
			<h3>Classement pour <?= htmlspecialchars($pairsCount) ?> Paires</h3>
			<table>
				<thead>
					<tr>
						<th style="width: 5%;">Rang</th>
						<th style="width: 30%;">Joueur</th>
						<th class="center">Mouvements (c)</th>
						<th class="center">Temps (t) (s)</th>
						<th class="center score-header">Score</th>
					</tr>
				</thead>
				<tbody>
					<?php $rank = 1; ?>
					<?php foreach ($rankings as $scoreEntry): ?>
						<tr>
							<td><?= $rank++ ?></td>
							<td><?= htmlspecialchars($scoreEntry['username'] ?? 'Anonyme') ?></td>
							<td class="center"><?= htmlspecialchars($scoreEntry['moves_count'] ?? 0) ?></td>
							<td class="center"><?= htmlspecialchars($scoreEntry['time_taken_seconds'] ?? 0) ?></td>
							<td class="score-cell">
								<?= number_format(htmlspecialchars($scoreEntry['calculated_score'] ?? 0), 2) ?>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	<?php endforeach; ?>
<?php endif; ?>
<a href="/" class="reset-link">Retour au Jeu</a>