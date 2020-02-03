<?php 
include ('connect.php');
$page = 'Dashboard';

$cont_clientes = CRUD::SELECT('COUNT(*) AS idc','cliente','','','');
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include ('_head.php'); ?>
</head>
<body>

<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<?php include('_top.php'); ?>
		</div>
	</div>

	<div class="row mt-5">
		<div class="col-md-2 bgNav">
			<?php include('_nav.php'); ?>
		</div>

		<div class="col-md-10">
			<h1>Cadastrar</h1>
			<br>
			<h3>Total de clientes: <?=$cont_clientes[0]['idc']?></h3>
		</div>
	</div>
</div>
	
</body>
</html>