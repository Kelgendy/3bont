<?php

Class teams_model extends CI_Model {
    
    
    function get_all_teams()
    {
        $this->db->select('en_name,ar_name'); 
        $this->db->from('teams'); 
        return $this -> db -> get() -> result_array();
    }
    
    function get_team($league)
    {
        $this->db->select('en_name,ar_name'); 
        $this->db->from('teams'); 
        $this->db->where('league',$league); 
        return $this -> db -> get() -> result_array();
    }
    
    function get_team_ar_link($team)
    {
        $this->db->select('ar_player_link'); 
        $this->db->from('teams'); 
        $this->db->where('en_name',$team); 
        return $this -> db -> get() -> result_array();
    }
    
    function get_standing($page,$teams)
    {
        $x = $page->find('#Live_false_tableBody_1 tr');
        
  
        foreach($x as $index => $element) 
        {
           $td = $element->find('td'); 
              
           $rank = intval($td[0] -> innertext);
           $name = str_replace(' ', "", strip_tags($td[2] -> innertext));
           $standings[intval($rank)]['team'] =  $teams[$name];
           
           $progress = $td[1] -> find('img');
           $progress = $progress[0] -> src;
           $progress = explode('/', $progress);
           $progress = $progress[count($progress) - 1];
           $progress = explode('_', $progress);
           $progress = $progress[0];
           
           if($progress == 'right')
           $progress = 'Remained';
           
           else if($progress == 'up')
           $progress = 'Moved Up';
           
           else if($progress == 'down')
           $progress = 'Moved Down';
           
           $standings[intval($rank)]['progress'] = $progress;
           
           
           
           $standings[$rank]['stats']['overall']['P'] =  $td[3] -> innertext;
           $standings[$rank]['stats']['overall']['W'] =  $td[4] -> innertext;
           $standings[$rank]['stats']['overall']['D'] =  $td[5] -> innertext;
           $standings[$rank]['stats']['overall']['L'] =  $td[6] -> innertext;
           $standings[$rank]['stats']['overall']['S'] =  $td[7] -> innertext;
           $standings[$rank]['stats']['overall']['A'] =  $td[8] -> innertext;
           $standings[$rank]['stats']['overall']['GD'] =  $td[22] -> innertext;
           $standings[$rank]['stats']['overall']['Pts'] =  $td[23] -> innertext;
           
           $standings[$rank]['stats']['home']['W'] =  $td[10] -> innertext;
           $standings[$rank]['stats']['home']['D'] =  $td[11] -> innertext;
           $standings[$rank]['stats']['home']['L'] =  $td[12] -> innertext;
           $standings[$rank]['stats']['home']['S'] =  $td[13] -> innertext;
           $standings[$rank]['stats']['home']['A'] =  $td[14] -> innertext;
           
           $standings[$rank]['stats']['away']['W'] =  $td[16] -> innertext;
           $standings[$rank]['stats']['away']['D'] =  $td[17] -> innertext;
           $standings[$rank]['stats']['away']['L'] =  $td[18] -> innertext;
           $standings[$rank]['stats']['away']['S'] =  $td[19] -> innertext;
           $standings[$rank]['stats']['away']['A'] =  $td[20] -> innertext;
  
        }
        
        return $standings;
    }
    
}    