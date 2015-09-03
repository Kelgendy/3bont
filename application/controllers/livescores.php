<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Livescores extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('dom');
	}

	public function index()
	{
		$supported_countries = array('England', 'Spain', 'Italy', 'Germany', 'Egypt');
		$supported_leagues = array('Premier League', 'Liga BBVA', 'Serie A', 'Bundesliga');

		$html_contents = file_get_html('http://www.sofascore.com/football/livescore')->find('.js-event-list-table-wrapper .js-event-list-tournament');

		$livescores = array();
		foreach ($html_contents as $key) {
			$league = $key->find('.cell--event-list .cell__section--main .cell__content');
			$country = trim($league[0]->plaintext);
			$league = trim($league[1]->plaintext);

			if(!in_array($country, $supported_countries) || !in_array($league, $supported_leagues)) {
				continue;
			}

			$matches = $key->find('.js-event-list-tournament-events .list-event');
			foreach ($matches as $match) {
				$time = trim($match->find('.event-live', 0)->plaintext);

				$teams = $match->find('.cell__section--main div');
				$home_team = trim($teams[0]->plaintext);
				$away_team = trim($teams[1]->plaintext);

				$scores = $match->find('.event-rounds__final-score div');
				$home_score = trim($scores[0]->plaintext);
				$away_score = trim($scores[1]->plaintext);

				$match_link = $match->href;
				// $match_details = file_get_html('http://www.sofascore.com' . $match_link);
				// $lineup_img = 'http://www.sofascore.com' . $match_details->find('.js-event-page-lineups-container .container-fluid div', 0)->find('img', 0)->src;
				$live_match = array('time' => $time, 'home_team' => $home_team, 'away_team' => $away_team, 'home_score' => $home_score, 'away_score' => $away_score, 'link' => $match_link);
				$livescores[$country][$league][] = $live_match;
				// unset($match_details);
			}
			unset($matches);
		}
		unset($html_contents);
		echo json_encode($livescores);
	}

	public function match_details()
	{
		$link = $_POST['link'];

		$match = array();
		$match_details = file_get_html('http://www.sofascore.com' . $link);

		$header_details = $match_details->find('.header__score-cell .cell__section');
		$home_img = 'http://www.sofascore.com' . $header_details[0]->find('.cell__content .js-link img', 0)->src;
		$away_img = 'http://www.sofascore.com' . $header_details[2]->find('.cell__content .js-link img', 0)->src;

		$date_time = $header_details[3]->find('.header__timestamp');
		$start_time = trim($date_time[0]->plaintext);
		$start_date = trim($date_time[1]->plaintext);

		unset($header_details);
		$match_status = array('home_img' => $home_img, 'away_img' => $away_img, 'start_time' => $start_time, 'start_date' => $start_date);
		$match['match_status'] = $match_status;

		$incidents = $match_details->find('.js-event-page-incidents-container .incidents .cell--incident');

		$incidents_array = array();
		for ($i=0; $i < count($incidents); $i++) {
			if(strpos($incidents[$i]->attr["class"], "cell--center") !== false){
				$text = trim($incidents[$i]->plaintext);
				$incidents_array[] = array('type' => 'center', 'text' => $text);
			} else {
				if(strpos($incidents[$i]->attr["class"], "cell--right") !== false) {
					$team = 'away_team';
				} else {
					$team = 'home_team';
				}
				$type_class = $incidents[$i]->find('.incident__icon span', 0)->attr["class"];
				$incident_type = substr($type_class, strpos($type_class, '-') + 1);
				$incident_time = trim($incidents[$i]->find('.incident__time', 0)->plaintext);
				$incident_main = $incidents[$i]->find('.cell__section--main', 0);

				if($incident_type == 'substitutionin') {
					$player_in = 	$incident_main->find('.incident__player', 0)->plaintext;
					$player_out = $incident_main->find('.incident__dim .incident__player', 0)->plaintext;
					$player_out = trim(substr($player_out, strpos($player_out, ':') + 1));
					$incidents_array[] = array('team' => $team, 'type' => 'substitution', 'time' => $incident_time, 'player_in' => $player_in, 'player_out' => $player_out);
				} elseif ($incident_type == 'regulargoal') {
					$goal = trim($incident_main->find('.incident__goal', 0)->plaintext);
					$goal_scorer = $incident_main->find('.incident__scorer .incident__player', 0)->plaintext;
					$goal_assist = '';
					if($incident_main->find('.incident__dim')) {
						$goal_assist = $incident_main->find('.incident__dim .incident__player', 0)->plaintext;
						$goal_assist = trim(substr($goal_assist, strpos($goal_assist, ':') + 1));
					}
					$incidents_array[] = array('team' => $team, 'type' => 'goal', 'time' => $incident_time, 'goal' => $goal, 'goal_scorer' => $goal_scorer, 'goal_assist' => $goal_assist);
				} else {
					$incident_player = $incident_main->find('.incident__player', 0)->plaintext;
					$incidents_array[] = array('team' => $team, 'type' => $incident_type, 'time' => $incident_time, 'player' => $incident_player);
				}
			}
		}
		unset($match_details);
		$match['incidents'] = $incidents_array;
		echo json_encode($match);
	}

	public function livesocres()
	{
		
		/*		livescores.com 			*/
		// $html_contents = file_get_html('http://www.livescores.com/soccer/live/')->find('.content .league-table');

		// foreach ($html_contents as $key) {
		// 	$country = $key->find('.league a strong', 0)->innertext;
		// 	$league = $key->find('.league span a', 0)->innertext;

		// 	print_r($country . ' - ' . $league . '<br/>');
		// 	$count = count($key->find('tr'));
		// 	for ($i=1; $i < $count; $i++) {
		// 		$match = $key->find('tr', $i);

		// 		$time = $match->find('.fd');
		// 		$time = strip_tags($time[0]->innertext);

		// 		$home_team = $match->find('.fh');
		// 		$home_team = $home_team[0]->innertext;

		// 		$score = $match->find('.fs');
		// 		$score = strip_tags($score[0]->innertext);

		// 		$away_team = $match->find('.fa');
		// 		$away_team = $away_team[0]->innertext;

		// 		$match_link = $match->find('.fs a');
		// 		$match_link = 'http://www.livescores.com' . $match_link[0]->href;
		// 		$match_details = file_get_html($match_link)->find();

		// 		print_r($time . $home_team . $score . $away_team); echo "<br/>";
		// 	}
		// }
		// unset($html_contents);
	}
}

/* End of file livescores.php */
/* Location: ./application/controllers/livescores.php */