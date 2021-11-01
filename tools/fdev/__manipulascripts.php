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
	
	function substitui($str_origem, $str_destino, $string)
	{		
		return str_replace($str_origem, $str_destino, $string);
	}

	function ObtemEstruturaTabela($tabela)
	{
		// Verificar se já existe um vetor com a estrutura da tabela para evitar um novo acesso ao BD sem necessidade
		if (isset($_SESSION["tabela"] [$tabela]))
			return;
		
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

		if (isset($conn))
				$conn->close();
		$conn = new PDO("$sgbd:host=$servername;dbname=$databasename", $username, $password);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$stmt = $conn->prepare("SHOW COLUMNS FROM $tabela");
	  	$stmt->execute();
	  	if ($stmt->setFetchMode(PDO::FETCH_ASSOC))
	  		$result = $stmt->fetchAll();
	  	$conn = null;

	  	// Preencher o vetor com a estrutura da tabela para evitar ler esta tabela do BD mais uma vez
	  	$_SESSION["tabela"] [$tabela] = $result;		
	}


	/************************************************************************************************************/

	function ProcessarScripts($dir, $dirlayouts, $dirnamespaces, $dircontrollers, $dirservices, $dirmodels, $dirdomains, $dirviews, $namespace, $tabela, $scriptDeLayout)
	{
		ObtemEstruturaTabela($tabela);

		$classetabela = ucfirst($tabela);

		$nomeArquivoLayout = $dirlayouts . DIRECTORY_SEPARATOR . $scriptDeLayout;

		if ($scriptDeLayout == "index.php" || 
			$scriptDeLayout == "incluir.php" || 
			$scriptDeLayout == "consultar.php" || 
			$scriptDeLayout == "alterar.php" || 
			$scriptDeLayout == "excluir.php" || 
			$scriptDeLayout == "index.blade.php" || 
			$scriptDeLayout == "incluir.blade.php" || 
			$scriptDeLayout == "consultar.blade.php" || 
			$scriptDeLayout == "alterar.blade.php" || 
			$scriptDeLayout == "excluir.blade.php" )
		{
			$nomeArquivoDestino = $dirviews . DIRECTORY_SEPARATOR . $namespace . DIRECTORY_SEPARATOR . $tabela . DIRECTORY_SEPARATOR . $scriptDeLayout;
			$dirnamespace = $dirviews . DIRECTORY_SEPARATOR . $namespace;
			$dir = $dirviews . DIRECTORY_SEPARATOR . $namespace . DIRECTORY_SEPARATOR . $tabela;
			// Cria o diretório da namespace dentro do diretório definido para as views
			if(!is_dir($dirnamespace))
				mkdir($dirnamespace);
			// Cria o diretório da tabela dentro da namespace para salvar os templates das views
			if(!is_dir($dir))
				mkdir($dir);
		}
		
		if ($scriptDeLayout == "Controller.php")
		{
			if ($_POST['fonte'] == 'laravel')
				$nomeArquivoDestino = $dircontrollers . DIRECTORY_SEPARATOR . $namespace . DIRECTORY_SEPARATOR . $classetabela . $scriptDeLayout;
			if ($_POST['fonte'] == 'php')
				$nomeArquivoDestino = $dircontrollers . DIRECTORY_SEPARATOR . $namespace . DIRECTORY_SEPARATOR . $tabela . '.php';
			$dir = $dircontrollers . DIRECTORY_SEPARATOR . $namespace;
			// Cria o diretório da namespace dentro do diretório definido para os controllers
			if(!is_dir($dir))
				mkdir($dir);
		}
		
		if ($scriptDeLayout == "Service.php")
		{
			$nomeArquivoDestino = $dirservices . DIRECTORY_SEPARATOR . $classetabela . $scriptDeLayout;
		}
		
		if ($scriptDeLayout == "Model.php")
		{
			$nomeArquivoDestino = $dirmodels . DIRECTORY_SEPARATOR . $classetabela . '.php';
		}
		
		if ($scriptDeLayout == "Domain.php")
		{
			$nomeArquivoDestino = $dirdomains . DIRECTORY_SEPARATOR . $classetabela . '.php';
		}
		
		$arquivoOrigem = fopen ($nomeArquivoLayout, 'r'); 	// Abre arquivo de origem
		$arquivoDestino = fopen ($nomeArquivoDestino, 'w'); // Cria arquivo de destino

		while(!feof($arquivoOrigem))
		{
			$linha = fgets($arquivoOrigem, 1024);

			$linha = substitui('[namespace]', $namespace, $linha);
			$linha = substitui('[nome_controller]', $tabela, $linha);
			$linha = substitui('[nome_service]', $tabela, $linha);
			$linha = substitui('[nome_model]', $tabela, $linha);
			$linha = substitui('[nome_classe_controller]', $classetabela, $linha);
			$linha = substitui('[nome_classe_service]', $classetabela, $linha);
			$linha = substitui('[nome_classe_model]', $classetabela, $linha);
			
			$posini1 = strpos($linha, "[nome_campo]");
			$posini2 = strpos($linha, "[nome_label]");

			if ($posini1 > 0)
			{
				if ($scriptDeLayout == "index.blade.php" || $scriptDeLayout == "index.php")
				{
					foreach ($_SESSION["tabela"] [$tabela] as $tab)
					{
						$novaLinha = $linha;
						$st = $tab['Field'];
						if ($_POST['fonte'] == 'laravel')
							$novaLinha = substitui('[nome_campo]', "<td>{{\$tp->$st}}</td>", $novaLinha);
						if ($_POST['fonte'] == 'php')
							$novaLinha = substitui('[nome_campo]', "<td><?php echo \$tp['$st']; ?></td>", $novaLinha);
						fwrite($arquivoDestino, $novaLinha);
					}
				} 

				/*
				if ($scriptDeLayout == "incluir.blade.php" || $scriptDeLayout == "incluir.php")
				{
					$cont = 0;

					foreach ($_SESSION["tabela"] [$tabela] as $tab)
					{
						if ($tab['Field'] != 'id' && $tab['Field'] != 'created_at' && $tab['Field'] != 'updated_at')
						{
							$type = $tab['Type'];
							$pos1 = strpos($type, "varchar(");
							$pos2 = strpos($type, ")");

							$tam = 0;
							$tamanhoInput = "";

							if ($pos1 === 0) 
							{
								$tamanhoInput = substr($type, $pos1 + 8, $pos2 - 8);
							}

							$st = $tab['Field'];
							$cont++;
							                            
		                    $novaLinha = $linha;
							$novaLinha = substitui('[nome_campo]', "<div class='row'>", $novaLinha);
							fwrite($arquivoDestino, $novaLinha);    
							
							$novaLinha = $linha;
							$novaLinha = substitui('[nome_campo]', "\t<div class='form-group col-6'>", $novaLinha);
							fwrite($arquivoDestino, $novaLinha);    
							
							$novaLinha = $linha;
							$novaLinha = substitui('[nome_campo]', "\t\t<label class='bmd-label-floating' for='$st'>$st</label>", $novaLinha);
							fwrite($arquivoDestino, $novaLinha);    
							
							$novaLinha = $linha;
							$required = $tab['Null'] == "NO" ? " required='true'" : "";
							$autofocus = $cont == 1 ? " autofocus" : "";
							if ($tamanhoInput != "")
								$maxlength = " maxlength='$tamanhoInput'";
							else
								$maxlength = "";
							$type = "text";
							if ($tab['Type'] == "date" || $tab['Type'] == "time")
							{
								$type = $tab['Type'];
								if ($_POST['fonte'] == 'laravel')
									$novaLinha = substitui('[nome_campo]', "\t\t<input type='$type' class='form-control' id='$st' name='$st'$maxlength$required$autofocus>", $novaLinha);
								if ($_POST['fonte'] == 'php')
								{
									$novaLinha = substitui('[nome_campo]', "\t\t<input type='$type' class='form-control' id='$st' name='$st'$maxlength$required$autofocus value=\"<?php echo \$[nome_model]['$st']; ?>\">", $novaLinha);
									$novaLinha = substitui('[nome_model]', $tabela, $novaLinha);
								}
							}
							else
								if ($tab['Type'] == "text")
								{
									if ($_POST['fonte'] == 'laravel')
										$novaLinha = substitui('[nome_campo]', "\t\t<textarea class='form-control' rows='5' id='$st' name='$st'></textarea>", $novaLinha);
									if ($_POST['fonte'] == 'php')
									{
										$novaLinha = substitui('[nome_campo]', "\t\t<textarea class='form-control' rows='5' id='$st' name='$st'><?php echo \$[nome_model]['$st']; ?></textarea>", $novaLinha);
										$novaLinha = substitui('[nome_model]', $tabela, $novaLinha);
									}
								}
								else
								{
									$pos1 = strpos($tab['Type'], "int") === 0;
									$pos2 = strpos($tab['Type'], "bigint") === 0;
									if ($pos1 || $pos2)
										$type = "number";
									if ($_POST['fonte'] == 'laravel')
										$novaLinha = substitui('[nome_campo]', "\t\t<input type='$type' class='form-control' id='$st' name='$st'$maxlength$required$autofocus>", $novaLinha);
									if ($_POST['fonte'] == 'php')
									{
										$novaLinha = substitui('[nome_campo]', "\t\t<input type='$type' class='form-control' id='$st' name='$st'$maxlength$required$autofocus value=\"<?php echo \$[nome_model]['$st']; ?>\">", $novaLinha);
										$novaLinha = substitui('[nome_model]', $tabela, $novaLinha);
									}
								}
							
							fwrite($arquivoDestino, $novaLinha);    
							
							$novaLinha = $linha;
							$novaLinha = substitui('[nome_campo]', "\t</div>", $novaLinha);
							fwrite($arquivoDestino, $novaLinha);
							
							$novaLinha = $linha;
							$novaLinha = substitui('[nome_campo]', "</div>", $novaLinha);
							fwrite($arquivoDestino, $novaLinha);
						}
					}
				}
				*/

				if ($scriptDeLayout == "consultar.blade.php" || $scriptDeLayout == "consultar.php")
				{
					foreach ($_SESSION["tabela"] [$tabela] as $tab)
					{
						$st = $tab['Field'];
						                            
                        $novaLinha = $linha;

                        $novaLinha = substitui('[nome_campo]', "<dt class='col-sm-2 text-right'>$st</dt>", $novaLinha);
						fwrite($arquivoDestino, $novaLinha);    
						
						$novaLinha = $linha;

						if ($_POST['fonte'] == 'laravel')
						{
							$novaLinha = substitui('[nome_campo]', "<dd class='col-sm-10'>{{\$[nome_model]->$st}}</dd>", $novaLinha);
							$novaLinha = substitui('[nome_model]', $tabela, $novaLinha);
						}
						if ($_POST['fonte'] == 'php')
						{
							$novaLinha = substitui('[nome_campo]', "<dd class='col-sm-10'><?php echo \$[nome_model]['$st']; ?></dd>", $novaLinha);
							$novaLinha = substitui('[nome_model]', $tabela, $novaLinha);
						}
						fwrite($arquivoDestino, $novaLinha);
					}
				}

				if ($scriptDeLayout == "alterar.blade.php" || $scriptDeLayout == "incluir.blade.php" || $scriptDeLayout == "alterar.php" || $scriptDeLayout == "incluir.php")
				{
					$cont = 0;

					foreach ($_SESSION["tabela"] [$tabela] as $tab)
					{
						if ($tab['Field'] != 'id' && $tab['Field'] != 'created_at' && $tab['Field'] != 'updated_at')
						{
							$type = $tab['Type'];
							$pos1 = strpos($type, "varchar(");
							$pos2 = strpos($type, ")");

							$tam = 0;
							$tamanhoInput = "";

							if ($pos1 === 0) 
							{
								$tamanhoInput = substr($type, $pos1 + 8, $pos2 - 8);
							}

							$st = $tab['Field'];
							$cont++;
							                            
		                    $novaLinha = $linha;
							$novaLinha = substitui('[nome_campo]', "<div class='row'>", $novaLinha);
							fwrite($arquivoDestino, $novaLinha);    
							
							$novaLinha = $linha;
							$novaLinha = substitui('[nome_campo]', "\t<div class='form-group col-6'>", $novaLinha);
							fwrite($arquivoDestino, $novaLinha);    
							
							$novaLinha = $linha;
							$novaLinha = substitui('[nome_campo]', "\t\t<label class='bmd-label-floating' for='$st'>$st</label>", $novaLinha);
							fwrite($arquivoDestino, $novaLinha);    
							
							$novaLinha = $linha;
							$required = $tab['Null'] == "NO" ? " required='true'" : "";
							$autofocus = $cont == 1 ? " autofocus" : "";
							if ($tamanhoInput != "")
								$maxlength = " maxlength='$tamanhoInput'";
							else
								$maxlength = "";
							$type = "text";
							if ($tab['Type'] == "date" || $tab['Type'] == "time")
							{
								$type = $tab['Type'];
								if ($_POST['fonte'] == 'laravel')
									$novaLinha = substitui('[nome_campo]', "\t\t<input type='$type' class='form-control' id='$st' name='$st'$maxlength$required$autofocus value='{{\$$tabela->$st}}'>", $novaLinha);
								if ($_POST['fonte'] == 'php')
								{
									$novaLinha = substitui('[nome_campo]', "\t\t<input type='$type' class='form-control' id='$st' name='$st'$maxlength$required$autofocus value=\"<?php echo \$[nome_model]['$st']; ?>\">", $novaLinha);
									$novaLinha = substitui('[nome_model]', $tabela, $novaLinha);
								}
							}
							else
								if ($tab['Type'] == "text")
								{
									if ($_POST['fonte'] == 'laravel')
										$novaLinha = substitui('[nome_campo]', "\t\t<textarea class='form-control' rows='5' id='$st' name='$st'>{{\$$tabela->$st}}</textarea>", $novaLinha);
									if ($_POST['fonte'] == 'php')
									{
										$novaLinha = substitui('[nome_campo]', "\t\t<textarea class='form-control' rows='5' id='$st' name='$st'><?php echo \$[nome_model]['$st']; ?></textarea>", $novaLinha);
										$novaLinha = substitui('[nome_model]', $tabela, $novaLinha);
									}
								}
								else
								{
									$pos1 = strpos($tab['Type'], "int") === 0;
									$pos2 = strpos($tab['Type'], "bigint") === 0;
									if ($pos1 || $pos2)
										$type = "number";
									if ($_POST['fonte'] == 'laravel')
										$novaLinha = substitui('[nome_campo]', "\t\t<input type='$type' class='form-control' id='$st' name='$st'$maxlength$required$autofocus value='{{\$$tabela->$st}}'>", $novaLinha);
									if ($_POST['fonte'] == 'php')
									{
										$novaLinha = substitui('[nome_campo]', "\t\t<input type='$type' class='form-control' id='$st' name='$st'$maxlength$required$autofocus value=\"<?php echo \$[nome_model]['$st']; ?>\">", $novaLinha);
										$novaLinha = substitui('[nome_model]', $tabela, $novaLinha);
									}
								}
						
							fwrite($arquivoDestino, $novaLinha);    
							
							$novaLinha = $linha;
							$novaLinha = substitui('[nome_campo]', "\t</div>", $novaLinha);
							fwrite($arquivoDestino, $novaLinha);
							
							$novaLinha = $linha;
							$novaLinha = substitui('[nome_campo]', "</div>", $novaLinha);
							fwrite($arquivoDestino, $novaLinha);
						}
					}
				}

				if ($scriptDeLayout == "excluir.blade.php" || $scriptDeLayout == "excluir.php")
				{
					foreach ($_SESSION["tabela"] [$tabela] as $tab)
					{
						$st = $tab['Field'];
						                            
                        $novaLinha = $linha;

						$novaLinha = substitui('[nome_campo]', "<dt class='col-sm-2 text-right'>$st</dt>", $novaLinha);
						fwrite($arquivoDestino, $novaLinha);    
						
						$novaLinha = $linha;
						if ($_POST['fonte'] == 'laravel')
						{
							$novaLinha = substitui('[nome_campo]', "<dd class='col-sm-10'>{{\$[nome_model]->$st}}</dd>", $novaLinha);
							$novaLinha = substitui('[nome_model]', $tabela, $novaLinha);
						}
						if ($_POST['fonte'] == 'php')
						{
							$novaLinha = substitui('[nome_campo]', "<dd class='col-sm-10'><?php echo \$[nome_model]['$st']; ?></dd>", $novaLinha);
							$novaLinha = substitui('[nome_model]', $tabela, $novaLinha);
						}
						fwrite($arquivoDestino, $novaLinha);
					}
				}

				if ($scriptDeLayout == "Controller.php" || $scriptDeLayout == "Service.php")
				{
					//$novaLinha = $linha;
					//$novaLinha = substitui('[nome_campo]', "/*", $novaLinha);
					//fwrite($arquivoDestino, $novaLinha);
					foreach ($_SESSION["tabela"] [$tabela] as $tab)
					{
						$novaLinha = $linha;
						if ($tab['Field'] != 'id' && $tab['Field'] != 'created_at' && $tab['Field'] != 'updated_at')
						{
							$st = $tab['Field'];
							if ($_POST['fonte'] == 'laravel')
								$novaLinha = substitui('[nome_campo]', "\$$tabela->$st = \$request->$st;", $novaLinha);
							if ($_POST['fonte'] == 'php')
								$novaLinha = substitui('[nome_campo]', "'$st'	=> \$this->input['$st'],", $novaLinha);
							fwrite($arquivoDestino, $novaLinha);
						}						
					}
					//$novaLinha = $linha;
					//$novaLinha = substitui('[nome_campo]', "*/", $novaLinha);
					//fwrite($arquivoDestino, $novaLinha);
				}

				if ($scriptDeLayout == "Model.php" || $scriptDeLayout == "Domain.php")
				{
					$st = "";
					foreach ($_SESSION["tabela"] [$tabela] as $tab)
					{
						$novaLinha = $linha;
						if ($tab['Field'] != 'id' && $tab['Field'] != 'created_at' && $tab['Field'] != 'updated_at')
							$st .= "'" . $tab['Field'] . "', ";						
					}
					$novaLinha = substitui('[nome_campo]', $st, $novaLinha);
					fwrite($arquivoDestino, $novaLinha);
				}
			}
			else
			if ($posini2 > 0)
			{
				foreach ($_SESSION["tabela"] [$tabela] as $tab)
				{
					$novaLinha = $linha;
					$st = $tab['Field'];
					$novaLinha = substitui('[nome_label]', "<th scope='col'>$st</th>", $novaLinha);
					fwrite($arquivoDestino, $novaLinha);
				}
			}
			else
				fwrite($arquivoDestino, $linha);
		}

		fclose($arquivoDestino); // Fecha arquivo de destino
		fclose($arquivoOrigem);	 // Fecha arquivo de origem
	}

	/************************************************************************************************************/


	function CriarScripts($dir, $dirlayouts, $dirnamespaces, $dircontrollers, $dirservices, $dirmodels, $dirdomains, $dirviews)
	{
		$diretorio = dir($dirlayouts);
		while ($arquivo = $diretorio->read())
			if ($arquivo != '.' && $arquivo != '..')
			{
				$pastas = scandir($dirnamespaces);

				foreach ($pastas as $tp)
					if ($tp != '.' && $tp != '..')
					{
						$namespaces = $dirnamespaces . DIRECTORY_SEPARATOR . $tp;
						$subpastas = scandir($namespaces);

						if (count($subpastas) > 2)
						{
							foreach ($subpastas as $sub)
								if ($sub != '.' && $sub != '..')
								{
									
									ProcessarScripts($dir, $dirlayouts, $dirnamespaces, $dircontrollers, $dirservices, $dirmodels, $dirdomains, $dirviews, $tp, $sub, $arquivo);
								}
							}
						}
			}			

		$diretorio->close();
	}

	if (isset($_POST['criar']))
	{
		try 
		{
			$dir = 'projects' . DIRECTORY_SEPARATOR . $_SESSION['project-lite'] . DIRECTORY_SEPARATOR . 'scripts';
			$dirlayouts = 'projects' . DIRECTORY_SEPARATOR . $_SESSION['project-lite'] . DIRECTORY_SEPARATOR . 'layouts';
			$dirnamespaces = 'projects' . DIRECTORY_SEPARATOR . $_SESSION['project-lite'] . DIRECTORY_SEPARATOR . 'namespaces';
			$dircontrollers = $dir . DIRECTORY_SEPARATOR . 'controllers';
			$dirservices = $dir . DIRECTORY_SEPARATOR . 'services';
			$dirmodels = $dir . DIRECTORY_SEPARATOR . 'models';
			$dirdomains = $dir . DIRECTORY_SEPARATOR . 'domains';
			$dirviews = $dir . DIRECTORY_SEPARATOR . 'views';
			
			if(!is_dir($dircontrollers))
				mkdir($dircontrollers);

			if(!is_dir($dirservices))
				mkdir($dirservices);

			if(!is_dir($dirdomains))
				mkdir($dirdomains);

			if(!is_dir($dirmodels))
				mkdir($dirmodels);

			if(!is_dir($dirviews))
				mkdir($dirviews);

			CriarScripts($dir, $dirlayouts, $dirnamespaces, $dircontrollers, $dirservices, $dirmodels, $dirdomains, $dirviews);
		}
		catch(Exception $e) 
		{
			
		}
	}

	if (isset($_POST['excluir']))
	{
		try
		{
			$dir = 'projects' . DIRECTORY_SEPARATOR . $_SESSION['project-lite'] . DIRECTORY_SEPARATOR . 'scripts';;
			$dircontrollers = $dir . DIRECTORY_SEPARATOR . 'controllers';
			$dirservices = $dir . DIRECTORY_SEPARATOR . 'services';
			$dirmodels = $dir . DIRECTORY_SEPARATOR . 'models';
			$dirdomains = $dir . DIRECTORY_SEPARATOR . 'domains';
			$dirviews = $dir . DIRECTORY_SEPARATOR . 'views';
			delTree($dircontrollers);
			delTree($dirservices);
			delTree($dirdomains);
			delTree($dirmodels);
			delTree($dirviews);
			echo "<div class='alert alert-info alert-dismissible'>";
	        echo "<button type='button' class='close' data-dismiss='alert' aria-hIDden='true'>×</button>";
	        echo "<h4><i class='icon fa fa-info'></i> Aviso!</h4>";
	        echo 'Scripts excluídos com sucesso';
	        echo "</div>";
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

	header ("location:scripts.php");	
?>