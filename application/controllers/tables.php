<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tables extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('dom');
	}

	public function index()
	{
	}

	public function england()
	{
		$html_contents = file_get_html('http://www.livescores.com/soccer/england/premier-league')->find('.table .row-gray');
		$table = $this->_get_table_data($html_contents);
		unset($html_contents);
		echo json_encode($table);
	}

	public function spain()
	{
		$html_contents = file_get_html('http://www.livescores.com/soccer/spain/primera-division')->find('.table .row-gray');
		$table = $this->_get_table_data($html_contents);
		unset($html_contents);
		echo json_encode($table);
	}

	public function germany()
	{
		$html_contents = file_get_html('http://www.livescores.com/soccer/germany/bundesliga')->find('.table .row-gray');
		$table = $this->_get_table_data($html_contents);
		unset($html_contents);
		echo json_encode($table);
	}

	public function italy()
	{
		$html_contents = file_get_html('http://www.livescores.com/soccer/italy/serie-a')->find('.table .row-gray');
		$table = $this->_get_table_data($html_contents);
		unset($html_contents);
		echo json_encode($table);
	}

	public function egypt()
	{
		$html_contents = file_get_html('http://www.livescores.com/soccer/egypt/premier-league')->find('.table .row-gray');
		$table = $this->_get_table_data($html_contents);
		unset($html_contents);
		echo json_encode($table);
	}

	private function _get_table_data($html_contents)
	{
		$count = count($html_contents);
		$table = array();
		for ($i=1; $i < $count; $i++) {
			$club_data = $html_contents[$i]->find('div');

			$club = $club_data[1]->plaintext;
			$played = $club_data[2]->plaintext;
			$win = $club_data[3]->plaintext;
			$draw = $club_data[4]->plaintext;
			$lose = $club_data[5]->plaintext;
			$for = $club_data[6]->plaintext;
			$against = $club_data[7]->plaintext;
			$diff = $club_data[8]->plaintext;
			$points = $club_data[9]->plaintext;

			$table[] = array('club' => $club,'played' => $played,'win' => $win,'draw' => $draw,'lose' => $lose,'for' => $for,'against' => $against,'diff' => $diff,'points' => $points);
			unset($club_data);
		}
		return $table;
	}
}

/* End of file tables.php */
/* Location: ./application/controllers/tables.php */