<?
session_start();
include('../connect.php');


$file = SITE_URL.'clientes.php';
$tabela = 'cliente';

// Recebe alguns dos parametros que vão indicar a ação e em qual entrada da tabela, definida pelo id, ela será executada
$inserir = isset($_POST['inserir']) ? $_POST['inserir'] : "";
$acao = isset($_POST['acao']) ? $_POST['acao'] : "";
$idEd = isset($_POST['idE']) ? $_POST['idE'] : "";

// Recebe os valores preenchidos nos formulários de cadastramento ou edição para montar um array que será inserido na tabela
$nome = isset($_POST['nome']) ? $_POST['nome'] : "";
$email = isset($_POST['email']) ? $_POST['email'] : "";
$cpf = isset($_POST['cpf']) ? $_POST['cpf'] : "";
$endereco = isset($_POST['endereco']) ? $_POST['endereco'] : "";
$numero = isset($_POST['numero']) ? $_POST['numero'] : "";
$bairro = isset($_POST['bairro']) ? $_POST['bairro'] : "";
$cidade = isset($_POST['cidade']) ? $_POST['cidade'] : "";
$cep = isset($_POST['cep']) ? $_POST['cep'] : "";
$estado = isset($_POST['estado']) ? $_POST['estado'] : "";


// Analisa as condições de inserção e edição, cria um array com os valores recebidos e os insere na tabela

if ($inserir=='true') {
    if($acao=='editar'){
        $params = array('nome'=>$nome, 'email'=>$email, 'cpf'=>$cpf, 'endereco'=>$endereco, 'numero'=>$numero, 'bairro'=>$bairro, 'cidade'=>$cidade, 'cep'=>$cep, 'uf'=>$estado);
        if(CRUD::UPDATE($tabela, $params, $idEd));

        print "<script>window.location='$file';</script>";

    }else{
        $params = array('nome'=>$nome, 'email'=>$email, 'cpf'=>$cpf, 'endereco'=>$endereco, 'numero'=>$numero, 'bairro'=>$bairro, 'cidade'=>$cidade, 'cep'=>$cep, 'uf'=>$estado);

        if(CRUD::INSERT($tabela, $params));
        
        print "<script>window.location='$file';</script>";

    }
}


// Recebe a ação de deletar, faz sua verificação e realiza a ação no id indicado
$Deletar = isset($_GET['deletar']) ? $_GET['deletar'] : "";

if($Deletar=='exclude'){
    $idR = isset($_GET['idR'])?intval($_GET['idR']):0;

    if(CRUD::DELETE($tabela, $idR)) print "<script>window.location='$file?alert=Excluído';</script>";

}
?>