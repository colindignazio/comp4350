<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class New_search extends MY_Controller {
    public function __construct() {
        parent::__construct();
    }
    public function search(){

        // Load required models
        //$this->load->model('search_m');

        // Read the from DB
       // $data['news'] = $this->news_m->read();
        $title['title'] = "Search";
        // Create the views
        //$this->load->view('templates/header', $title);
        $this->load->view('search/searchbox');

        if(isset($_POST['searchValue'])){ //check if form was submitted
            $input = $_POST['searchValue']; //get input text
             
            $data = $this->getResults($input);
            
            if($data['status'] == 200){
                
                if(array_key_exists("details", $data)){
                    echo $data['details'];
                } else {
                    //var_dump($data);
                    $resultsData = array();
                    
                    if(isset($data['status'])){
                        $resultsData['status'] =  $data['status'];
                    }
                    if(isset($data['nameMatches'])){
                        $resultsData['nameMatches'] =  $data['nameMatches'];
                    }
                    if(isset($data['breweryMatches'])){
                        $resultsData['breweryMatches'] =  $data['breweryMatches'];
                    }
                    if(isset($data['typeMatches'])){
                        $resultsData['typeMatches'] =  $data['typeMatches'];
                    }            

                    $this->load->view('search/results', $resultsData);
                }
                
            } else {
                if(array_key_exists("details", $data)){
                    echo $data['details'];
                } else {
                    echo "unknown error";
                }
            }
            
        }
        //$this->load->view('news/list', $data);

        //$this->load->view('news/arbitrary');
        
    } 

    public function getResults($searchValue){
        
        $url = 'http://54.200.14.217/?/Beer/search';
        //$url = 'http://localhost/?/Beer/search';
        $data = array('searchToken' => "$searchValue");
        
        $options = array(
                'http' => array(
                        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                        'method'  => 'POST',
                        'content' => http_build_query($data),
                    ),
                );

        $context  = stream_context_create($options);
        $result = file_get_contents($url, false, $context); // call url
        $searchResults = json_decode($result, true); // results from url call
        
        return $searchResults;
        
    }                
}
?>