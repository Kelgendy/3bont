<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Videos extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		$this->load->model('videos_model');
		header('Content-Type: text/html; charset=utf-8');
		header('Access-Control-Allow-Origin: *');
		// define('MAX_FILE_SIZE', 600000);
	}

	public function index()
	{
		$this->get_videos();
	}

	public function update_videos()
	{
		$this->korabia_vid();
		// $this->youtube_vid();
		echo "Videos Updated.";
	}

	public function get_videos()
	{
		// $_POST['offset'] = 0;
		$offset = $_POST['offset'];
		$videos = $this->videos_model->get_vids($offset);
		echo json_encode($videos);
		// echo "<pre>";
		// print_r($videos);
	}

// 				not working properly 				//
	public function get_videos_type()
	{
		// $_POST['offset'] = 0;
		// $_POST['type'] = "euro";
		$offset = $_POST['offset'];
		$type = $_POST['type']; // "euro", "egy", "talk"
		$videos = $this->videos_model->get_type($type, $offset);
		echo json_encode($videos);
		// echo "<pre>";
		// print_r($videos);
	}

	public function korabia_vid()
	{
		$source_id = 5;
		$lastentry = $this->videos_model->get_lastentry($source_id);
		$lastentry = $lastentry['lastentry'];
		$source_links = array("euro" =>"http://tv.korabia.com/sections/16-%D8%A7%D9%84%D9%83%D8%B1%D8%A9_%D8%A7%D9%84%D8%A3%D9%88%D8%B1%D9%88%D8%A8%D9%8A%D8%A9", "egy" => "http://tv.korabia.com/sections/14-%D8%A7%D9%84%D9%83%D8%B1%D8%A9_%D8%A7%D9%84%D9%85%D8%B5%D8%B1%D9%8A%D8%A9", "talk" => "http://tv.korabia.com/sections/1-%D8%A8%D8%B1%D8%A7%D9%85%D8%AC_%D8%A7%D9%84%D8%AA%D9%88%D9%83_%D8%B4%D9%88_%D8%A7%D9%84%D8%B1%D9%8A%D8%A7%D8%B6%D9%8A");

		foreach ($source_links as $type => $link) {
			$html_content = file_get_html($link)->find('#articles .nd_box');

			foreach ($html_content as $key) {
				$link = "http://tv.korabia.com/" . $key->find('a', 0)->href;
				if($link <= $lastentry) {
					break;
				}
				$img = "http://tv.korabia.com/" . $key->find('a img', 0)->src;
				$img = str_replace("289x207", "500x330", $img);
				$title = $key->find('a img', 0)->alt;
				$article_content = file_get_html($link);
				$vid_content = $article_content->find('.art_video', 0);
				$embed_link = $vid_content->find('iframe');
				if(!$embed_link) { // youtube video
					$embed_link = $vid_content->find('embed');
				}
				$embed_link = $embed_link[0]->src;

				$date = $article_content->find('.article_details .art_date', 0)->plaintext;
				$exploded = explode(" ", $date);
				$year = $exploded[3];
				$month = $this->_get_month($exploded[2]);
				$day = $exploded[1];
				$time = $exploded[5];
				$formatted_date = "$year-$month-$day $time";

				/*  to remove extra arguments from videos  */
				// if(strpos('?', $embed_link)!==false)
				// 	$embed_link = strstr($embed_link, '?', true);
				$embed_link = "http:" . $embed_link;
				unset($article_content);
				$vid_array = array('title' => $title, 'link' => $link, 'embed_link' => $embed_link, 'img' => $img, 'src_id' => $source_id, 'date' => $formatted_date, 'type' => $type);
				$this->videos_model->insert_videos($vid_array);
			}
			unset($html_content);
		}
	}

	public function youtube_vid()
	{
		$source_links = array('6' => "channel/UCZQkEreAn33cSdXlCyPJ1Iw", '8' => 'user/Yallakorafans', '9' => 'channel/UCxutPjxelujYXOdnfjeFU7Q');

		foreach ($source_links as $src_id => $link) {
			$last_entry = $this->videos_model->get_lastdate($src_id);
			$html_content = file_get_html("http://www.youtube.com/" . $link . "/videos?flow=grid&sort=dd&view=0")->find('ul#channels-browse-content-grid li.channels-content-item');

			foreach ($html_content as $key) {
				$img = $key->find('.video-thumb img', 0)->src;
				$img = 'http:' . str_replace("mqdefault", "sddefault", $img);
				$title = $key->find('.yt-lockup-title a', 0)->plaintext;
				$full_link = $key->find('.yt-lockup-thumbnail a', 0)->href;
				$link = "http://www.youtube.com" . $key->find('.yt-lockup-thumbnail a', 0)->href;
				$video_link = substr($full_link, strpos($full_link, '=')+1);
				$embed_link = 'http://www.youtube.com/embed/' . $video_link;

				$url = "http://gdata.youtube.com/feeds/api/videos/".$video_link."?v=2&alt=json";
				$json = file_get_contents($url);
				$json = str_replace('$', '_', $json);
				$obj = json_decode($json);
				$video_date = date('Y-m-d H:i:s', strtotime($obj->entry->published->_t));
				unset($json);

				if(strtotime($last_entry->date) >= strtotime($video_date)) {
					break;
				}

				$vid_array = array('title' => $title, 'link' => $link, 'embed_link' => $embed_link, 'img' => $img, 'src_id' => $src_id, 'date' => $video_date);
				$this->videos_model->insert_videos($vid_array);
			}
			unset($html_content);
		}
	}

	// 				not working !! 				//
	public function cairokora_vid()
	{
		$source_id = 6;

		$html_content = file_get_html("http://cairokora.com/?cat=8")->find('.section_2 .thum');

		foreach ($html_content as $key) {
			$link = $key->find('a');
			$link = $link[0]->href;

			$img = $key->find('a img');
			$img = $img[0]->src;

			$title = $key->find('.desc a');
			$title = $title[0]->innertext;

			$vid_content = file_get_html($link)->find('iframe');
			if(!$link || !$vid_content)
				break;
			$embed_link = "http:" . $vid_content[0]->src;
			unset($vid_content);

			$vid_array = array('title' => $title, 'link' => $link, 'embed_link' => $embed_link, 'img' => $img, 'src_id' =>$source_id);
			print_r($vid_array); echo "<br/>";
		}

		// $html_content = file_get_html("http://cairokora.com/?cat=8")->find('.videos_con .thum');
		// foreach ($html_content as $key) {
		// 	$link = $key->find('a');
		// 	$link = $link[0]->href;
		// 	print_r($link); echo "<br/>";
		// }
		unset($html_content);
	}

	function _get_month($ar_month)
	{
		switch ($ar_month) {
			case 'يناير': 			$month = "01"; break;
			case 'فبراير': 		$month = "02"; break;
			case 'مارس': 			$month = "03"; break;
			case 'أبريل': 			$month = "04"; break;
			case 'مايو': 				$month = "05"; break;
			case 'يونيو': 			$month = "06"; break;
			case 'يوليو': 			$month = "07"; break;
			case 'أغسطس': 		$month = "08"; break;
			case 'سبتمبر':		$month = "09"; break;
			case 'أكتوبر': 		$month = "10"; break;
			case 'نوفمبر': 		$month = "11"; break;
			case 'ديسمبر': 		$month = "12"; break;
			default: 					$month = "00"; break;
		}
		return $month;
	}
}

/* End of file videos.php */
/* Location: ./application/controllers/videos.php */