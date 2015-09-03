<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Latest_news extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		$this->load->model('latest_news_model');
		header('Content-Type: text/html; charset=utf-8');
		header('Access-Control-Allow-Origin: *');
	}

	public function index()
	{
		$this->get_news();
	}

	function array_sort($array, $on, $order=SORT_ASC){

		$new_array = array();
		$sortable_array = array();

		if (count($array) > 0) {
			foreach ($array as $k => $v) {
				if (is_array($v)) {
					foreach ($v as $k2 => $v2) {
						if ($k2 == $on) {
							$sortable_array[$k] = $v2;
						}
					}
				} else {
					$sortable_array[$k] = $v;
				}
			}

			switch ($order) {
				case SORT_ASC:
				asort($sortable_array);
				break;
				case SORT_DESC:
				arsort($sortable_array);
				break;
			}

			foreach ($sortable_array as $k => $v) {
				$new_array[$k] = $array[$k];
			}
		}

		return $new_array;
	}


	public function get_news()
	{
		// $_POST['offset'] = 0;
		$offset = $_POST['offset'];
		if(isset($_POST['date']))
			$date = $_POST['date'];
		else
			$date = '2015'; // default date get news in year 2015

		$sources = array(1 => 'hihi2', 2 => 'filgoal', 3 => 'goal', 4 => 'fifa', 7 => 'cairokora');
		$src_offset = $offset / count($sources);
		$limit = 20 / count($sources);

		$news = array();
		foreach ($sources as $key => $value) {
			$news = array_merge($news, $this->latest_news_model->get_news($date, $limit, $src_offset, $key));
		}
		$news = $this->array_sort($news, 'date', SORT_DESC);
		echo json_encode($news);
		// echo "<pre>";
		// print_r($news);
	}

	public function get_news_item()
	{
		$id = $_POST['id'];
		$news_item = $this->latest_news_model->get_news_item($id);
		echo json_encode($news_item);
	}

	public function update_news()
	{
		//update news from all sources
		$this->_hihi2news();
		$this->_filgoalnews();
		$this->_goalnews();
		$this->_fifanews();
		$this->_cairokoranews();
		echo "News Updated.";
	}

	function _hihi2news()
	{
		$source_id = 1;
  	$lastentry = $this->latest_news_model->get_lastentry($source_id);  // get last news date

  	for ($i=1; $i <= 2; $i++) {
  		$html_content = file_get_html("http://hihi2.com/category/football-news/page/$i")->find('#content-loop .post');

  		foreach ($html_content as $key) {
  			$link = $key->find('a', 0)->href;
  			$img = $key->find('a img', 0)->src;
  			$img = str_replace("-150x100", "", $img);
  			$title = $key -> find('.entry-title a', 0)->title;
  			$text = trim($key->find('.entry-excerpt', 0)->plaintext);
  			$date = $key->find('.entry-date', 0)->plaintext;

  			$exploded = explode(" ", $date);
  			$exploded_date = explode("/", $exploded[1]);
  			$exploded_time = explode(":", $exploded[3]);

  			$year = $exploded_date[0];
  			$month = $exploded_date[1];
  			$day = $exploded_date[2];

  			$hour = $exploded_time[0];
  			$min = $exploded_time[1];

  			if($exploded[4] == "م" && $hour != 12)
	      	$hour = $hour + 12 ; // add 12 hours for evening hours (13, 14, 15);

	      else if($exploded[4] == "ص") {
	      	if($hour == 12)
	      		$hour = "00";  // translate 12 ص to 00 hour
	      	else if($hour < 10)
	      		$hour = "0" . $hour;  // add 0 before hour to make it comparable (09, 08, 07);
	      }

	      $formatted_date = "$year-$month-$day $hour:$min";  // mysql formatted datetime

	      if(strtotime($lastentry['date']) >= strtotime($formatted_date)) {
	      	unset($html_content);
	    		$this->clear_memory->clean_all($GLOBALS);
	      	break 2;
	      }

	      $this->_insert_news($title, $text, $link, $img, $formatted_date, $source_id);
	    }
	    unset($html_content);
	    $this->clear_memory->clean_all($GLOBALS);
	  }
	}

	function _filgoalnews()
	{
		$source_id = 2;
		$lastentry = $this->latest_news_model->get_lastentry($source_id);

		for ($i=1; $i <= 1; $i++) { // get news from multiple pages ( not working for filgoal )
			$html_content = file_get_html("http://www.filgoal.com/arabic/AllNews.aspx?CatID=1#$i")->find('#AllNewsDV .AllNews ul li');

			foreach ($html_content as $key) {
				$link = "http://www.filgoal.com/arabic/" . $key->find('a', 0)->href;
				$img_id = $key->find('a img', 0)->src;
				$img = "http://betaimages.filgoal.com/images//NewsPics/XLarge" . substr(strstr($img_id, '&', true), strrpos($img_id, '/'));  // double substr to get only image id
				$title = trim($key->find('a .ANT', 0)->plaintext);
				$text = trim($key->find('a .ANB', 0)->plaintext);
				$date = trim($key->find('a .ANTInfo span', 0)->innertext);

				$exploded = explode(" ", $date);
				$exploded_time = explode(":", $exploded[7]);

				$year = $exploded[5];
				$month = $this->_get_month($exploded[3]);
				$day = $exploded[2];

				$hour = $exploded_time[0];
				$min = $exploded_time[1];

				$formatted_date = "$year-$month-$day $hour:$min";

				if(strtotime($lastentry['date']) >= strtotime($formatted_date)) {
					unset($html_content);
					$this->clear_memory->clean_all($GLOBALS);
					break 2;
				}

				$this->_insert_news($title, $text, $link, $img, $formatted_date, $source_id);
			}
			unset($html_content);
			$this->clear_memory->clean_all($GLOBALS);
		}
	}

	function _goalnews()
	{
		$source_id = 3;
		$lastentry = $this->latest_news_model->get_lastentry($source_id);

		for ($i=1; $i <= 2; $i++) { // get news from each page
			$day_content = file_get_html("http://www.goal.com/ar/news/archive/$i")->find('#news-archive .day-news');

			foreach ($day_content as $day) {
				$date = $day->find('.date', 0)->innertext;
				$exploded = explode(" ", $date);

				$year = $exploded[3];
				$ar_month = preg_replace('/[^أ-ي ]/ui', '', $exploded[2]);
				$month = $this->_get_month($ar_month);
				$date_day = $exploded[1];

				$html_content = $day->find('ul li');
				foreach ($html_content as $key) {
					$link = "http://www.goal.com" . $key->find('.imgBox a', 0)->href;
					$img = $key->find('.imgBox a img', 0)->src;
					$img = str_ireplace("thumb", "heroa", $img);
					$title = trim($key->find('.articleInfo a', 0)->plaintext);
					$text = trim($key->find('.articleSummary', 0)->plaintext);
					$time = $key->find('.articleInfo .time', 0)->innertext;

					$exploded_time = explode(" ", strtr($time, ":", " "));
					$hour = $exploded_time[0];
					$min = $exploded_time[1];

					if($exploded[2] == "م" && $hour != 12)
	      		$hour = $hour + 12 ; // add 12 hours for evening hours (13, 14, 15);

	      	else if($exploded[2] == "ص") {
	      		if($hour == 12)
		      		$hour = "00";  // translate 12 ص to 00 hour
		      	else if($hour < 10)
		      		$hour = "0" . $hour;  // add 0 before hour to make it comparable (09, 08, 07);
		      }

		      $formatted_date = "$year-$month-$date_day $hour:$min";

		      if(strtotime($lastentry['date']) >= strtotime($formatted_date)) {
		      	unset($html_content);
		      	unset($day_content);
		      	$this->clear_memory->clean_all($GLOBALS);
		      	break 3;
		      }

		      $this->_insert_news($title, $text, $link, $img, $formatted_date, $source_id);
		    }
		    unset($html_content);
		  }
		  unset($day_content);
		  $this->clear_memory->clean_all($GLOBALS);
		}
	}

	function _fifanews()
	{
		$source_id = 4;
		// date_default_timezone_set("Africa/Cairo");
		$now = date('H:i:s');
		$last_entries = $this->latest_news_model->get_last_entries($source_id); // get last news title

		$last_entries_array = array();
		foreach ($last_entries as $key => $value) {
			$last_entries_array[] = $value->title;
		}

		for ($i=1; $i <= 2; $i++) {
			$html_content = file_get_html("http://ar.fifa.com/newscentre/news/index,page=$i.htmx")->find('.nl-list .nl-listItem');

			foreach ($html_content as $key) {
				$link = "http://ar.fifa.com" . $key->find('.nt-newsLink', 0)->href;
				$img = $key->find('.nt-picLink img', 0)->src;
				$img = str_ireplace("small", "full", $img);
				$title = trim($key->find('.nt-newsLink', 0)->plaintext);
				$text = trim($key->find('.nt-text a', 0)->plaintext);
				$date = $key->find('.nt-date', 0)->innertext;

				$exploded = explode(" ", $date);

				$year = $exploded[3];
				$month = $this->_get_month($exploded[2]);
				$day = $exploded[1];

				$formatted_date = "$year-$month-$day " . $now;

				if(in_array($title, $last_entries_array)) {
					unset($html_content);
					$this->clear_memory->clean_all($GLOBALS);
					break 2;
				}

				$this->_insert_news($title, $text, $link, $img, $formatted_date, $source_id);
			}
			unset($html_content);
			$this->clear_memory->clean_all($GLOBALS);
		}
	}

	public function _cairokoranews()
	{
		$src_id = 7;
		$sources_array = array('world' => 'http://www.cairokora.com/category/%D8%AD%D9%88%D9%84-%D8%A7%D9%84%D8%B9%D8%A7%D9%84%D9%85', 'egy' => 'http://www.cairokora.com/category/%D9%83%D8%B1%D8%A9-%D9%85%D8%B5%D8%B1%D9%8A%D8%A9', 'talk' => 'http://www.cairokora.com/category/%D8%AA%D9%88%D9%83-%D8%B4%D9%88');
		foreach ($sources_array as $type => $url) {
			$lastentry = $this->latest_news_model->get_lastentry_byType($src_id, $type);
			for ($i=1; $i <=1; $i++) {
				$html_content = file_get_html($url . '/page/' . $i);

				$slider = $html_content->find('.slider .slider_bottom .section');
				foreach ($slider as $section) {
					$link = $section->find('.to_show a', 0)->href;
					$img = $section->find('.to_show a img', 0)->src;
					$title = $section->find('.to_show a', 1)->plaintext;
					$text = $section->find('.data_con .desc', 0)->plaintext;

					$inner_content = file_get_html($link)->find('.article_cont', 0);
					$date = trim($inner_content->find('.article_info .writer', 0)->plaintext);

					$exploded = explode(" ", $date);

					$year = $exploded[2];
					$month = $this->_get_month($exploded[1]);
					$day = explode(',' ,$exploded[0]);
					$day = $day[1];

					$exploded_time = explode(':', $exploded[4]);
					$hour = $exploded_time[0];
					$min = $exploded_time[1];

					if($exploded[5] == "م" && $hour != 12) {
	      		$hour = $hour + 12 ; // add 12 hours for evening hours (13, 14, 15);
	      	}

	      	else if($exploded[5] == "ص") {
	      		if($hour == 12) {
		      		$hour = "00";  // translate 12 ص to 00 hour
		      	}
		      	else if($hour < 10) {
		      		$hour = "0" . $hour;  // add 0 before hour to make it comparable (09, 08, 07);
		      	}
		      }

		      $formatted_date = "$year-$month-$day $hour:$min";
		      if(strtotime($lastentry['date']) >= strtotime($formatted_date)) {
		      	unset($html_content);
		      	unset($inner_content);
		      	$this->clear_memory->clean_all($GLOBALS);
		      	break 2;
		      }

		      unset($inner_content);
		      $this->clear_memory->clean_all($GLOBALS);

		      // $news_array = array('link' => $link, 'img' => $img, 'title' => $title, 'text' => $text, 'date' => $formatted_date, 'type' => $type);
		      $this->_insert_news($title, $text, $link, $img, $formatted_date, $src_id, $type);
		    }
		    unset($slider);

		    $right_section = $html_content->find('.section_1 .right_sec .egypt .thum');
		    foreach ($right_section as $item) {
		    	$link = $item->find('a', 0)->href;
		    	$img = $item->find('a img', 0)->src;
		    	$title = trim($item->find('div.cat_indata a', 0)->plaintext);
		    	$text = '';
		    	$date = trim($item->find('div.cat_indata p.cat_indate', 0)->plaintext);

		    	$exploded = explode(" ", $date);

		    	$year = $exploded[3];
		    	$month = $this->_get_month($exploded[2]);
		    	$day = $exploded[1];
		    	$time = $exploded[5];

		    	$formatted_date = "$year-$month-$day $time";

		    	// $news_array = array('link' => $link, 'img' => $img, 'title' => $title, 'date' => $formatted_date, 'type' => $type);
		    	$this->_insert_news($title, $text, $link, $img, $formatted_date, $src_id, $type);
					// print_r($news_array);
		    }
		    unset($right_section);
				unset($html_content);
				$this->clear_memory->clean_all($GLOBALS);
		  }
		}
	}

	function _insert_news($title, $text, $link, $img, $date, $src_id, $type='')
	{
		$news_array = array('title' => $title, 'text' => $text, 'link' => $link, 'img' => $img, 'date' => $date, 'type' => $type, 'src_id' => $src_id);
		$this->latest_news_model->insert_news($news_array);
	}

	function _get_month($ar_month)
	{
		switch ($ar_month) {
			case 'يناير': 			$month = "01"; break;
			case 'فبراير': 		$month = "02"; break;
			case 'مارس': 				$month = "03"; break;
			case 'أبريل': 			$month = "04"; break;
			case 'مايو': 				$month = "05"; break;
			case 'يونيو': 			$month = "06"; break;
			case 'يوليو': 			$month = "07"; break;
			case 'أغسطس': 			$month = "08"; break;
			case 'سبتمبر':			$month = "09"; break;
			case 'أكتوبر': 		$month = "10"; break;
			case 'نوفمبر': 		$month = "11"; break;
			case 'ديسمبر': 		$month = "12"; break;
			default: 						$month = "00"; break;
		}
		return $month;
	}

}

/* End of file latest_news.php */
/* Location: ./application/controllers/latest_news.php */