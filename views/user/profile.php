<h2>Mon Profil : <?= htmlspecialchars($user->getUsername() ?? 'Utilisateur Inconnu') ?></h2>

<div style="margin-bottom: 30px; padding: 15px; border: 1px solid #ccc; border-radius: 8px; background-color: #e9ecef; display: flex; flex-direction: column; gap: 10px;">
	<p><strong>Nom d'utilisateur:</strong> <?= htmlspecialchars($user->getUsername() ?? 'Non renseigné') ?></p>
</div>

<h3>Historique des Parties</h3>

<?php if (empty($scores)): ?>
	<p>Vous n'avez pas encore de scores enregistrés. Jouez une partie pour commencer !</p>
<?php else: ?>
	<table>
		<thead>
			<tr>
				<th>#</th>
				<th class="center">Paires</th>
				<th class="center">Mouvements</th>
				<th class="center">Temps (s)</th>
				<th class="center">Score (x10^6 / c*t)</th>
				<th class="center">Date</th>
			</tr>
		</thead>
		<tbody>
			<?php $index = 1; ?>
			<?php foreach ($scores as $scoreEntry): ?>
				<tr>
					<td><?= $index++ ?></td>
					<td class="center"><?= htmlspecialchars($scoreEntry['pairs_count'] ?? 0) ?></td>
					<td class="center"><?= htmlspecialchars($scoreEntry['moves'] ?? 0) ?></td>
					<td class="center"><?= htmlspecialchars($scoreEntry['time_taken_seconds'] ?? 0) ?></td>
					<td class="center" style="font-weight: bold; color: var(--primary-color);">
						<?= number_format(htmlspecialchars($scoreEntry['calculated_score'] ?? 0), 2) ?>
					</td>
					<td class="center"><?= htmlspecialchars(substr($scoreEntry['finished_at'] ?? 'N/A', 0, 10)) ?></td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<?php endif; ?>