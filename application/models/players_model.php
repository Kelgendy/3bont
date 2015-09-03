<?php

Class players_model extends CI_Model {
    
    
    function get_players($team)
    {
        $this->db->select('en_name,ar_name'); 
        $this->db->from('players'); 
        $this->db->where('team',$team); 
        return $this -> db -> get() -> result_array();
    }
    
    function get_players_league($league)
    {
        $this->db->select('players.en_name,players.ar_name'); 
        $this->db->from('players'); 
        $this->db->join('teams', 'teams.en_name = players.team');
        $this->db->where('teams.league',$league); 
        return $this -> db -> get() -> result_array();
    }
    
    function insert_players($players)
    {
        $orig_db_debug = $this->db->db_debug;
        $this->db->db_debug = FALSE;
              
        foreach ($players as $key) {

            $this->db->insert('players',$key); 
        }
        $this->db->db_debug = $orig_db_debug;
    }
    
    function update_players($players,$team)
    {
        foreach ($players as $key) {
                
            $number = $key['number'];
            $ar_name = $key['ar_name'];
            
            $this->db->where(array('team' => $team, 'number' => $number));
            $this->db->update('players', array('ar_name' => $ar_name));  
        }
        
    }
    
    function delete_transfers()
    {
        $this->db->delete('players', array('number' => '0')); 
        
    }
    
}    