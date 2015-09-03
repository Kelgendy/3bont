<?php

Class espn_model extends CI_Model {
    
    
    function get_results($results,$teams)
    {
        $res = array();
        $months = array('January','February','March','April','May','June','July','August','September','October'
       ,'November','December');

       $results = file_get_html($results);
       $results_table = $results -> find('#my-teams-table .mod-container');
       
       foreach ($results_table as $index => $key) {
              
          $row = $key -> find('tr'); 
          $date = strip_tags($row[0] -> innertext);
          unset($row[0]);
          unset($row[1]);
          
          foreach ($row as $key2) {
             
              $table = $key2 -> find('td'); 
           
              $report = $table[2] -> find('a');
              $report = $report[0] -> href;
              
              // $venue = strip_tags($table[4] -> innertext);
              // $venue = explode('(', $venue);
              // $venue = $venue[0];
              
              
              $home = $teams[str_replace(' ', "", strip_tags($table[1] -> innertext))];
              $away = $teams[str_replace(' ', "", strip_tags($table[3] -> innertext))];
              
              $res[$date][] = array('home' => $home,'score' => strip_tags($table[2] -> innertext),'away' => $away,
              'report' => $report);
            
           }
          
       }  
       
       return $res;
    }

    function get_report($link)
    {
       $report = file_get_html($link);
       $sections = $report -> find('.last section');
       
       $goals = $sections[0] -> find('div');
       
       $home_goals = $goals[0] -> find('td');
       $away_goals = $goals[1] -> find('td');
       
       $stats = array();
  
       foreach ($home_goals as $key) {
               
           if($key -> innertext == '&nbsp;')
           {
               $stats['home']['goals'] = array();
               break;
           }

           $name = $key -> find('a');
           $name = $name[0] -> title;
           
           $time = explode('/a>', $key -> innertext);
           $time = $time[1];
           $time = str_replace(' ', "", $time);
           $time = explode('strong>', $time);
           
           if(isset($time[1]))
           $time = '('.$time[1];
           
           else $time = $time[0];
           
           $time = str_replace(' ', "", $time);
                          
           $stats['home']['goals'][] = array('name' => $name, 'time' => $time);
       }
       

       foreach ($away_goals as $key) {
           
           if($key -> innertext == '&nbsp;')
           {
               $stats['away']['goals'] = array();
               break;
           }
           
           $name = $key -> find('a');
           $name = $name[0] -> title;
           
           $time = explode('<a', $key -> innertext);
           $time = $time[0];
           $time = str_replace(' ', "", $time);
           $time = explode('strong>', $time);
           
           if(isset($time[1]))
           $time = '('.$time[1];
           
           else $time = $time[0];
           
           $time = str_replace(' ', "", $time);
               
           $stats['away']['goals'][] = array('name' => $name, 'time' => $time);
       }
       
       $match_stats = $sections[1] -> find('td');
       
       $stats['home']['stats'][] = array('Shots (on goal)' => $match_stats[0] -> innertext, 
       'Fouls' => $match_stats[3] -> innertext,'Corner kicks' => $match_stats[6] -> innertext, 
       'Offsides' => $match_stats[9] -> innertext,'Time of Possession' => $match_stats[12] -> innertext, 
       'Yellow Cards' => $match_stats[15] -> innertext,'Red Cards' => $match_stats[18] -> innertext, 
       'Saves' => $match_stats[21] -> innertext);
       
       $stats['away']['stats'][] = array('Shots (on goal)' => $match_stats[2] -> innertext, 
       'Fouls' => $match_stats[5] -> innertext,'Corner kicks' => $match_stats[8] -> innertext, 
       'Offsides' => $match_stats[11] -> innertext,'Time of Possession' => $match_stats[14] -> innertext, 
       'Yellow Cards' => $match_stats[17] -> innertext,'Red Cards' => $match_stats[20] -> innertext, 
       'Saves' => $match_stats[23] -> innertext);
       
       $cards = $sections[2] -> children;
       
       $stats['home']['yellow cards'] = array();
       $stats['home']['red cards'] = array();
       
       foreach ($cards as $index => $key) {
            
           $x = $key -> find('th');
           
           if(isset($x[0]))
           {
                  if($x[0] -> innertext == 'Yellow Cards')
                 {
                       $home_yellow_cards = $cards[$index + 1];
                       $away_yellow_cards = $cards[$index + 2]; 
                       
                       $home_yellow_cards = $home_yellow_cards -> find('td'); 
                       $away_yellow_cards = $away_yellow_cards -> find('td');
                       
                       foreach ($home_yellow_cards as $key1) {
                   
                           $name = $key1 -> find('a');
                           $name = $name[0] -> title;
                           
                           $time = explode('/a>', $key1 -> innertext);
                           $time = $time[1];
                           $time = str_replace(' ', "", $time);
                               
                           $stats['home']['yellow cards'][] = array('name' => $name, 'time' => $time);
                       }  
                       
                       foreach ($away_yellow_cards as $key1) {
                   
                           $name = $key1 -> find('a');
                           $name = $name[0] -> title;
                           
                           $time = explode('/a>', $key1 -> innertext);
                           $time = $time[1];
                           $time = str_replace(' ', "", $time);
                               
                           $stats['away']['yellow cards'][] = array('name' => $name, 'time' => $time);
                       }   
                    }

                    else if($x[0] -> innertext == 'Red Cards')
                 {
                       $home_red_cards = $cards[$index + 1];
                       $away_red_cards = $cards[$index + 2]; 
                       
                       $home_red_cards = $home_red_cards -> find('td'); 
                       $away_red_cards = $away_red_cards -> find('td');
                       
                       foreach ($home_red_cards as $key1) {
                   
                           $name = $key1 -> find('a');
                           $name = $name[0] -> title;
                           
                           $time = explode('/a>', $key1 -> innertext);
                           $time = $time[1];
                           $time = str_replace(' ', "", $time);
                               
                           $stats['home']['red cards'][] = array('name' => $name, 'time' => $time);
                       }  
                       
                       foreach ($away_red_cards as $key1) {
                   
                           $name = $key1 -> find('a');
                           $name = $name[0] -> title;
                           
                           $time = explode('/a>', $key1 -> innertext);
                           $time = $time[1];
                           $time = str_replace(' ', "", $time);
                               
                           $stats['away']['red cards'][] = array('name' => $name, 'time' => $time);
                       }   
                    } 
           }
       }
        
        return $stats;
    }
    
}    