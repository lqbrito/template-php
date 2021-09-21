<?php 
	session_start();
	$pag = 4;
	$titulo = "FDev (Lite) - Geração de scripts para o projeto";
	include_once ("_cabecalho.php");
?>

<!------------------------- Código do script ------------------------>

<?php
	require ("_definicoes.php");
	
	$pastas = scandir('projects' . DIRECTORY_SEPARATOR . $_SESSION['project-lite'] . DIRECTORY_SEPARATOR . 'namespaces');

	$pastasScripts = scandir('projects' . DIRECTORY_SEPARATOR . $_SESSION['project-lite'] . DIRECTORY_SEPARATOR . 'scripts');
?>

<!------------------------------------------------------------------->      

<div class="row mb-3">
	<div class="col-md-4">
		<div class="card">
		 	<div class="card-header">
		    	Scripts de layout
		 	</div>
		  
	  		<div class="card-body">			    
		    	<div class="form-row">
					<div class="col-md-12">
						<ul class = 'list-unstyled'>
							<?php

								$path = 'projects' . DIRECTORY_SEPARATOR . $_SESSION['project-lite'] . DIRECTORY_SEPARATOR . 'layouts';

								$diretorio = dir($path);

								while ($arquivo = $diretorio->read())
									if ($arquivo != '.' && $arquivo != '..')
									echo "<li>$arquivo</li>";					
								
								$diretorio->close();
							?>
						</ul>
					</div>
				</div>
		  	</div>
		
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

	  							echo "<li>$tp</li>";
	  							
	  							if (count($subpastas) > 2)
	  							{
	  								echo "<ul class = 'list-unstyled'>";
	  								foreach ($subpastas as $sub)
										if ($sub != '.' && $sub != '..')
										{
											echo "<li>$sub</li>";
										}
	  								echo "</ul>";
	  							}
	  						}
	  				?>
	  			</ul>
		  	</div>
		</div>
	</div>

	<div class="col-md-4">
		<div class="card">
	 		<div class="card-header">
		    	Scripts gerados para o projeto
		 	</div>
		 	<div class="card-body">
		 		<form action = "__manipulascripts.php" method = "post">
		 			<input type="hidden" name="fonte" value="php">
                    <button class="btn btn-primary btn-block mt-2" type="submit" name="criar"><i class="fas fa-file"></i> Criar scripts</button>
  					<button class="btn btn-danger btn-block" type="submit" name="excluir"><i class="fas fa-trash"></i> Excluir scripts</button>
  				</form>
	  			<hr>
	  			<ul>
	  				<?php
	  					foreach ($pastasScripts as $tp)
	  						if ($tp != '.' && $tp != '..')
	  						{
	  							$estrutura = 'projects' . DIRECTORY_SEPARATOR . $_SESSION['project-lite'] . DIRECTORY_SEPARATOR . 'scripts' . DIRECTORY_SEPARATOR . $tp;
	  						
	  							echo "<li>$tp</li>";
	  							if ($tp == 'models')
	  							{
	  								echo "<ol class = 'list-unstyled'>";
	  								$diretorio = dir($estrutura);

									while ($arquivo = $diretorio->read())
										if ($arquivo != '.' && $arquivo != '..')
										echo "<li>$arquivo</li>";					
									
									$diretorio->close();
									echo "</ol>";
	  							}
	  							else
	  							if ($tp == 'services')
	  							{
	  								echo "<ol class = 'list-unstyled'>";
	  								$diretorio = dir($estrutura);

									while ($arquivo = $diretorio->read())
										if ($arquivo != '.' && $arquivo != '..')
										echo "<li>$arquivo</li>";					
									
									$diretorio->close();
									echo "</ol>";
	  							}
	  							else
	  								if ($tp == 'controllers')
									{
										$subpastas = scandir($estrutura);

										if (count($subpastas) > 2)
										{
											echo "<ul>";
											foreach ($subpastas as $sub)
											if ($sub != '.' && $sub != '..')
											{
												echo "<li>$sub</li>";
												
												echo "<ol class = 'list-unstyled'>";
												$diretorio = dir($estrutura . DIRECTORY_SEPARATOR . $sub);

												while ($arquivo = $diretorio->read())
													if ($arquivo != '.' && $arquivo != '..')
														echo "<li>$arquivo</li>";					

													$diretorio->close();
													echo "</ol>";
											}
											echo "</ul>";
										}
									}
									else
		  								if ($tp == 'views')
										{
											$subpastas = scandir($estrutura);

											if (count($subpastas) > 2)
											{
												echo "<ul>";
												foreach ($subpastas as $sub)
												if ($sub != '.' && $sub != '..')
												{
													echo "<li>$sub</li>";
													
													$subsubpastas = scandir($estrutura . DIRECTORY_SEPARATOR . $sub);

													echo "<ul>";
													foreach ($subsubpastas as $subsub)
														if ($subsub != '.' && $subsub != '..')
														{
															echo "<li>$subsub</li>";
															echo "<ol class = 'list-unstyled'>";
															$diretorio = dir($estrutura . DIRECTORY_SEPARATOR . $sub . DIRECTORY_SEPARATOR . $subsub);

															while ($arquivo = $diretorio->read())
																if ($arquivo != '.' && $arquivo != '..')
																	echo "<li>$arquivo</li>";					

															$diretorio->close();
															echo "</ol>";
														}
													echo "</ul>";											
												}
												echo "</ul>";
											}
										}	
	  						}
	  				?>
	  			</ul>
		  	</div>
		</div>
	</div>
</div>

<?php 
	include_once ("_rodape.php");
?>