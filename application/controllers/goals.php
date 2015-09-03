<?php 

class goals extends CI_Controller {
        
        
    function __construct()
    {
        parent::__construct();
        
        $this->load->helper('form');
        $this->load->helper('url'); 
        $this->load->helper('dom');
        $this->load->library('curl'); 
    }
    
    public function hihi($team,$page)
    {
        $this->load->library('rssparser');   
        $this->load->library('curl');
        
        
        ///////////////////// Egypt /////////////////////
        
        if($team == 'egypt')
        {
            $team = 'egypt-national-team';
            $limit = 1;
        }
        
        if($team == 'ahly')
        {
            $team = 'alahli-egypt';
            $limit = 1;
        }
        
        if($team == 'zamalek')
        {
            $team = 'zamalek-egypt';
            $limit = 1;
        }
        
        ////////////////////////////////////////////////////////////
        ///////////////////// Spain /////////////////////
        
        if($team == 'spain')
        {
            $team = 'spanish-national-team';
            $limit = 2;
        }
        
        if($team == 'br')
        {
            $team = 'barcelona';
            $limit = 12;
        }
        
        
        if($team == 'rm')
        {
            $team = 'real-madrid';
            $limit = 14;
        }
        
        ////////////////////////////////////////////////////////////
        ///////////////////// England /////////////////////
        
        if($team == 'england')
        {
            $team = 'england-national-team';
            $limit = 2;
        }
        
        if($team == 'ars')
        {
            $team = 'arsenal';
            $limit = 6;
        }
        
        if($team == 'manU')
        {
            $team = 'manchester-united';
            $limit = 7;
        }
        
        if($team == 'manC')
        {
            $team = 'manchester-city';
            $limit = 7;
        }
        
        if($team == 'chel')
        {
            $team = 'chelsea';
            $limit = 9;
        }
        
        if($team == 'liver')
        {
            $team = 'liverpool';
            $limit = 5;
        }
        
        ////////////////////////////////////////////////////////////
        ///////////////////// Italy /////////////////////
        
        if($team == 'italy')
        {
            $team = 'intalian-national-team';
            $limit = 3;
        }
        
        if($team == 'ac')
        {
            $team = 'ac-milan';
            $limit = 7;
        }
        
        if($team == 'juv')
        {
            $team = 'juventus';
            $limit = 9;
        }
        
        if($team == 'inter')
        {
            $team = 'inter-milan';
            $limit = 6;
        }
        
        if($team == 'roma')
        {
            $team = 'roma';
            $limit = 5;
        }
        
        ////////////////////////////////////////////////////////////
        ///////////////////// Germany /////////////////////
        
        if($team == 'germany')
        {
            $team = 'germany-national-team';
            $limit = 2;
        }
        
        if($team == 'bay')
        {
            $team = 'bayern-munchen';
            $limit = 10;
        }
        
        ////////////////////////////////////////////////////////////
        ///////////////////// France /////////////////////
        
        if($team == 'france')
        {
            $team = 'france-national-team';
            $limit = 2;
        }
        
        if($team == 'psg')
        {
            $team = 'paris-saint-germain';
            $limit = 6;
        }
        
        ////////////////////////////////////////////////////////////
        ///////////////////// UEFA Champions League /////////////////////
        
        if($team == 'uefa')
        {
            $team = 'uefa-champions-league';
            $limit = 13;
        }

        ////////////////////////////////////////////////////////////
        ///////////////////// Club World Cup /////////////////////
        
        if($team == 'club_world_cup')
        {
            $team = 'clubs-world-cup';
            $limit = 1;
        }
        
        ////////////////////////////////////////////////////////////
        ///////////////////// General News /////////////////////
        
        if($team == 'general')
        {
            $team = 'general-football';
            $limit = 5;
        }
        
        ////////////////////////////////////////////////////////////
        
        if($page > $limit)
        {
            print_r('not found');
            die;
        }

        $page = file_get_html('http://hihi2.com/category/'.$team.'-goals/page/'.$page);
        // echo "<pre>"; print_r($page);echo "</pre>"; die;
        $x = $page->find('#content-loop .post');
        
        $goals = array();
        
        foreach ($x as $key) {

            $link = $key -> find('a');
            $link = $link[0] -> href;
            
            $title = $key -> find('.entry-title a');
            $title = $title[0] -> title;
            
            $image = $key -> find('.entry-thumb');
            $image = $image[0] -> src;
            
            $page = file_get_html($link);
            
            $video = $page -> find('.entry-content link');
            
            if(!isset($video[1]))
            continue;
            
            else
            $video = $video[1] -> href;
            
            $goals[] = array('title' => $title, 'image' => $image, 'video' => $video);
        }
        
        print_r($goals);die;
    }
    
    public function yallakora()
    {
        $page = file_get_html('http://www.yallakora.com/arabic/YKChampions/MultimediaEmbed.aspx?TourID=0&CategoryID=218&ItemID=329&ID=731603&MediaItemID=722524');
        $video = $page->find('.vadio-player iframe');
        
        $video = $video[0] -> src;
        print_r($video);die;
        
        $page = file_get_html('http://www.yallakora.com/arabic/ykchampions/News.aspx?TeamID=1461&TourID=294');
        $list = $page->find('#dvTeamNews a');
        $urls = array();
        
        foreach ($list as $index => $key) {

            if($index % 2 == 0)
            {
                $link = explode('..', $key -> href);
                $urls[] = 'http://www.yallakora.com/arabic'.$link[1];
            } 
        }
        
        $list2 = $page->find('#dvTeamNews img');
        $thumbs = array();
        
        foreach ($list2 as $key) {
            $thumbs[] = $key -> src;
        }

        foreach ($urls as $index => $key) {
                
            $p = file_get_html($key);
            
            $title = $p -> find('#ctl00_ContentPlaceHolder1_details1_lblNewsTitle');
            $title = $title[0] -> innertext;
            
            $image = $p -> find('.brdImg');
            $image = $image[0] -> src;
            
            $b = $p->find('.bodycontent p');
            $body = '';
            
            foreach ($b as $key2) {
                   
               // if(strpos($key2->outertext,'font color') !== false || 
               // strpos($key2->outertext,'google_ad') !== false)  
                // continue;
                    
               $body.= $key2->outertext; 
            }
            
            $news[] = array('title'=>$title, 'image'=>$image, 'thumb'=>$thumbs[$index],
             'body'=>$body);
        }    
        
        print_r($news);die;
    }
}
?>