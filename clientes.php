<?php 
include ('connect.php');
$page = 'Clientes';

$clientes = CRUD::SELECT('','cliente','','','');
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
				<div class="row">
					<div class="col-md-12 d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
						<h1>Cliente</h1>
					</div>
				</div>

				<div class="row">
					<div class="col-md-12 d-flex justify-content-between flex-row-reverse flex-wrap flex-md-nowrap pt-3 pb-2 mb-3 border-bottom">
						<a href="<?=SITE_URL?>cadastrar.php" target="_blank"><span class="align-middle">Cadastrar  <i class="fas fa-plus"></i></span></a>
					</div>
				</div>

				<div class="table-responsive-sm table-hover">
					<table class="table table-hover table-borderless" style="border-spacing: 0px;">
						<thead class="thead">
							<tr>
								<th scope="col">ID</th>
								<th scope="col">Nome</th>
								<th scope="col">Email</th>
								<th scope="col">Endereço</th>
								<th scope="col">CPF</th>
								<th scope="col"></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($clientes as $key => $c) { ?>
								<tr>
									<td scope="row"><?=$c['id']?></td>
									<td scope="row"><?=$c['nome']?></td>
									<td scope="row"><?=$c['email']?></td>
									<td scope="row"><?=$c['endereco'].','.$c['numero'].','.$c['cidade'].'-'.$c['uf']?></td>
									<td scope="row"><?=$c['cpf']?></td>
									<td scope="row"><a href="<?=SITE_URL?>cadastrar.php?edit=editar&id=<?=$c['id']?>"><button class="btn btn-primary">Editar</button></a>  <a href="<?=SITE_URL?>includes/f-cadastro.php?deletar=exclude&idR=<?=$c['id']?>"><button class="btn btn-danger">Excluir</button></a></td>
							</tr>
							<? } ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>

</body>
</html>