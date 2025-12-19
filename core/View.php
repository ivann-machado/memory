<?php
namespace Core;

use Exception;

class View {
	private string $templatePath;
	private array $data;
	private string $layout = 'layout/default';

	public function __construct(string $templatePath, array $data = []) {
		$this->templatePath = $this->resolvePath($templatePath);
		$this->data = $data;
	}

	private function resolvePath(string $templateName): string {
		$rootDir = dirname(__DIR__);
		$path = $rootDir . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . $templateName . '.php';

		if (!file_exists($path)) {
			throw new Exception("View template not found: {$path}");
		}
		return $path;
	}

	public function render(): void {
		$viewContent = $this->getTemplateContent($this->templatePath, $this->data);

		$layoutPath = $this->resolvePath($this->layout);
		$this->getTemplateContent($layoutPath, array_merge($this->data, ['viewContent' => $viewContent]), true);
	}

	private function getTemplateContent(string $path, array $data, bool $echo = false): string {
		extract($data);
		ob_start();

		try {
			(function() use ($path, $data) {
				extract($data);
				require $path;
			})();
		} catch (Exception $e) {
			ob_end_clean();
			throw $e;
		}
		$content = ob_get_clean();

		if ($echo) {
			echo $content;
		}
		return $content;
	}
}