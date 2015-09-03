<?php 

class standings extends CI_Controller {
        
        
    function __construct()
    {
        parent::__construct();
        
        $this->load->helper('form');
        $this->load->helper('url'); 
        $this->load->helper('dom');
        $this->load->model(array('espn_model','teams_model','players_model'));
    }
    
    ////////////////// ESPN ////////////////
    
    public function league($type) 
    {
        $standings = array();    
        $teams = array();
        
        if($type == 'en')
        $page = file_get_html('http://espnfc.com/tables/_/league/eng.1/english-premier-league?cc=3888');    
        
        else if($type == 'sp')
        $page = file_get_html('http://espnfc.com/tables/_/league/esp.1/spanish-la-liga?cc=3888');
        
        else if($type == 'it')
        $page = file_get_html('http://espnfc.com/tables/_/league/ita.1/ita-serie-a?cc=3888');   
        
        else if($type == 'gr')
        $page = file_get_html('http://espnfc.com/tables/_/league/ger.1/german-bundesliga?cc=3888'); 
        
        else if($type == 'fr')
        $page = file_get_html('http://espnfc.com/tables/_/league/fra.1/french-ligue-1?cc=3888'); 
        
        else if($type == 'pr')
        $page = file_get_html('http://espnfc.com/tables/_/league/por.1/portuguese-liga?cc=3888'); 
        
        
        $temp = $this->teams_model->get_team($type);
            
        foreach ($temp as $key) {
            $n = str_replace(' ', "", $key['en_name'])   ; 
            $teams[$n] = $key['ar_name'];
        }
        
        $standings = $this->teams_model->get_standing($page,$teams);
        echo json_encode($standings);
        //print_r($standings);die;
    }

}
?>