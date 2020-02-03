<?
	if ($_SESSION[NOME_SESSAO]['tipoUsuario']!='1') {
	    $area = CRUD::SELECT('id', 'gerencie_areasacesso', 'nomeArea=:area', array('area'=>$area), '');
	    $area = $area[0]['id'];

	    if (in_array($area, $_SESSION[NOME_SESSAO]['areasAcesso'])===false) {
	        print "<script>window.alert('Você não tem acesso à essa área! Dúvidas contate o Administrador.');</script>";
	        print "<script>window.location='".SITE_URL."gerencie/index.php';</script>";
	    }
	}
?>