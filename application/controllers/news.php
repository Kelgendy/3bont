<?php 

class news extends CI_Controller {
        
        
    function __construct()
    {
        parent::__construct();
        
        $this->load->helper('form');
        $this->load->helper('url'); 
        $this->load->helper('dom');
        $this->load->library('curl'); 
        $this->load->library('rssparser');
    }
    
	public function world_cup()
    {
       	// $page = file_get_html('http://www.korabia.com/Champion/Detailed/72');
        // $page = $page -> find('#team_rank_row_1037 td');
		// $page = $page[0] -> innertext;
		
       $team = 'http://www.yallakora.com/ar/TourNews/343/0/%D9%8A%D8%A7%D9%84%D9%84%D8%A7%20%D9%85%D9%88%D9%86%D8%AF%D9%8A%D8%A7%D9%84';	
       $page = file_get_html($team);
			
		$list = $page->find('.TwoHalfCol li');
	    $thumbs = array();
	    $links = array();
		
        foreach ($list as $index => $key) {
            	
			$thumb = $key -> find('.NewsIMGClip img');
			$thumb = $thumb[0] -> src;
			$thumbs[] = $thumb;
			
			$link = $key -> find('.NewsIMGClip');
			$link = $link[0] -> href;
			
			$link = explode('/ar', $link);
			$link = 'http://www.yallakora.com/ar'.$link[1];
	        $links[] = $link;
        }

        foreach ($links as $index => $key) {
                
            $p = file_get_html($key);
            $title = $p -> find('.HeadTitles');
            $title = $title[0] -> innertext;

             if(strpos($title,'خاص..') !== false)
                {
                    $title = str_replace('خاص..', "", $title);
                }
            else if(strpos($title,'صيد ') !== false)
                {
                    $title = str_replace('صيد ', "", $title);
                }  
            else if(strpos($title,'صحيفة:') !== false)
                {
                    $title = str_replace('صحيفة:', "", $title);
                } 
                               
            else if (strpos($title,'صورة') !== false || strpos($title,'صور') !== false 
            || strpos($title,'تشكيلة') !== false || strpos($title,'فيديو') !== false  
            || strpos($title,'صباحك أوروبي') !== false || strpos($title,'بالفيديو..') !== false
            || strpos($title,'.. فيديو') !== false || strpos($title,'شارك برأيك..') !== false
			|| strpos($title,'تقارير:') !== false || strpos($title,'تقرير:') !== false)
               continue;
               
            $image = $p -> find('.ArticleIMG img');
            $image = $image[0] -> src;
            
			
            $date = $p -> find('.date');
			$date = $date[0] -> innertext;
			
			$month_arabic = trim(str_replace(range(0,9),'',$date));

			$month_name_1 = array('0','يناير','فبراير','مارس','إبريل','مايو','يونيو','يوليو','أغسطس','سبتمبر','أكتوبر','نوفمبر','ديسمبر');
			$month_name_2 = array('0','يناير','فبراير','مارس','ابريل','مايو','يونيو','يوليو','اغسطس','سبتمبر','اكتوبر','نوفمبر','ديسمبر');
			
			$day = intval($date);
			$day = str_pad($day, 2, '0', STR_PAD_LEFT);
			
			$month = array_search($month_arabic, $month_name_1);
			if(empty($month))
			$month = array_search($month_arabic, $month_name_2);
			
            $date = $day.'-'.$month;
			
            $b = $p->find('.BodyContent p');
            $body = '';
			
            foreach ($b as $i => $key2) {
               
               $x = $key2->outertext;
               
			   if (strpos($x,'<strong>') !== false)
			   continue;
                    
               $body.= $x; 
            }
            
            $news[] = array('title'=>$title, 'image'=>$image, 'thumb'=>$thumbs[$index],
             'date' => $date, 'body'=>$body);
             
        }    
        
        $final = array();
       foreach ($news as $index => $key) {
           if(!empty($key['body']) && !empty($key['image']))
           $final[] = $key; 
       }
	   
	   print_r($final);die;
       //echo json_encode($final);
       
	}
	
    public function hihi($team,$p1)
    {
           
        $news = array();
        
        ///////////////////// Egypt /////////////////////
        
        if($team == 'egypt')
        {
            $team = 'egypt-national-team';
            $limit = 3;
        }
        
        if($team == 'ahly')
        {
            $team = 'alahli-egypt';
            $limit = 8;
        }
        
        if($team == 'zamalek')
        {
            $team = 'zamalek-egypt';
            $limit = 4;
        }
        
        if($team == 'arabic')
        {
            $team = 'arab-football';
            $limit = 89;
        }
        
        ////////////////////////////////////////////////////////////
        ///////////////////// Spain /////////////////////
        
        if($team == 'spain')
        {
            $team = 'spanish-national-team';
            $limit = 14;
        }
        
        if($team == 'br')
        {
            $team = 'barcelona';
            $limit = 140;
        }
        
        
        if($team == 'rm')
        {
            $team = 'real-madrid';
            $limit = 157;
        }
        
        if($team == 'spanish')
        {
            $team = 'spanish-football';
            $limit = 302;
        }
        
        ////////////////////////////////////////////////////////////
        ///////////////////// England /////////////////////
        
        if($team == 'england')
        {
            $team = 'england-national-team';
            $limit = 5;
        }
        
        if($team == 'ars')
        {
            $team = 'arsenal';
            $limit = 40;
        }
        
        if($team == 'manU')
        {
            $team = 'manchester-united';
            $limit = 48;
        }
        
        if($team == 'manC')
        {
            $team = 'manchester-city';
            $limit = 29;
        }
        
        if($team == 'chel')
        {
            $team = 'chelsea';
            $limit = 43;
        }
        
        if($team == 'liver')
        {
            $team = 'liverpool';
            $limit = 23;
        }
        
        if($team == 'english')
        {
            $team = 'england-football';
            $limit = 187;
        }
        
        ////////////////////////////////////////////////////////////
        ///////////////////// Italy /////////////////////
        
        if($team == 'italy')
        {
            $team = 'intalian-national-team';
            $limit = 7;
        }
        
        if($team == 'ac')
        {
            $team = 'ac-milan';
            $limit = 35;
        }
        
        if($team == 'juv')
        {
            $team = 'juventus';
            $limit = 30;
        }
        
        if($team == 'inter')
        {
            $team = 'inter-milan';
            $limit = 16;
        }
        
        if($team == 'roma')
        {
            $team = 'roma';
            $limit = 10;
        }

        if($team == 'italian')
        {
            $team = 'italian-football';
            $limit = 99;
        }
        
        ////////////////////////////////////////////////////////////
        ///////////////////// Germany /////////////////////
        
        if($team == 'germany')
        {
            $team = 'germany-national-team';
            $limit = 3;
        }
        
        if($team == 'bay')
        {
            $team = 'bayern-munchen';
            $limit = 23;
        }

        if($team == 'german')
        {
            $team = 'germany-football';
            $limit = 36;
        }
        
        ////////////////////////////////////////////////////////////
        ///////////////////// France /////////////////////
        
        if($team == 'france')
        {
            $team = 'france-national-team';
            $limit = 4;
        }
        
        if($team == 'psg')
        {
            $team = 'paris-saint-germain';
            $limit = 19;
        }
        
        if($team == 'french')
        {
            $team = 'france-football';
            $limit = 27;
        }
        
        ////////////////////////////////////////////////////////////
        ///////////////////// UEFA Champions League /////////////////////
        
        if($team == 'uefa')
        {
            $team = 'uefa-champions-league';
            $limit = 42;
        }

        ////////////////////////////////////////////////////////////
        ///////////////////// General News /////////////////////
        
        if($team == 'general')
        {
            $team = 'general-football';
            $limit = 26;
        }
        
        ////////////////////////////////////////////////////////////
        
        ///////////////////// Clubs World Cup /////////////////////
        
        if($team == 'clubs_world_cup')
        {
            $page = file_get_html('http://hihi2.com/category/clubs-world-cup_news');  
        }
        ////////////////////////////////////////////////////////////
        
        if($team != 'clubs_world_cup')
        if($p1 > $limit)
        {
            print_r('not found');
            die;
        }

        if($team != 'clubs_world_cup')
        $page = file_get_html('http://hihi2.com/category/'.$team.'-news/page/'.$p1);
        
        
        
        $x = $page->find('#content-loop .post');

        $k = 0;
        foreach ($x as $key) {
            
            if($k == 99)
            break;
            $link = $key -> find('a');
            $link = $link[0] -> href;

            $page = file_get_html($link);
            
            $thumb = $key -> find('.wp-post-image');
            $thumb = $thumb[0] -> src;
            
            $desc = $key -> find('.entry-excerpt');
            $desc = $desc[0] -> innertext;
            
            $title = $key -> find('a');
            $title = $title[1] -> title;
            
            $date = $key -> find('.entry-date');
            $date = $date[0] -> innertext;
            $date = explode('[', $date);
            $date = explode(']', $date[1]);
            $date = $date[0];
            
            
            if (strpos($title,'تقرير وفيديو') !== false
            || strpos($title,'بالصور') !== false || strpos($title,'صورة :') !== false
            || strpos($title,'صور') !== false || strpos($title,'تشكيلة:') !== false 
            || strpos($title,'تشكيلة :') !== false || strpos($title,'فيديو :') !== false  
            || strpos($title,'فيديو:') !== false)
               continue;
               
               
            $image = $page->find('.entry-content img');
            if(isset($image[0]))
            $image = $image[0] -> src;
            else continue;
            
            $body = '';
            $b = $page->find('.entry-content p');
            foreach ($b as $key2) {
                
               if(strpos($key2->outertext,'text-align:') !== false || strpos($key2->outertext,'img') !== false
               || strpos($key2->outertext,'هاي كورة') !== false || strpos($key2->outertext,'nbsp') !== false
               || strpos($key2->outertext,'هاى كورة') !== false)  
                continue;
               
               $body.= $key2->outertext; 
            }
            
            $x = strip_tags($body);
            if($x == '')
            continue;
            
            $news[] = array('title'=>$title,'thumb' => $thumb, 'image'=>$image,'date'=>$date,
            'desc' => $desc,'body'=>$body);
             
             $k++;
        }

        $final = array();
       foreach ($news as $index => $key) {
           if(!empty($key['body']) && !empty($key['image']))
           $final[] = $key;
           
       }
       
       $data['news'] = $final;
	   print_r($data);die;
	   echo json_encode($final);
       //$this->load->view('news',$data);
       //print_r($final);die;

    }

    public function yallakora($team)
    {
        //////////////////// Teams ///////////////////////////////////    
         
        if($team == 'ahly')
        {
            $team = 'http://www.yallakora.com/arabic/ykchampions/News.aspx?TeamID=1461&TourID=294';
            $flag = 1;
        }
        
        if($team == 'tala2e3')
        {
            $team = 'http://www.yallakora.com/arabic/ykchampions/News.aspx?TeamID=1462&TourID=294';
            $flag = 1;
        }
        
        if($team == 'enpy')
        {
            $team = 'http://www.yallakora.com/arabic/ykchampions/News.aspx?TeamID=1463&TourID=294';
            $flag = 1;
        }
        
        if($team == '7aras')
        {
            $team = 'http://www.yallakora.com/arabic/ykchampions/News.aspx?TeamID=1465&TourID=294';
            $flag = 1;
        }
        
        if($team == 'ism')
        {
            $team = 'http://www.yallakora.com/arabic/ykchampions/News.aspx?TeamID=1466&TourID=294';
            $flag = 1;
        }
        
        if($team == 'etehad')
        {
            $team = 'http://www.yallakora.com/arabic/ykchampions/News.aspx?TeamID=1467&TourID=294';
            $flag = 1;
        }
        
        if($team == 'zam')
        {
            $team = 'http://www.yallakora.com/arabic/ykchampions/News.aspx?TeamID=1469&TourID=294';
            $flag = 1;
        }
        
        if($team == 'moq')
        {
            $team = 'http://www.yallakora.com/arabic/ykchampions/News.aspx?TeamID=1470&TourID=294';
            $flag = 1;
        }
        
        if($team == 'petro')
        {
            $team = 'http://www.yallakora.com/arabic/ykchampions/News.aspx?TeamID=1471&TourID=294';
            $flag = 1;
        }
        
        if($team == 'shorta')
        {
            $team = 'http://www.yallakora.com/arabic/ykchampions/News.aspx?TeamID=1472&TourID=294   ';
            $flag = 1;
        }
        
        if($team == 'smoo7a')
        {
            $team = 'http://www.yallakora.com/arabic/ykchampions/News.aspx?TeamID=1473&TourID=294';
            $flag = 1;
        }
        
        if($team == 'da5')
        {
            $team = 'http://www.yallakora.com/arabic/ykchampions/News.aspx?TeamID=1474&TourID=294';
            $flag = 1;
        }
        
        if($team == 'entag')
        {
            $team = 'http://www.yallakora.com/arabic/ykchampions/News.aspx?TeamID=1475&TourID=294';
            $flag = 1;
        } 
        
        if($team == 'gona')
        {
            $team = 'http://www.yallakora.com/arabic/ykchampions/News.aspx?TeamID=1476&TourID=294';
            $flag = 1;
        } 
        
        if($team == 'tele')
        {
            $team = 'http://www.yallakora.com/arabic/ykchampions/News.aspx?TeamID=1477&TourID=294';
            $flag = 1;
        } 
        
        if($team == 'masr')
        {
            $team = 'http://www.yallakora.com/arabic/ykchampions/News.aspx?TeamID=1478&TourID=294';
            $flag = 1;
        }
        
        if($team == 'wady')
        {
            $team = 'http://www.yallakora.com/arabic/ykchampions/News.aspx?TeamID=1479&TourID=294';
            $flag = 1;
        }
        
        ////////////////////////// Leagues ////////////////////////////////////////
        
        if($team == 'clubs')
        {
            $team = 'http://www.yallakora.com/arabic/ykchampions/default.aspx?tourId=341&region=';
            $flag = 0;
        }
        
        if($team == 'en')
        {
            $team = 'http://www.yallakora.com/arabic/ykchampions/default.aspx?tourId=321&region=';
            $flag = 0;
        }
        
        if($team == 'gr')
        {
            $team = 'http://www.yallakora.com/arabic/ykchampions/default.aspx?tourId=323&region=';
            $flag = 0;
        }
        
        if($team == 'sp')
        {
            $team = 'http://www.yallakora.com/arabic/ykchampions/default.aspx?tourId=329&region=';
            $flag = 0;
        }
        
        if($team == 'fr')
        {
            $team = 'http://www.yallakora.com/arabic/ykchampions/default.aspx?tourId=322&region=';
            $flag = 0;
        }
        
        if($team == 'it')
        {
            $team = 'http://www.yallakora.com/arabic/ykchampions/default.aspx?tourId=331&region=';
            $flag = 0;
        }
        
        
        if($team == 'uefa')
        {
            $team = 'http://www.yallakora.com/arabic/ykchampions/default.aspx?tourId=332&region=';
            $flag = 0;
        }
        
        if($team == 'ksa')
        {
            $team = 'http://www.yallakora.com/arabic/ykchampions/default.aspx?tourId=325&region=';
            $flag = 0;
        }
        
        if($team == 'kas_etehad')
        {
            $team = 'http://www.yallakora.com/arabic/ykchampions/default.aspx?tourId=314&region=';
            $flag = 0;
        }
        
        if($team == 'carling')
        {
            $team = 'http://www.yallakora.com/arabic/ykchampions/default.aspx?tourId=340&region=';
            $flag = 0;
        }
        
        
        if($team == 'asia_cup_2015')
        {
            $team = 'http://www.yallakora.comarabic//ykchampions/default.aspx?tourId=295&region=';
            $flag = 0;
        }
        
        /////////////////////// Regions ////////////////////////////////////
        
        if($team == 'egypt')
        {
            $team = 'http://www.yallakora.com/arabic/YK_News/Regiondefault.aspx?newsregion=1&region=';
            $flag = 2;
        }  
        
        if($team == 'inter')
        {
            $team = 'http://www.yallakora.com/arabic/YK_News/Regiondefault.aspx?newsregion=8&region=';
            $flag = 2;
        }  
        
        if($team == 'arabic')
        {
            $team = 'http://www.yallakora.com/arabic/YK_News/Regiondefault.aspx?newsregion=9&region=';
            $flag = 2;
        }    
        
        $page = file_get_html($team);
        
        if(empty($flag)) 
        {
            $list = $page->find('.listnews a');
            $desc_list = $page->find('.listnews p');
        }
            
        
        else if($flag == 1)
            {
               $list = $page->find('#dvTeamNews a'); 
               $desc_list = $page->find('#dvTeamNews p');
            }
            
        else if($flag == 2)
            {
               $list = $page->find('#dvRegionNews a'); 
               $desc_list = $page->find('#dvRegionNews p');
            }    
        
            $urls = array();
            $desc = array();
            
            $d = '';

        foreach ($desc_list as $index => $key) {

            if($index % 2 != 0 && empty($flag))
            {
                $desc[] = $key -> innertext;
            }
        }
         //print_r($desc);die;
        foreach ($list as $index => $key) {

            if($index % 2 == 0 && !empty($flag))
            {
                $link = explode('..', $key -> href);
                $urls[] = 'http://www.yallakora.com/arabic'.$link[1];
            }
            
            else if (empty($flag))
            {
                $urls[] = $key -> href;
            } 
        }

        if(empty($flag)) 
            $list2 = $page->find('.listnews img');
            
        else if ($flag == 1)
            $list2 = $page->find('#dvTeamNews img');
        
        else if ($flag == 2)
            $list2 = $page->find('#dvRegionNews img');
            
        
        $thumbs = array();
        
        foreach ($list2 as $key) {
            $thumbs[] = $key -> src;
        }

        foreach ($urls as $index => $key) {
                
           //$p = file_get_html('http://www.yallakora.com/arabic/yk_news/details.aspx?tourid=321&id=235348&region=&RelatedTourID=285,321');
            $p = file_get_html($key);
            $title = $p -> find('#ctl00_ContentPlaceHolder1_details1_lblNewsTitle');
            $title = $title[0] -> innertext;

             if(strpos($title,'خاص..') !== false)
                {
                    $title = str_replace('خاص..', "", $title);
                }
            else if(strpos($title,'صيد ') !== false)
                {
                    $title = str_replace('صيد ', "", $title);
                }
            else if(strpos($title,'تقارير:') !== false)
                {
                    $title = str_replace('تقارير:', "", $title);
                }    
            else if(strpos($title,'صحيفة:') !== false)
                {
                    $title = str_replace('صحيفة:', "", $title);
                } 
                               
            else if (strpos($title,'صورة') !== false || strpos($title,'صور') !== false 
            || strpos($title,'تشكيلة') !== false || strpos($title,'فيديو') !== false  
            || strpos($title,'صباحك أوروبي') !== false || strpos($title,'بالفيديو..') !== false
            || strpos($title,'.. فيديو') !== false || strpos($title,'شارك برأيك..') !== false)
               continue;
               
            $image = $p -> find('.brdImg');
            $image = $image[0] -> src;
            
            $day = $p -> find('.calDay');
            $day = intval($day[0] -> innertext);
            
            $month = $p -> find('.calMonth');
            $month = $month[0] -> innertext;
            
            $year = $p -> find('.calYear');
            $year = intval($year[0] -> innertext);
            
            $date = $day.'-'.$month.'-'.$year;
            
            $b = $p->find('.bodycontent p');
            $body = '';
            //print_r($b[1]->outertext);die;
            foreach ($b as $i => $key2) {
               
               $x = $key2->outertext;
                
                // if (strpos($x,'href') !== false)
                //if ($i == 0)
                //continue;
                    
               $body.= $x; 
            }
            
            $body = explode('<a href',$body);
            $body = $body[0];
            
            $news[] = array('title'=>$title,'desc'=>$desc[$index], 'image'=>$image, 'thumb'=>$thumbs[$index],
             'date' => $date, 'body'=>$body);
             
        }    
        
        $final = array();
       foreach ($news as $index => $key) {
           if(!empty($key['body']) && !empty($key['image']))
           $final[] = $key;
           
       }
       echo json_encode($final);
       //print_r($final);die;
    }

      public function korabia($team)
    { 
        $news = array();
        
        if($team  == 'eu')
        $team ='europe';
        
        if($team  == 'eg')
        $team ='egypt';
        
        if($team  == 'gu')
        $team ='gulf';
        
        if($team  == 'na')
        $team ='northafrica';
        
        if($team  == 'general')
        $team ='news';
        
        $page = file_get_html('http://www.korabia.com/'.$team);
        $page = $page -> find('#divNews .news_box');
       
        foreach ($page as $key) {
           
           $hrefs = $key -> find('a');
           $link = 'http://www.korabia.com/'.$hrefs[0] -> href;  
           $title = $hrefs[1] -> innertext;  
           
           if (strpos($title,'خاص بالصور|') !== false
            || strpos($title,'بالصور') !== false || strpos($title,'صورة :') !== false
            || strpos($title,'صور') !== false || strpos($title,'تشكيلة:') !== false || 
            strpos($title,'تشكيلة :') !== false)
               continue;
           
           if(strpos($title,'فيديو|') !== false)
                {
                    $title = str_replace('فيديو|', "", $title);
                }
                
           if(strpos($title,'خاص|') !== false)
                {
                    $title = str_replace('خاص|', "", $title);
                }
           if(strpos($title,'فيديو|') !== false)
                {
                    $title = str_replace('فيديو|', "", $title);
                }     
          
           $thumb = $key -> find('img');
           $thumb = explode('http', $thumb[0]);
           $thumb = 'http'.$thumb[1];
           $thumb = explode('"', $thumb);
           $thumb = $thumb[0];
            
           $desc = $key -> find('.ant');
           $desc = $desc[0] -> innertext;
           
           $date = $key -> find('h4');
           $date = $date[0] -> innertext;
           
           $list[] = array('link' => $link, 'title' => $title,'desc' => $desc, 'thumb' => $thumb,
           'image' => '','date' => $date, 'body'=>'');
        }
        //print_r($list);die;
        foreach ($list as $index => $key) {
            
            $link = $key['link'];
            $page = file_get_html($link);
            
            $image = $page -> find('.details_img img');
            $image = explode('http', $image[0]);
            $image = 'http'.$image[1];
            $image = explode('"', $image);
            $image = $image[0];

            $b = $page -> find('.fontt p');
            $body ='';
            foreach ($b as $key2) {
               
               $x = $key2 -> outertext;
                
               if (strpos($x,'href') !== false)
               continue;

               $body .= $x;
            }
            
            $n = explode('<div class="sayingOutOfContent', $body);
            $p1 = $n[0];
            
            $n = explode('</div>', $n[1]);
            $p2 = $n[count($n) - 1];
            
            $body = $p1.$p2;
            $list[$index]['body'] = $body;
            $list[$index]['image'] = $image;
            
            //print_r($list);die;
        }
        
        $data['news'] = $list;
		//echo json_encode($list);
        //$this->load->view('news',$data);
       print_r($data);die;
      }
    
    public function fifa($team)
    { 
       $histroy = 'http://ar.fifa.com/classicfootball/history/news/index.html';
       
       if($team == 'general')
       $team = 'http://ar.fifa.com/newscentre/news/index.html';
       
       if($team == 'africa')
       $team = 'http://ar.fifa.com/newscentre/africa/news/index.html';
       
       if($team == 'asia')
       $team = 'http://ar.fifa.com/newscentre/asia/news/index.html';
       
       if($team == 'europe')
       $team = 'http://ar.fifa.com/newscentre/europe/news/index.html';
       
       if($team == 'nccamerica')
       $team = 'http://ar.fifa.com/newscentre/nccamerica/news/index.html';
       
       if($team == 'oceania')
       $team = 'http://ar.fifa.com/newscentre/oceania/news/index.html';
       
       if($team == 'southamerica')
       $team = 'http://ar.fifa.com/newscentre/southamerica/news/index.html';
       
       
       $page = file_get_html($team);
       $page = $page -> find('.nl-list li');
       
       $news = array();
       $urls = array();
       
       
       foreach ($page as $index => $key) {
         
               $type =  $key -> find('.nt-rl a');   
           
               if(isset($type[0]))
               {
                   $type =  $type[0] -> innertext;    
                   if(strpos($type,'مثل هذا اليوم') !== false || strpos($type,'الأسبوع') !== false
                   || strpos($type,'FIFA Weekly') !== false || strpos($type,'ميلاد') !== false)
                    continue;
               }
               
               $title = $key -> find('.nt-newsTitle a');

               if(!isset($title[0]))
               continue;
               
               $link = 'http://ar.fifa.com'.$title[0] -> href;
               $title = $title[0] -> title;
               
               $date = $key -> find('.nt-date');
               $date = $date[0] -> innertext;
               
               $thumb = $key -> find('.nt-thumb img');
               $thumb = $thumb[0] -> src;
               $thumb = 'http://ar.fifa.com'.$thumb;
               
               $desc = $key -> find('.nt-text a');
               
               if(isset($desc[0]))
               $desc = $desc[0] -> innertext;
               else $desc = '';
               
               $urls[] = $link;
               
               $news[] = array('link' => $link, 'title' => $title,'desc' => $desc, 'thumb' => $thumb,
               'image' => '','date' => $date, 'body'=>'');
         
       }

       foreach ($urls as $index => $key) {
              
                  
              $page = file_get_html($key);
           
              $image = $page -> find('.articleBody img');
              $image = 'http://ar.fifa.com'.$image[0] -> src;
              
              $vid = $page -> find('iframe');
               if(isset($vid[0]))
               continue;
              
              $b = $page -> find('.articleBody p');

              $body = '';
              foreach ($b as $key2) {
                  
                 if(strpos($key2 -> innertext,'لمزيد من المعلومات') !== false)
                 continue;
                 
                 $body .= $key2 -> outertext;
               
              }
              
              $body = strip_tags($body,'<p><span>');
              
              $x = '؟';
              
              if(substr_count($body,$x) > 2 || strlen(strip_tags($body) > 3000))
              $body = '';
              
              
              
              $news[$index]['image'] = $image; 
              $news[$index]['body'] = $body; 
              
           }
       
       $final = array();
       foreach ($news as $index => $key) {
           if(!empty($key['body']))
           $final[] = $key;
           
       }
      echo json_encode($final);
	   //print_r($final);die;
       
      }
      
      public function super($team)
    { 
       
       
       if($team == 'egyptian')
       $team = 'http://www.super.ae/ar/football/arabic/egypt';
       
       if($team == 'ksa')
       $team = 'http://www.super.ae/ar/football/arabic/saudi';
       
       if($team == 'europian')
       $team = 'http://www.super.ae/ar/football/international/europe';
       
       if($team == 'italian')
       $team = 'http://www.super.ae/ar/football/international/italy';

       if($team == 'spanish')
       $team = 'http://www.super.ae/ar/football/international/spain';
       
       if($team == 'german')
       $team = 'http://www.super.ae/ar/football/international/germany';
       
       if($team == 'english')
       $team = 'http://www.super.ae/ar/football/international/england';
       
       if($team == 'french')
       $team = 'http://www.super.ae/ar/football/international/france';
       
       if($team == 'latin')
       $team = 'http://www.super.ae/ar/football/international/latin-football';
       
       $page = file_get_html($team);
       
       
       $news = array();
       ///////////////////// Main /////////////////////
       
       $title = $page -> find('.categorylargeHolder .catgoryThumblarge img');
       $title = $title[0] -> title;
       
       //print_r($title);die;

       $link = $page -> find('.categorylargeHolder .catgoryThumblarge a');
       $link = $link[0] -> href;
       
       $desc = $page -> find('.sm_article_cont');
       $desc = $desc[0] -> innertext;
       
       $p = file_get_html($link); 
           
       $image = $p -> find('.picture img');
       if(isset($image[0]))
       $image = $image[0] -> src;

       //print_r($image);die;
       
       $b = $p -> find('.articleContHolder #articleView p');

       $body = '';
       foreach ($b as $key2) {
           
            if(strpos($key2 -> outertext,'<iframe') !== false)
            break;
            
            if(strpos($key2 -> outertext,'href') !== false || strpos($key2 -> outertext,'فيسبوك وتويتر') !== false )
            continue;
              
           $body .= $key2 -> outertext; 
       }
       
       if (strpos($title,'بالفيديو :') !== false)
       {
           $title = str_replace('بالفيديو :', "", $title);
       }
       
       else if (strpos($title,'فيديو :') !== false)
       {
           $title = str_replace('فيديو :', "", $title);
       }
               
       $news[] = array('link' => $link, 'title' => $title,'desc' => $desc,'image' => $image, 'body'=>$body);
               //print_r($news);die;  
       ///////////////////// Other News ///////////////////////
       

       $list = $page -> find('.quizthumblisting li');
       //print_r($list[0] -> outertext);die;  
       foreach ($list as $index => $key) {
               
           $href = $key -> find('.listedthumbimg a');
           $link = $href[0] -> href;

           $title = $href[0] -> title;
           
           $page = file_get_html($link); 
           
           $image = $page -> find('.picture img');
           if(isset($image[0]))
           $image = $image[0] -> src;

           $b = $page -> find('.articleContHolder #articleView p');

           $body = '';
           foreach ($b as $key2) {
               
                if(strpos($key2 -> outertext,'<iframe') !== false)
                break;
                
                if(strpos($key2 -> outertext,'href') !== false || strpos($key2 -> outertext,'فيسبوك وتويتر') !== false
                || strpos($key2 -> outertext,'للتواصل') !== false )
                  continue;
                    
               $body .= $key2 -> outertext; 
           }
           
           if (strpos($title,'بالفيديو :') !== false)
           {
               $title = str_replace('بالفيديو :', "", $title);
           }
           
           else if (strpos($title,'فيديو :') !== false)
           {
               $title = str_replace('فيديو :', "", $title);
           }

           $news[] = array('link' => $link, 'title' => $title,'desc' => '','image' => $image, 'body'=>$body);
          
       }
       
       $final = array();
       foreach ($news as $index => $key) {
           if(!empty($key['body']) && !empty($key['image']))
           $final[] = $key;
           
       }
       echo json_encode($final);
       //print_r($final);die;
       
      }
}
?>