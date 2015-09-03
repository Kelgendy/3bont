<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Latest_news_model extends CI_Model {

	public function insert_news($news_array)
	{
		$this->db->insert('news', $news_array);
	}

	public function get_news($date, $limit, $offset, $src_id)
	{
		$query = $this->db->select('news.*, sources.name AS source_name, sources.img AS source_img')
											->from('news')
											->join('sources', 'news.src_id = sources.id', 'left outer')
											->where('news.date >', $date)
											->where('src_id', $src_id)
											->order_by('news.date', 'desc')
											->limit($limit, $offset)
											->get();
		return $query->result_array();
	}

	public function get_news_item($id)
	{
		$query = $this->db->select('news.*, sources.name AS source_name, sources.img AS source_img')
											->join('sources', 'news.src_id = sources.id', 'left outer')
											->where('news.id', $id)
											->get('news');
		return $query->result();
	}

	public function get_lastentry($src_id)
	{
		$query = $this->db->select_max('date')
											->where('src_id', $src_id)
											->get('news');

		return $query->row_array();
	}

	public function get_lastentry_byType($src_id, $type)
	{
		$query = $this->db->select_max('date')
											->where('src_id', $src_id)
											->where('type', $type)
											->get('news');
		return $query->row_array();
	}

	public function get_last_entries($src_id)
	{
		$max_date = $this->db->select_max('date')->where('src_id', $src_id)->get('news')->row();
		$query = $this->db->select('title')
											->where('src_id', $src_id)
											->where('date', $max_date->date)
											->get('news');
		return $query->result();
	}
}

/* End of file latest_news_model.php */
/* Location: ./application/models/latest_news_model.php */