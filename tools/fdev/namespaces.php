<?php 
	session_start();
	$pag = 3;
	$titulo = "FDev (Lite) - Criação das namespaces do projeto";
	include_once ("_cabecalho.php");
?>

<!------------------------- Código do script ------------------------>

<?php
	require ("_definicoes.php");

	if (isset($_SESSION['servername']))
		$servername = $_SESSION['servername'];
	else
		$servername = 'localhost';
	
	if (isset($_SESSION['databasename']))
		$databasename = $_SESSION['databasename'];
	else
		$databasename = '';
	
	if (isset($_SESSION['username']))
		$username = $_SESSION['username'];
	else
		$username = 'root';
	
	if (isset($_SESSION['password']))
		$password = $_SESSION['password'];
	else
		$password = '';
	
	if (isset($_SESSION['sgbd']))
		$sgbd = $_SESSION['sgbd'];
	else
		$sgbd = 'mysql';

	if (isset($_POST['criar']))
	{
		try 
		{
			$dir = 'projects' . DIRECTORY_SEPARATOR . $_SESSION['project-lite'] . DIRECTORY_SEPARATOR . 'namespaces' . DIRECTORY_SEPARATOR . $_POST['nomenamespace'];
			
			if(!is_dir($dir))
			{
				mkdir($dir);
				echo "<div class='alert alert-info alert-dismissible'>";
		        echo "<button type='button' class='close' data-dismiss='alert' aria-hIDden='true'>×</button>";
		        echo "<h4><i class='icon fa fa-info'></i> Aviso!</h4>";
		        echo 'Namespace "' . $_POST['nomenamespace'] . '" criada com sucesso';
		        echo "</div>";
			}
			else
			{
				echo "<div class='alert alert-danger alert-dismissible'>";
		        echo "<button type='button' class='close' data-dismiss='alert' aria-hIDden='true'>×</button>";
		        echo "<h4><i class='icon fa fa-info'></i> Aviso!</h4>";
		        echo 'Namespace "' . $_POST['nomenamespace'] . '" Já existe';
		        echo "</div>";
			}
		}
		catch(Exception $e) 
		{
			echo "<div class='alert alert-danger alert-dismissible'>";
	        echo "<button type='button' class='close' data-dismiss='alert' aria-hIDden='true'>×</button>";
	        echo "<h4><i class='icon fa fa-info'></i> Erro!</h4>";
	        echo $e->getMessage();
	        echo "</div>";
		}
	}

	if (isset($_POST['associar']))
	{
		$diretorio = 'projects' . DIRECTORY_SEPARATOR . $_SESSION['project-lite'] . DIRECTORY_SEPARATOR . 'namespaces' . DIRECTORY_SEPARATOR . $_POST['namespace'];
		$cont = 0;
		foreach ($_POST['tablesselect'] as $arq)
		{
			$arquivo = $diretorio . DIRECTORY_SEPARATOR . $arq;
			mkdir($arquivo);
			$cont++;
		}
		echo "<div class='alert alert-info alert-dismissible'>";
        echo "<button type='button' class='close' data-dismiss='alert' aria-hIDden='true'>×</button>";
        echo "<h4><i class='icon fa fa-info'></i> Aviso!</h4>";
        echo 'Foram adicionadas ' . $cont . ' tabelas à namespace com sucesso';
        echo "</div>";
	}

	echo "<p>Associe as tabelas do banco de dados às suas namespaces para que os scripts de Controllers e Views sejam gerados nas namespaces corretas.</p>";

	try
	{
		$pastas = scandir('projects' . DIRECTORY_SEPARATOR . $_SESSION['project-lite'] . DIRECTORY_SEPARATOR . 'namespaces');
		$tabelas = array();
		foreach ($pastas as $tp)
		{
			if ($tp != '.' && $tp != '..')
			{
				$estrutura = 'projects' . DIRECTORY_SEPARATOR . $_SESSION['project-lite'] . DIRECTORY_SEPARATOR . 'namespaces' . DIRECTORY_SEPARATOR . $tp;
				$subpastas = scandir($estrutura);
				foreach ($subpastas as $sub)
					if ($sub != '.' && $sub != '..')
						$tabelas[] = $sub;
			}		
		}

		if (isset($conn))
			$conn->close();
		$conn = new PDO("$sgbd:host=$servername;dbname=$databasename", $username, $password);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$stmt = $conn->prepare("SHOW TABLES");
	  	$stmt->execute();
	  	if ($stmt->setFetchMode(PDO::FETCH_ASSOC))
	  		$result = $stmt->fetchAll();
	  	$conn = null;
		
	}
	catch (\Exception $e) 
	{
		
	}
	
	// $pastas contém os nomes das namespaces
	// $result contém os nomes das tabelas do banco de dados
	// $tabelas contém os nomes das tabelas do banco de dados já associadas a alguma namespace
?>

<div class="row mb-3">
	<div class="col-md-4">
		<div class="card">
		 	<div class="card-header">
		    	Namespaces / Tabelas do banco de dados
		 	</div>
		  
		  	<form action = "namespaces.php" method = "post">
				<div class="card-body">			    
			    	<div class="form-row">
			    		<div class="col-md-12">
							<select class="form-control" id="sgbd" name="namespace" required>
								<?php
									foreach ($pastas as $tp)
										if ($tp != '.' && $tp != '..')
											echo "<option value='$tp'>$tp</option>";
								?>
							</select>
						</div>
						<div class="col-md-12">
							<br>
							<select class="form-control" multiple id="filesdelete" name="tablesselect[]" required style="min-height : 200px;">
								<?php
									for ($i = 0; $i < count($result); $i++)
										foreach ($result[$i] as $tp)
										{
											$jaexiste = false;
											foreach($tabelas as $tab)
												if ($tp == $tab)
													$jaexiste = true;
											if (!$jaexiste)
												echo "<option value='$tp'>$tp</option>";					
										}
								?>
							</select>
						</div>						
					</div>
			  	</div>
			  	
				<div class="card-footer">
			    	<button class="btn btn-primary btn-block" type="submit" name="associar"><i class="fas fa-tasks"></i> Associar tabelas à namespace</button>
			 	</div>
		  
			</form>
		</div>
	</div>

	<div class="col-md-4">
		<div class="card">
	 		<div class="card-header">
		    	Estrutura das namespaces
		 	</div>
	  		<div class="card-body">			    
	  			<ul>
	  				<?php
	  					foreach ($pastas as $tp)
	  						if ($tp != '.' && $tp != '..')
	  						{
	  							$estrutura = 'projects' . DIRECTORY_SEPARATOR . $_SESSION['project-lite'] . DIRECTORY_SEPARATOR . 'namespaces' . DIRECTORY_SEPARATOR . $tp;
	  							$subpastas = scandir($estrutura);

	  							echo "<form action='__excluinamespaces.php' method='post'>";
	  							echo "<input type = 'hidden' name = 'nomenamespace' value = '" . $tp . "'>";
	  							echo "<li>$tp ";
	  							echo "<button type='submit' name='excluinamespace' class='btn btn-sm btn-link'><small><i class='fas fa-trash'></i></small></button>";
	  							echo "</li>";
	  							echo "</form>";

	  							if (count($subpastas) > 2)
	  							{
	  								echo "<ul class = 'list-unstyled'>";
	  								foreach ($subpastas as $sub)
										if ($sub != '.' && $sub != '..')
										{
											echo "<form action='__excluinamespaces.php' method='post'>";
				  							echo "<input type = 'hidden' name = 'nomenamespace' value = '" . $tp . "'>";
	  										echo "<input type = 'hidden' name = 'nometabela' value = '" . $sub . "'>";
				  							echo "<li>$sub ";
				  							echo "<button type='submit' name='excluitabela' class='btn btn-sm btn-link'><small><i class='fas fa-trash'></i></small></button>";
				  							echo "</li>";
				  							echo "</form>";
										}
	  								echo "</ul>";
	  							}
	  						}
	  				?>
	  			</ul>
		  	</div>
			<form action = "__excluinamespaces.php" method = "post">					  	
				<div class="card-footer">
			    	<button class="btn btn-danger btn-block" type="submit" name="limpar"><i class="fas fa-eraser"></i> Limpar estrutura de namespaces</button>
			 	</div>
			</form>
		</div>
	</div>

	<div class="col-md-4">
		<div class="card">
		 	<div class="card-header">
		    	Nova namespace para o projeto
		 	</div>
		  
		  	<form action = "namespaces.php" method = "post" enctype="multipart/form-data">
				<div class="card-body">			    
			    	<div class="form-row">
						<div class="col-md-12">
							<label for="nomenamespace">Nome da namespace</label>
							 <input type="text" class="form-control" id="nomenamespace" name="nomenamespace"required>
						</div>
					</div>
			  	</div>
			  	
				<div class="card-footer">
			    	<button class="btn btn-primary btn-block" type="submit" name="criar"><i class="fas fa-folder"></i> Criar</button>
			 	</div>
		  
			</form>

		</div>
	</div>
</div>

<!------------------------------------------------------------------->

<?php 
	include_once ("_rodape.php");
?>