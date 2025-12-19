<?php
use Memory\App\User;

$currentViewName = $templateName ?? '';

function isActive(string $expectedViewName, string $currentViewName): string {
	return str_starts_with($currentViewName, $expectedViewName) ? 'active' : '';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Jeu de Mémoire</title>
	<link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
	<h1>Jeu de Mémoire</h1>

	<nav>
		<div class="nav-group">
			<a href="/" class="<?= isActive('game/', $currentViewName) || isActive('start', $currentViewName) ? 'active' : '' ?>">Jouer</a>
			<a href="/ranking" class="<?= isActive('ranking/index', $currentViewName) ?>">Classement</a>
		</div>
		<div class="nav-group">
			<?php if (User::isLoggedIn()): ?>
				<a href="/profile" class="<?= isActive('user/profile', $currentViewName) ?>">Profil</a>
				<span class="username"><?= htmlspecialchars(User::getCurrentUser()->getUsername()) ?></span>
				<a href="/logoff" class="logoff-link">Déconnexion</a>
			<?php else: ?>
				<a href="/login" class="auth-link <?= isActive('user/login', $currentViewName) ?>">Connexion</a>
				<a href="/signup" class="auth-link <?= isActive('user/signup', $currentViewName) ?>">Inscription</a>
			<?php endif; ?>
		</div>
	</nav>

	<main class="container">
		<?php if (isset($_SESSION['error_message'])): ?>
			<div class="error"><?= htmlspecialchars($_SESSION['error_message']) ?></div>
			<?php unset($_SESSION['error_message']); ?>
		<?php endif; ?>
		<?php if (isset($_SESSION['score_message'])): ?>
			<div class="success"><?= htmlspecialchars($_SESSION['score_message']) ?></div>
			<?php unset($_SESSION['score_message']); ?>
		<?php endif; ?>
		<?= $viewContent; ?>
	</main>

	<div class="footer">
		<p>&copy; <?= date('Y') ?> Jeu de Mémoire. All rights reserved.</p>
	</div>
</body>
</html>