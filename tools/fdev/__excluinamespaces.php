<?php
	session_start();
	require ("_definicoes.php");

	function delTree($dir) 
	{ 
		$files = array_diff(scandir($dir), array('.','..')); 
		foreach ($files as $file) 
		{ 
			(is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file"); 
		} 
		return rmdir($dir); 
	}
	
	if (isset($_POST['limpar']))
	{
		$dirRaiz = 'projects' . DIRECTORY_SEPARATOR . $_SESSION['project-lite'] . DIRECTORY_SEPARATOR . 'namespaces';
		$pastasLimpar = scandir($dirRaiz);
		$cont = 0;
		$operacaoOk = true;
		foreach ($pastasLimpar as $tp)
			if ($tp != '.' && $tp != '..')
			{
				$dirApagar = $dirRaiz . DIRECTORY_SEPARATOR . $tp;
				try
				{
					if (delTree($dirApagar))
						$cont++;
					else
						$operacaoOk = false;
				}
				catch (\Exception $e) 
				{
					
				}
			}
		if ($operacaoOk)
		{
			echo "<div class='alert alert-info alert-dismissible'>";
	        echo "<button type='button' class='close' data-dismiss='alert' aria-hIDden='true'>×</button>";
	        echo "<h4><i class='icon fa fa-info'></i> Aviso!</h4>";
	        echo 'Estrutura de namespaces limpa com sucesso. Foram excluídas ' . $cont . ' namespaces';
	        echo "</div>";
		}
		else
		{
			echo "<div class='alert alert-danger alert-dismissible'>";
	        echo "<button type='button' class='close' data-dismiss='alert' aria-hIDden='true'>×</button>";
	        echo "<h4><i class='icon fa fa-info'></i> Erro!</h4>";
	        echo 'Falha ao limpar a estrutura de namespaces. Foram excluídas ' . $cont . ' namespaces';
	        echo "</div>";
		}
	}

	if (isset($_POST['excluinamespace']))
	{
		$arquivo = 'projects' . DIRECTORY_SEPARATOR . $_SESSION['project-lite'] . DIRECTORY_SEPARATOR . 'namespaces' . DIRECTORY_SEPARATOR . $_POST['nomenamespace'];
		if (delTree($arquivo))
		{
			echo "<div class='alert alert-info alert-dismissible'>";
	        echo "<button type='button' class='close' data-dismiss='alert' aria-hIDden='true'>×</button>";
	        echo "<h4><i class='icon fa fa-info'></i> Aviso!</h4>";
	        echo 'Namespace ' . $_POST['nomenamespace'] . ' excluída com sucesso';
	        echo "</div>";
		}
		else
		{
			echo "<div class='alert alert-danger alert-dismissible'>";
	        echo "<button type='button' class='close' data-dismiss='alert' aria-hIDden='true'>×</button>";
	        echo "<h4><i class='icon fa fa-info'></i> Erro!</h4>";
	        echo 'Falha ao excluir a namespace ' . $_POST['nomenamespace'];
	        echo "</div>";
		}
	}

	if (isset($_POST['excluitabela']))
	{
		$arquivo = 'projects' . DIRECTORY_SEPARATOR . $_SESSION['project-lite'] . DIRECTORY_SEPARATOR . 'namespaces' . DIRECTORY_SEPARATOR . $_POST['nomenamespace'] . DIRECTORY_SEPARATOR . $_POST['nometabela'];
		if (rmdir($arquivo))
		{
			echo "<div class='alert alert-info alert-dismissible'>";
	        echo "<button type='button' class='close' data-dismiss='alert' aria-hIDden='true'>×</button>";
	        echo "<h4><i class='icon fa fa-info'></i> Aviso!</h4>";
	        echo 'Tabela ' . $_POST['nomenamespace'] . DIRECTORY_SEPARATOR . $_POST['nometabela'] . ' excluída da namespace com sucesso';
	        echo "</div>";
		}
		else
		{
			echo "<div class='alert alert-danger alert-dismissible'>";
	        echo "<button type='button' class='close' data-dismiss='alert' aria-hIDden='true'>×</button>";
	        echo "<h4><i class='icon fa fa-info'></i> Erro!</h4>";
	        echo 'Falha ao excluir a tabela ' . $_POST['nomenamespace'] . DIRECTORY_SEPARATOR . $_POST['nometabela'] . ' da namespace';
	        echo "</div>";
		}
	}
	
	header ("location:namespaces.php");	
?>