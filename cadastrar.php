<?php 
include ('connect.php');
$page = 'Cadastrar';

$edit = isset($_GET['edit']) ? $_GET['edit'] : "";
$id = isset($_GET['id']) ? $_GET['id'] : "";


?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include ('_head.php'); ?>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.11/jquery.mask.min.js"></script>
	<script type="text/javascript">
		$("#cpf").mask("000.000.000.00");
		$("#estado").mask("AA");
		$("#cep").mask("00000-000");
	</script>
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
						<h1>Cadastrar</h1>
					</div>
				</div>

				<div class="row">
					<div class="col-md-10">
						<form  action="<?=SITE_URL?>includes/f-cadastro.php" method="post">
							<div class="form-group row">
								<div class="col-md-10">
									<label for="nome">Nome: </label>
									<input class="inputc form-control" class="inputc form-control" type="text" placeholder="Nome completo"  name="nome" id="nome" value="<?=isset($_POST['nome'])?$_POST['nome']:""?>" required >
								</div>
								<div class="col-md-2">
									<label for="id">ID:</label>
									<input class="inputc form-control" type="text" name="idE" value="<?=$id?>" placeholder="<?=$id!="" ? $id : "ID - automático"?>" readonly>
								</div>
							</div>
							<div class="form-group">
								<label for="nome">Email: </label>
								<input class="inputc form-control" class="inputc form-control" type="text" placeholder="Seu endereço de e-mail: example@example.com"  name="email" id="email" value="<?=isset($_POST['email'])?$_POST['email']:""?>" >
							</div>
							<!-- Endereço -->
							<div class="form-group row">
								<div class="col-md-9">
									<label for="cpf">Rua: </label>
									<input class="inputc form-control" type="text" placeholder="Rua"  name="endereco" id="endereco" value="<?=isset($_POST['rua'])?$_POST['rua']:""?>"  >
								</div>
								<div class="col-md-3">
									<label for="empresa">n°: </label>
									<input class="inputc form-control" type="text" placeholder="n°"  name="numero" id="numero" value="<?=isset($_POST['numero'])?$_POST['numero']:""?>"  >
								</div>
							</div>
							<div class="form-group row">
								<div class="col-md-4">
									<label for="cnpj">Bairro: </label>
									<input class="inputc form-control" type="text" placeholder="Bairro"  name="bairro" id="bairro" value="<?=isset($_POST['bairro'])?$_POST['bairro']:""?>">
								</div>
								<div class="col-md-4">
									<label for="cnpj">Cidade: </label>
									<input class="inputc form-control" type="text" placeholder="Cidade"  name="cidade" id="cidade" value="<?=isset($_POST['cidade'])?$_POST['cidade']:""?>">
								</div>
								<div class="col-md-2">
									<label for="cnpj">CEP: </label>
									<input class="inputc form-control" type="text" placeholder="CEP"  name="cep" id="cep" value="<?=isset($_POST['cep'])?$_POST['cep']:""?>" pattern="\[A-Z]{2}\" >
								</div>
								<div class="col-md-2">
									<label for="cnpj">Estado: </label>
									<input class="inputc form-control" type="text" placeholder="UF"  name="estado" id="estado" value="<?=isset($_POST['estado'])?$_POST['estado']:""?>" pattern="\[A-Z]{2}\" >
								</div>
							</div>
							<div class="form-group">
								<label for="cpf">CPF: </label>
								<input class="inputc form-control" type="text" placeholder="Informe seu CPF" required name="cpf" id="cpf" value="<?=isset($_POST['cpf'])?$_POST['cpf']:""?>"  >

								<input type="hidden" name="inserir" value="true">
							</div>
							
							<div class="form-group d-flex flex-row-reverse bd-highlight ">
								<?if ($edit=="editar") { ?>
									<input type="hidden" name="acao" value="editar">
									<button type="submit" class="btn btn-outline-success">Editar</button>
								<? } else{ ?>
									
									<button type="submit" class="btn btn-outline-primary">Enviar</button>
									<?} ?>
								</div>

							</form>
						</div>
					</div>
				</div>
			</div>
		</div>

	</body>
	</html>