<?php
class Search extends MY_Controller {
	public function __construct() {
		parent::__construct();
	}
	public function test() {
		echo "test function in Search<head><title>HTML Reference</title></head>";
	}
	public function search() {
		echo "<form  method='post' action='/comp4350/index.php/Search/validateSearch'  id=searchToken>";
		echo "<input  type='text' name='searchValue'>";
		echo "<input type='submit' name='submit' value='Search'>";
		echo "</form>";
		
	}
	public function validateSearch()
    {
		$form_data = $this->input->post();
		
		$searchValue = $this->input->post("searchValue");
		$url = 'http://54.200.14.217/?/Beer/search';
		$data = array('searchToken' => "$searchValue");
		
		$options = array(
				'http' => array(
						'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
						'method'  => 'POST',
						'content' => http_build_query($data),
				),
		);
		$context  = stream_context_create($options);
		$result = file_get_contents($url, false, $context);
		$searchResults = json_decode($result);
		echo "<table border=1>Matches Name<tr><th>Name</th><th>type</th></tr>";
		if(array_key_exists('nameMatches', $searchResults)) {
			echo "<tr><td colspan='2'><span style='font-weight:bold'>Matches Name</span></td></tr>";
			foreach ($searchResults->nameMatches as $data) {
				if (!is_null($data)) {
					echo "<tr><td>$data->Name</td>";
					echo "<td>$data->Type</td></tr>";
				}
			}
		}
		if(array_key_exists('typeMatches', $searchResults)) {
			echo "<tr><td colspan='2'><span style='font-weight:bold'>Matches Type</span></td></tr>";
			foreach ($searchResults->typeMatches as $typeData) {
				if(!is_null($typeData)) {
					echo "<tr><td>$typeData->Name</td>";
					echo "<td>$typeData->Type</td></tr>";			
				}
			}
		}
		if(array_key_exists('breweryMatches', $searchResults)) {
			echo "<tr><td colspan='2'><span style='font-weight:bold'>Matches Brewery</span></td></tr>";
			foreach ($searchResults->breweryMatches as $breweryData) {
				if(!is_null($breweryData)) {
					echo "<tr><td>$breweryData->Name</td>";
					echo "<td>$breweryData->Type</td></tr>";
				}
			}
		}
    }
}
?>