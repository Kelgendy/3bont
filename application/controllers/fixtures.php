<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Fixtures extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('fixtures_model');
	}

	// public function index()
	// {
	// 	$html_contents = file_get_html('http://www.sofascore.com/standings/football/england/premier-league/1')->find('div#tab-tournament-page-fixtures div', 0)->innertext;
	// 	print_r($html_contents);die();

	// 	foreach ($dom as $key) {
	// 		$date = trim($key->find('.status .cell__content', 0)->plaintext);
	// 		$time = trim($key->find('.status .cell__content', 1)->plaintext);

	// 		$home_team = trim($key->find('.cell__section--main .event_team', 0)->plaintext);
	// 		$away_team = trim($key->find('.cell__section--main .event_team', 1)->plaintext);

	// 		$home_score = trim($key->find('.event-rounds__final-score .cell__content', 0)->plaintext);
	// 		$away_score = trim($key->find('.event-rounds__final-score .cell__content', 1)->plaintext);

	// 		$fixture = array('date', $date, 'time' => $time, 'home_team' => $home_team, 'away_team' => $away_team, 'home_score' => $home_score, 'away_score' => $away_score);
	// 		print_r($fixture);
	// 	}
	// 	unset($dom);
	// 	$this->clear_memory->clean_all($GLOBALS);
	// }

	public function index()
	{
		$leagues = array('england' => 'england/premier-league', 'spain' => 'spain/primera-division', 'germany' => 'germany/bundesliga', 'italy' => 'italy/italy/serie-a', 'egypt' => 'egypt/premier-league');

		foreach ($leagues as $league => $link) {
			$last_date = $this->fixtures_model->get_lastdate($league);
			$html_contents = file_get_html('http://www.livescores.com/soccer/' . $link . '/results/all')->find('div.content div');

			$date = '';
			foreach ($html_contents as $row) {
				if(strpos($row->attr["class"], "row-tall") !== false) { // date row
					$date = trim($row->plaintext);
					$date = date('Y-m-d', strtotime($date));
					if(strtotime($last_date->date) >= strtotime($date)) {
						unset($html_contents);
						break;
					}
				} elseif (strpos($row->attr["class"], "row-gray") !== false) { // match row
					$home_team = trim($row->find('div.name', 0)->plaintext);
					$away_team = trim($row->find('div.name', 1)->plaintext);
					$score = trim($row->find('div.sco', 0)->plaintext);

					$match = array('home_team' => $home_team, 'score' => $score, 'away_team' => $away_team, 'date' => $date, 'league' => $league);
					$this->fixtures_model->insert_match($match);
				}
			}
			unset($html_contents);
		}
	}

}

/* End of file fixtures.php */
/* Location: ./application/controllers/fixtures.php */