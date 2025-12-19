<?php
namespace Controller;

use Core\View;
use Memory\App\User;

class UserController {

	public function login() {
		if (User::isLoggedIn()) {
			header('Location: /profile');
			exit();
		}

		$view = new View('user/login');
		$view->render();
	}

	public function handleLogin() {
		if (isset($_POST['login'], $_POST['password'])) {
			try {
				if(User::logIn($_POST['login'], $_POST['password'])) {
					header('Location: /');
					exit();
				}
				else {
					$_SESSION['error_message'] = "Identifiants invalides.";
					header('Location: /login');
					exit();
				}
			} catch (\Exception $e) {
				$_SESSION['error_message'] = "Erreur de connexion: " . $e->getMessage();
				header('Location: /login');
				exit();
			}
		}
	}

	public function signup() {
		if (User::isLoggedIn()) {
			header('Location: /profile');
			exit();
		}

		$view = new View('user/signup');
		$view->render();
	}

	public function handleSignup() {
		if (isset($_POST['login'], $_POST['password'])) {
			$data = [
				'login' => $_POST['login'],
				'password' => $_POST['password'],
			];

			if (User::signUp($data)) {
				$_SESSION['score_message'] = "Inscription réussie! Veuillez vous connecter.";
				header('Location: /login');
				exit();
			} else {
				$_SESSION['error_message'] = "Erreur lors de l'inscription (pseudo déjà pris ?).";
				header('Location: /signup');
				exit();
			}
		}
	}

	public function logoff() {
		User::logOff();
		header('Location: /');
		exit();
	}
}