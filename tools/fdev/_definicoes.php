<?php 
	$conn = null;
	include_once("../../config/conexao.php");
 	$_SESSION['project-lite'] = 'Default';
	$_SESSION['servername'] = DB_HOST;
	$_SESSION['databasename'] = DB_NAME;
	$_SESSION['username'] = DB_USER;
	$_SESSION['password'] = DB_PASSWORD;
	$_SESSION['sgbd'] = DB_SGBD;	
?>