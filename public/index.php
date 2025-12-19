<?php
require __DIR__ . '/../vendor/autoload.php';
use Core\Router;

session_start();

$router = new Router();

// --- GAME ROUTES ---
$router->get('/', 'GameController@index');
$router->get('/reset', 'GameController@resetGame');
$router->post('/', 'GameController@handleAction');

// --- RANKING ROUTES ---
$router->get('/ranking', 'RankingController@index');

// --- USER AUTH ROUTES ---
$router->get('/login', 'UserController@login');
$router->post('/login', 'UserController@handleLogin');
$router->get('/signup', 'UserController@signup');
$router->post('/signup', 'UserController@handleSignup');
$router->get('/logoff', 'UserController@logoff');

// --- USER PROFILE ROUTE ---
$router->get('/profile', 'ProfileController@index');


try {
	$router->dispatch();
} catch (\Exception $e) {
	http_response_code(500);

	// echo "<h1>Error</h1>";
	// echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
}