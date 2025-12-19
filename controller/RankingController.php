<?php
namespace Controller;

use Memory\App\Ranking;
use Core\View;

class RankingController {

	public function index() {
		$rankingApp = new Ranking();
		$allRankings = $rankingApp->getTopScores();

		$view = new View('ranking/index', [
			'allRankings' => $allRankings
		]);
		$view->render();
	}
}