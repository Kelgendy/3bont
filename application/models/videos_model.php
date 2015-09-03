<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Videos_model extends CI_Model {

	public function insert_videos($vid)
	{
		$this->db->insert('videos', $vid);
	}

	public function get_lastentry($src_id)
	{
		$query = $this->db->select_max('link', 'lastentry')
											->where('src_id', $src_id)
											->get('videos');
		return $query->row_array();
	}

	public function get_lastdate($src_id)
	{
		$query = $this->db->select_max('date')
											->where('src_id', $src_id)
											->get('videos');
		return $query->row();
	}

	public function get_vids($offset)
	{
		$query = $this->db->select('videos.*, sources.name AS source_name, sources.img AS source_img')
											->from('videos')
											->join('sources', 'videos.src_id = sources.id', 'left outer')
											->order_by('videos.date', 'desc')
											->limit(20, $offset)
											->get();

		// $query = $this->db->query("SELECT videos.*, sources.name AS source_name, sources.img AS source_img FROM videos LEFT OUTER JOIN sources ON videos.src_id=sources.id ORDER BY videos.link DESC LIMIT $offset, 20");
		return $query->result();
	}

	public function get_type($type, $offset)
	{
		$query = $this->db->select('videos.*, sources.name AS source_name')
											->from('videos')
											->join('sources', 'videos.src_id = sources.id', 'left outer')
											->where('type', $type)
											->order_by('videos.link', 'desc')
											->limit(20, $offset)
											->get();
		return $query->result();
	}

}

/* End of file videos_model.php */
/* Location: ./application/models/videos_model.php */