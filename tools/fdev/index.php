<?php 
	session_start();
	$pag = 1;
	$titulo = "FDev (Lite) - Fast Development in PHP and Laravel";
	include_once ("_cabecalho.php");
?>

<!------------------------- Código do script ------------------------>

<p class="lead">Uma ferramenta rápida, prática e eficiente para gerar código CRUD em PHP e Laravel.</p>
<p><b>Você está usando a versão Lite do FDev associada apenas a este projeto PHP. Para utilizar a versão completa que permite trabalhar com vários projetos simultaneamente e gerar scripts tanto para o PHP quanto para o Laravel, acesse o github no endereço https://github.com/lqbrito/FDev.</b></p>
<p class="text-danger"><b>Por questões de segurança não é aconselhável fazer upload da pasta do FDev (Lite) para o servidor de produção.</b></p>

<div class="card">
 	<div class="card-header">
    	Créditos de desenvolvimento
 	</div>
  
 	<div class="card-body">
 		<div class="row">
 			<div class="col-lg-12">
 				<dl class="row">
 					<dt class="col-sm-2 text-right">Desenvolvedor</dt>
 					<dd class="col-sm-10">Luciano Brito Querido</dd>
 					<dt class="col-sm-2 text-right">Contato</dt>
 					<dd class="col-sm-10">+55 (62) 98258-1888</dd>
 					<dt class="col-sm-2 text-right">Email</dt>
 					<dd class="col-sm-10">lqbrito@gmail.com</dd>
 					<dt class="col-sm-2 text-right">Instagram</dt>
 					<dd class="col-sm-10"> @luciano.lbq</dd>
 				</dl>
 			</div>
 		</div>
 	</div>
</div>


<!------------------------------------------------------------------->

<?php 
 	include_once ("_rodape.php");
?>