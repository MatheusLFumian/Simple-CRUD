<?
// if(!isset($_SESSION)) session_start(); 
// header('Content-Type: text/html; charset=utf-8');
//minutos que a sessao fica ativa
$minutos = 60;

$file = explode("/", $_SERVER['PHP_SELF']); 
//var_dump($file);

if(!isset($_SESSION[NOME_SESSAO])){
    if(sizeof($file)==4){ include("../index.php"); die();}
    else{ include("../index.php"); die();}
}else if( (time() - $_SESSION['time']) > $minutos*60) {
	unset($_SESSION[NOME_SESSAO]); print "<script>window.alert('Seu tempo acabou, faça login novamente');</script>";
    if(sizeof($file)==4){ include("index.php"); die();}
    else{ include("../index.php"); die();}
} else {
    $acao = isset($_GET["acao"]) ? $_GET["acao"] : "" ;
    if ($acao=='sair') {
        unset($_SESSION[NOME_SESSAO]); //print "<script>window.alert('Logoff efetuado com sucesso.');</script>";
        print "<script>window.location='".SITE_URL."gerencie/index.php';</script>";
    }
}

$nomeU = $_SESSION[NOME_SESSAO]['nome'];
$idU = $_SESSION[NOME_SESSAO]['id'];

?>