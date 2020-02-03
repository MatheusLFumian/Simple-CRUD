<?php 
include("../../connect.php");

$action 				= $_POST['action']; 
$tabela 				= $_POST['tabela']; 
$updateRecordsArray 	= $_POST['recordsArray'];


if ($action == "updateRecordsListings"){
	
	$listingCounter = 1;
	foreach ($updateRecordsArray as $recordIDValue) {
		
		if(CRUD::UPDATE($tabela, array('ordem'=>$listingCounter),$recordIDValue)) {
			$listingCounter = $listingCounter + 1;
		}else{
			die('Error, insert query failed');
		}
	}
	
	echo '<pre>';
	print_r($updateRecordsArray);
	echo '</pre>';
	echo 'If you refresh the page, you will see that records will stay just as you modified.';
}

?>