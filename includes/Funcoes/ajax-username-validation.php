<?


if (@$_REQUEST['action'] == 'check_username' && isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
    echo json_encode(check_username($_REQUEST['username'], $_REQUEST['table'], $_REQUEST['field']));
    exit; // only print out the json version of the response
} else {
    echo json_encode(check_exist_username($_REQUEST['username'], $_REQUEST['userAntigo']));
    exit; // only print out the json version of the response
}

function check_username($username) {
  $username = trim($username); // strip any white space
  $response = array(); // our response

  // if the username is blank
  if (!$username) {
  $response = array(
    'ok' => false, 
    'msg' => "1");
    
  // if the username does not match a-z or '.', '-', '_' then it's not valid
  } else if (!preg_match('/^[a-zA-Z0-9]+$/', $username)) {
  $response = array(
    'ok' => false, 
    'msg' => "2");
    
  // it's all good
  } else {
  $response = array(
    'ok' => true, 
    'msg' => "Ok");
  }

  return $response;        
}

function check_exist_username($username, $userAntigo) {
  $username = trim($username); // strip any white space
  $response = array(); // our response

  // if the username is blank
  if (!$username) {
  $response = array(
    'ok' => false, 
    'msg' => "1");
    
  // if the username does not match a-z or '.', '-', '_' then it's not valid
  } else if (!preg_match('/^[a-z0-9]+$/', $username)) {
  $response = array(
    'ok' => false, 
    'msg' => "2");
    
  // this would live in an external library just to check if the username is taken
  } else if (check_username_bd($username)) {
      if($username==$userAntigo){
          $response = array(
          'ok' => true, 
          'msg' => "4");
      }else{
          $response = array(
          'ok' => false, 
          'msg' => "3");
      }
    
  // it's all good
  } else {
  $response = array(
    'ok' => true, 
    'msg' => "4");
  }

  return $response;        
}

function check_username_bd($username) {
  include("../../connect.php");
  $sql = CRUD::SELECT('', 'gerencie_login', 'login=:username', array('username'=>$username), ''); 
  $total_reg = sizeof($sql);
  if($total_reg>0) return true; else return false;
}