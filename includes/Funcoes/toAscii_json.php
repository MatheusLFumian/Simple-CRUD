<?
header('Content-type: application/json');

include('Funcoes.php'); 

$arr = array ();

//var_dump($_GET);

if(isset($_GET)){
	foreach ($_GET as $key => $value) {
		$arr[$key] = Funcoes::toAscii($value);
	}

} else {
	$arr['status'] = 'error';  
}

//var_dump($arr);

echo json_encode($arr);

?>