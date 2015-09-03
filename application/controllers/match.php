<?php 

class match extends CI_Controller {
        
        
    function __construct()
    {
        parent::__construct();
        
        $this->load->helper('form');
        $this->load->helper('url'); 
        $this->load->helper('dom');
        $this->load->model(array('espn_model','teams_model','players_model'));
    }

      public function espn($team)
    { 
       if($team == 'en')
       $results = 'http://espnfc.com/results/_/league/eng.1/english-premier-league?cc=3888';
       
       if($team == 'sp')
       $results = 'http://espnfc.com/results/_/league/esp.1/spanish-la-liga?cc=3888';
       
       if($team == 'it')
       $results = 'http://espnfc.com/results/_/league/ita.1/ita-serie-a?cc=3888';
       
       if($team == 'gr')
       $results = 'http://espnfc.com/results/_/league/ger.1/german-bundesliga?cc=3888';
       
       if($team == 'fr')
       $results = 'http://espnfc.com/results/_/league/fra.1/french-ligue-1?cc=3888';
       
       if($team == 'pr')
       $results = 'http://espnfc.com/results/_/league/por.1/portuguese-liga?cc=3888';
       

       $temp_players = $this->players_model->get_players_league($team);
       $players = array();
       
       foreach ($temp_players as $key) {
           $players[str_replace(' ', "", $key['en_name'])] = $key['ar_name'];
       }
       
       $temp = $this->teams_model->get_all_teams();
       $teams = array();
       foreach ($temp as $key) {   
           $teams[str_replace(' ', "", $key['en_name'])] = $key['ar_name'];
       }
       
       $data['results'] = $this->espn_model->get_results($results,$teams);
         
       foreach ($data['results'] as $index1 => $key) {
           foreach ($key as $index2 => $match) {
               $data['results'][$index1][$index2]['report'] = $this->espn_model->get_report($match['report'],$players);
           }
       }   
    
       print_r($data);die;
    
       
      }
}
?>