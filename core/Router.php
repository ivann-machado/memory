<?php
namespace Core;

class Router {
	protected array $routes = [];

	public function get(string $uri, $action) {
		$this->routes['GET'][$uri] = $action;
	}

	public function post(string $uri, $action) {
		$this->routes['POST'][$uri] = $action;
	}

	public function dispatch() {
		$uri = $this->getUri();
		$method = $this->getMethod();

		if (isset($this->routes[$method]) && array_key_exists($uri, $this->routes[$method])) {
			$action = $this->routes[$method][$uri];

			if (is_callable($action)) {
				return call_user_func($action);
			}
			return $this->callControllerAction($action);
		}

		http_response_code(404);
		echo "<h1>404 Not Found</h1>";
		exit();
	}

	protected function getUri(): string {
		$uri = trim(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH), '/');
		return $uri === '' ? '/' : '/' . $uri;
	}

	protected function getMethod(): string {
		return $_SERVER['REQUEST_METHOD'];
	}

	protected function callControllerAction(string $action) {
		[$controllerName, $method] = explode('@', $action);

		$controllerClass = 'Controller\\' . $controllerName;

		if (!class_exists($controllerClass)) {
			throw new \Exception("Controller {$controllerClass} not found.");
		}
		$controller = new $controllerClass();

		if (!method_exists($controller, $method)) {
			throw new \Exception("Method {$method} not found in controller {$controllerClass}.");
		}
		return $controller->$method();
	}
}