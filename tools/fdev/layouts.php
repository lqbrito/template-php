<?php 
	session_start();
	$pag = 2;
	$titulo = "FDev (Lite) - Upload de scripts de layout";
	include_once ("_cabecalho.php");
?>

<!------------------------- Código do script ------------------------>

<?php
	require ("_definicoes.php");

	function upload()
	{
		$res = false;
		$arquivoinvalido = false;
		$arquivos = $_FILES['filesupload'];
		for ($controle = 0; $controle < count($arquivos['name']); $controle++)
		{
			$nome = $arquivos['name'][$controle];
			
			// Pega a extensão
			$extensao = pathinfo ($nome, PATHINFO_EXTENSION);			
			
			// Converte a extensão para minúsculo
			$extensao = strtolower ($extensao);
			if (! strstr ('php', $extensao))
				$arquivoinvalido = true; 			
		}

		if ($arquivoinvalido)
		{
			echo "<div class='alert alert-danger alert-dismissible'>";
	        echo "<button type='button' class='close' data-dismiss='alert' aria-hIDden='true'>×</button>";
	        echo "<h4><i class='icon fa fa-info'></i> Aviso!</h4>";
	        echo 'Só é permitido upload de arquivos com extensão php';
	        echo "</div>";

	        return false;
		}
		
				//diretório para salvar as imagens
		$diretorio = 'projects' . DIRECTORY_SEPARATOR . $_SESSION['project-lite'] . DIRECTORY_SEPARATOR . 'layouts';

		$downloadBemSucedido = true;
		for ($controle = 0; $controle < count($arquivos['name']); $controle++)
		{        
	        $destino = $diretorio . DIRECTORY_SEPARATOR . $arquivos['name'][$controle];
	        
	        if(! move_uploaded_file($arquivos['tmp_name'][$controle], $destino))
	     		$downloadBemSucedido = false;
	    }

	    if ($downloadBemSucedido)
		{
			echo "<div class='alert alert-info alert-dismissible'>";
	        echo "<button type='button' class='close' data-dismiss='alert' aria-hIDden='true'>×</button>";
	        echo "<h4><i class='icon fa fa-info'></i> Aviso!</h4>";
	        echo 'Upload de ' . count($arquivos['name']) . ' arquivo(s) realizado com sucesso';
	        echo "</div>";
		}
		else
		{
			echo "<div class='alert alert-danger alert-dismissible'>";
	        echo "<button type='button' class='close' data-dismiss='alert' aria-hIDden='true'>×</button>";
	        echo "<h4><i class='icon fa fa-info'></i> Erro!</h4>";
	        echo 'Upload de arquivos mal sucedido';
	        echo "</div>";
		}

	    return $downloadBemSucedido;
	}

	if (isset($_POST['upload']))
		upload();

	if (isset($_POST['excluir']))
	{
		$diretorio = 'projects' . DIRECTORY_SEPARATOR . $_SESSION['project-lite'] . DIRECTORY_SEPARATOR . 'layouts';		
		$totalarquivosexcluidos = 0;
		foreach ($_POST['filesdelete'] as $arq)
		{
			$arquivo = $diretorio . DIRECTORY_SEPARATOR . $arq;
			if (unlink($arquivo))
				$totalarquivosexcluidos++;
		}
		echo "<div class='alert alert-info alert-dismissible'>";
        echo "<button type='button' class='close' data-dismiss='alert' aria-hIDden='true'>×</button>";
        echo "<h4><i class='icon fa fa-info'></i> Aviso!</h4>";
        echo 'Exclusão de ' . $totalarquivosexcluidos . ' arquivo(s) realizada com sucesso';
        echo "</div>";		
	}
?>

<div class="row mb-3">
	<div class="col-md-6">
		<div class="card">
		 	<div class="card-header">
		    	Scripts de layout
		 	</div>
		  
		  	<form action = "layouts.php" method = "post">
				<div class="card-body">			    
			    	<div class="form-row">
						<div class="col-md-12">
							<label for="filesdelete">Selecione...</label>
							<select class="form-control" multiple id="filesdelete" name="filesdelete[]" required style="min-height : 200px;">
								<?php

									$path = 'projects' . DIRECTORY_SEPARATOR . $_SESSION['project-lite'] . DIRECTORY_SEPARATOR . 'layouts';

									$diretorio = dir($path);

									while ($arquivo = $diretorio->read())
										if ($arquivo != '.' && $arquivo != '..')
										echo "<option value='$arquivo'>$arquivo</option>";					
									
									$diretorio->close();
								?>
							</select>
						</div>
					</div>
			  	</div>
			  	
				<div class="card-footer">
			    	<button class="btn btn-danger" type="submit" name="excluir"><i class="fas fa-trash"></i> Excluir</button>
			 	</div>
		  
			</form>
		</div>
	</div>

	<div class="col-md-6">
		<div class="card">
		 	<div class="card-header">
		    	Upload de scripts de layout
		 	</div>
		  
		  	<form action = "layouts.php" method = "post" enctype="multipart/form-data">
				<div class="card-body">			    
			    	<div class="form-row">
						<div class="col-md-12">
							<label for="filesupload">Selecione...</label>
							<input type="file" multiple class="form-control" id="filesupload" name="filesupload[]" required>
						</div>
					</div>
			  	</div>
			  	
				<div class="card-footer">
			    	<button class="btn btn-primary" type="submit" name="upload"><i class="fas fa-upload"></i> Upload</button>
			 	</div>
		  
			</form>

		</div>

		<div class="card mt-3">
			<div class="card-header">
		    	Os scripts de layout devem conter as seguintes tags
		 	</div>
		  		  	
			<div class="col-md-12 mt-3">
			  	<nav>
			  		<div class="nav nav-tabs" id="nav-tab" role="tablist">
			  			<a class="nav-link active" id="nav-01-tab" data-toggle="tab" href="#nav-01" role="tab" aria-controls="nav-01" aria-selected="true">Controller</a>
			  			<a class="nav-link" id="nav-08-tab" data-toggle="tab" href="#nav-08" role="tab" aria-controls="nav-08" aria-selected="false">Service</a>
			  			<a class="nav-link" id="nav-02-tab" data-toggle="tab" href="#nav-02" role="tab" aria-controls="nav-02" aria-selected="false">Model</a>
			  			<a class="nav-link" id="nav-03-tab" data-toggle="tab" href="#nav-03" role="tab" aria-controls="nav-03" aria-selected="false">Index</a>
			  			<a class="nav-link" id="nav-04-tab" data-toggle="tab" href="#nav-04" role="tab" aria-controls="nav-04" aria-selected="false">Incluir</a>
			  			<a class="nav-link" id="nav-05-tab" data-toggle="tab" href="#nav-05" role="tab" aria-controls="nav-05" aria-selected="false">Consultar</a>
			  			<a class="nav-link" id="nav-06-tab" data-toggle="tab" href="#nav-06" role="tab" aria-controls="nav-06" aria-selected="false">Alterar</a>
			  			<a class="nav-link" id="nav-07-tab" data-toggle="tab" href="#nav-07" role="tab" aria-controls="nav-07" aria-selected="false">Excluir</a>
			  		</div>
			  	</nav>
			  	<div class="tab-content" id="nav-tabContent">
			  		<div class="tab-pane fade show active" id="nav-01" role="tabpanel" aria-labelledby="nav-01-tab">
			  			<br>
			  			<ul>
				  			<li>[namespace] - Representa a namespace do controller e das views</li>
				  			<li>[nome_classe_controller] - Representa o nome do arquivo e da classe controller</li>
				  			<li>[nome_classe_service] - Representa o nome do arquivo e da classe de serviços</li>
				  			<li>[nome_classe_model] - Representa o nome do arquivo e da classe model</li>
				  			<li>[nome_controller] - Representa o nome do objeto instanciado da classe controller</li>
				  			<li>[nome_service] - Representa o nome do objeto instanciado da classe de serviços</li>
				  			<li>[nome_model] - Representa o nome do objeto instanciado da classe model</li>
				  			<li>[nome_campo] - Representa o local onde será feita a atribuição dos dados de uma requisição para o controller nas operações de inclusão e alteração</li>
			  			<ul>
			  		</div>
			  		<div class="tab-pane fade" id="nav-08" role="tabpanel" aria-labelledby="nav-08-tab">
			  			<br>
			  			<ul>
				  			<li>[namespace] - Representa a namespace do controller e das views</li>
				  			<li>[nome_classe_controller] - Representa o nome do arquivo e da classe controller</li>
				  			<li>[nome_classe_service] - Representa o nome do arquivo e da classe de serviços</li>
				  			<li>[nome_classe_model] - Representa o nome do arquivo e da classe model</li>
				  			<li>[nome_controller] - Representa o nome do objeto instanciado da classe controller</li>
				  			<li>[nome_service] - Representa o nome do objeto instanciado da classe de serviços</li>
				  			<li>[nome_model] - Representa o nome do objeto instanciado da classe model</li>
				  			<li>[nome_campo] - Representa o local onde será feita a atribuição dos dados de uma requisição para o controller nas operações de inclusão e alteração</li>
			  			<ul>
			  		</div>
			  		<div class="tab-pane fade" id="nav-02" role="tabpanel" aria-labelledby="nav-02-tab">
			  			<br>
			  			<ul>
				  			<li>[nome_model] - Representa o nome do arquivo e da classe model</li>
				  			<li>[nome_campo] - Representa o local onde serão listados os nomes dos atributos da model</li>
			  			<ul>
			  		</div>
			  		<div class="tab-pane fade" id="nav-03" role="tabpanel" aria-labelledby="nav-03-tab">
			  			<br>
			  			<ul>
				  			<li>[nome_controller] - Representa o nome do arquivo e da classe controller</li>
				  			<li>[nome_model] - Representa o nome do arquivo e da classe model</li>
				  			<li>[nome_label] - Representa o local da tabela onde serão listados os labels dos campos das instâncias da model</li>
				  			<li>[nome_campo] - Representa o local da tabela onde serão listados os campos das instâncias da model</li>
			  			<ul>
			  		</div>
			  		<div class="tab-pane fade" id="nav-04" role="tabpanel" aria-labelledby="nav-04-tab">
			  			<br>
			  			<ul>
				  			<li>[nome_controller] - Representa o nome do arquivo e da classe controller</li>
				  			<li>[nome_model] - Representa o nome do arquivo e da classe model</li>
				  			<li>[nome_campo] - Representa o local do formulário onde serão colocados os inputs para inclusão de uma nova instância na model</li>
			  			<ul>
			  		</div>
			  		<div class="tab-pane fade" id="nav-05" role="tabpanel" aria-labelledby="nav-05-tab">
			  			<br>
			  			<ul>
				  			<li>[nome_controller] - Representa o nome do arquivo e da classe controller</li>
				  			<li>[nome_model] - Representa o nome do arquivo e da classe model</li>
				  			<li>[nome_campo] - Representa o local do formulário onde serão listados os atributos de uma instância da model</li>
			  			<ul>
			  		</div>
			  		<div class="tab-pane fade" id="nav-06" role="tabpanel" aria-labelledby="nav-06-tab">
			  			<br>
			  			<ul>
				  			<li>[nome_controller] - Representa o nome do arquivo e da classe controller</li>
				  			<li>[nome_model] - Representa o nome do arquivo e da classe model</li>
				  			<li>[nome_campo] - Representa o local do formulário onde serão colocados os inputs para alteração dos atributos de uma instância da model</li>
			  			<ul>
			  		</div>
			  		<div class="tab-pane fade" id="nav-07" role="tabpanel" aria-labelledby="nav-07-tab">
			  			<br>
			  			<ul>
				  			<li>[nome_controller] - Representa o nome do arquivo e da classe controller</li>
				  			<li>[nome_model] - Representa o nome do arquivo e da classe model</li>
				  			<li>[nome_campo] - Representa o local do formulário onde serão listados os atributos de uma instância da model a ser excluída</li>
			  			<ul>
			  		</div>
			  	</div>
  			</div>
		  	
		</div>
	</div>

</div>

<!------------------------------------------------------------------->      

<?php 
	include_once ("_rodape.php");
?>