<?php
namespace Controller;

use Core\View;
use Memory\App\User;
use Memory\App\Ranking;

class ProfileController {

	public function index() {
		if (!User::isLoggedIn()) {
			$_SESSION['error_message'] = "Vous devez être connecté pour accéder à votre profil.";
			header('Location: ./login');
			exit();
		}

		$user = User::getCurrentUser();
		$ranking = new Ranking();
		$scores = $ranking->getUserScoreHistory($user->getId());

		$view = new View('user/profile', [
			'user' => $user,
			'scores' => $scores
		]);
		$view->render();
	}
}