<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Fixtures_model extends CI_Model {

	public function insert_match($match)
	{
		$this->db->insert('matches', $match);
	}

	public function get_lastdate($league)
	{
		$query = $this->db->select_max('date')
											->where('league', $league)
											->get('matches');
		return $query->row();
	}

}

/* End of file fixtures_model.php */
/* Location: ./application/models/fixtures_model.php */