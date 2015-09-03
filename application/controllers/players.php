<?php

class players extends CI_Controller {
        
        
    function __construct()
    {
        parent::__construct();
        
        $this->load->helper('form');
        $this->load->helper('url'); 
        $this->load->helper('dom');
        $this->load->library('curl'); 
        $this->load->library('rssparser');
        $this->load->model(array('espn_model','teams_model','players_model'));
    }
    
    public function get_players($type)
    {
        
            
        if($type == 'en')
        $link = 'http://espnfc.com/league/_/id/eng.1/english-premier-league?cc=3888';
            
        if($type == 'sp')
        $link = 'http://espnfc.com/league/_/id/esp.1/spanish-la-liga?cc=3888';
        
        if($type == 'gr')
        $link = 'http://espnfc.com/league/_/id/ger.1/german-bundesliga?cc=3888';
        
        if($type == 'it')
        $link = 'http://espnfc.com/league/_/id/ita.1/italian-serie-a?cc=3888';
        
        if($type == 'fr')
        $link = 'http://espnfc.com/league/_/id/fra.1/french-ligue-1?cc=3888';
        
        if($type == 'pr')
        $link = 'http://espnfc.com/league/_/league/por.1/portuguese-liga?cc=3888';
        
        
        $p_list = array();
        //$page = file_get_html($link);
        $page = file_get_html('http://www.filgoal.com/Arabic/TeamProfile.aspx?ChampId=378&ClubId=93');
        
        
        if(!isset($table[0]))
        {
            $table = $page->find('.TPPList');  
        }
        
        $x = $table[0] -> find('.TPTeam');
        print_r($x[0] -> outertext);die;
        
        while(!isset($x[0]))
        $x2 = $x[0] -> find('p');
        
        print_r($x2[3] -> outertext);die;
        
        $rows = $page->find('.mod-content tr');
        unset($rows[0]);
        
        $links = array();
        
        foreach ($rows as $key) {
            
           $row = $key->find('td a'); 
            
           if(empty($row))
           break;
            
           $link = $row[0] -> href;
           $link = explode('id/', $link);
           $link = 'http://espnfc.com/team/squad/_/id/'.$link[1].'?cc=3888';
           $links[] = $link;
           
           
        }

        foreach ($links as $key) {
           
           $players = array();
           $ar_players = array();
        
           $page = file_get_html($key);

           $rows1 = $page->find('.mod-content #statsBody_0 tr');
           $rows2 = $page->find('.mod-content #statsBody_1 tr');
           
           $t_name = $page->find('.soccernet-logo a');
           $t_name = $t_name[0] -> innertext;
           
           $a = $this->teams_model->get_team_ar_link($t_name);
           $ar_link = $a[0]['ar_player_link'];
           
           foreach ($rows1 as $key1) {
              
              $x = $key1 -> find('td');    
              $player = array('number' => $x[0] -> innertext, 'en_name' => strip_tags($x[1] -> innertext),
              'team' => $t_name);
              
              $players[] = $player;
               
           }
           
           
           foreach ($rows2 as $key1) {
              
              $x = $key1 -> find('td');    
              $player = array('number' => $x[0] -> innertext, 'en_name' => strip_tags($x[1] -> innertext),
              'team' => $t_name);
              
              $players[] = $player;
           }
           
            $this->players_model->insert_players($players);
            
            $page2 = file_get_html($ar_link);
            $ar_players_rows = $page2 -> find('#teamSquadModule table tr');
            
            foreach ($ar_players_rows as $key) {
                
                $role = $key -> find('.player_role'); 
                
                if(isset($role[0]))
                {
                    $role = $role[0] -> innertext;   
                
                    if(($role != 'حارس مرمى' && $role != 'مدافع' && $role != 'خط الوسط' && $role != 'مهاجم') == false)
                    {
                        $td = $key -> find('td');
                    
                        $num1 = intval($td[0] -> innertext);
                        $name1 = strip_tags($td[1] -> innertext);
                        $name1 = str_replace('&quot;', '"', $name1);
                        
                        $ar_players[] = array('number' => $num1, 'ar_name' => $name1);
                    }
        
                    
                }
               
                ///////// 2/////////////
                
                $role = $key -> find('.player_role'); 
                
                if(isset($role[1]))
                {
                    $role = $role[1] -> innertext;   
                
                    if(($role != 'حارس مرمى' && $role != 'مدافع' && $role != 'خط الوسط' && $role != 'مهاجم') == false)
                    {
                        $num2 = intval($td[4] -> innertext);
                        $name2 = strip_tags($td[5] -> innertext);
                        $name2 = str_replace('&quot;', '"', $name2);
                        
                        $ar_players[] = array('number' => $num2, 'ar_name' => $name2);
                    }
                    
                    
                }
                
                
                
            }
            
                $this->players_model->update_players($ar_players,$t_name);
           
        }

        $this->players_model->delete_transfers();
        print_r('Success');die;
    }

    public function delete_duplicates()
    {
        $x = $this->players_model->delete_duplicates();
        print_r($x);die;
    }


    
}

?>