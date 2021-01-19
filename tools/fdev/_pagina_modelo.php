<?php 
	session_start();
	$pag = 8;
	$titulo = "FDev - Título do formulário";
	include_once ("_cabecalho.php");
?>

<!------------------------- Código do script ------------------------>

<?php
	echo "<p>";
	echo "Projeto <b>" . $_SESSION['project-lite'] . "</b> selecionado na área de trabalho. ";
	if (isset($_SESSION['databasename']))
		echo "Conectado ao banco de dados <b>" . $_SESSION['databasename'] . ".</b>";
	else
		echo "Nenhuma conexão com banco de dados ativa no momento.";
	echo "</p>";
?>

<!------------------------------------------------------------------->      

<?php 
	include_once ("_rodape.php");
?>