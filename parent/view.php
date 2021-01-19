<?php
	class View
	{
		protected $data; // Representa os dado enviados pelo controller
		protected $maxLinks = 3; // Quantidade de links antes e depois da página atual
	  

		function __construct($data = null)
		{
			$this->data = $data; // Recebe os dados do controller
			$this->view($data); // Renderiza a view definida em uma subclasse
		}
	
		function links($controller, $pagina, $paginas)
		{
			echo "<nav aria-label='...'>";
  			echo "<ul class='pagination justify-content-center'>";
  			// Renderiza o link para a primeira página
  			echo "<li class='page-item'><a class='page-link' href='../controllers/$controller?pagina=1'>Primeira</a></li>";
  			
  			// Renderiza as ... entre a página inicial e a primeira página do bloco de páginas sendo exibidas
  			if($pagina - $this->maxLinks > 1) 
  				echo "<li class='page-item disabled'><a class='page-link' href='#' tabindex='-1' aria-disabled='true'>...</a></li>";
  			
  			// Renderiza o link para o bloco de páginas sendo exibidas
  			for ($pg = $pagina - $this->maxLinks; $pg <= $pagina + $this->maxLinks; $pg++)
				if ($pg >= 1 && $pg <= $paginas)
				{
					if ($pg == $pagina) // Destaca o link da página atual
						echo "<li class='page-item active' aria-current='page'><a class='page-link' href='../controllers/$controller?pagina=$pg'>$pg</a></li>";
					else // Renderiza o link das outras páginas do bloco de páginas sendo exibido
						echo "<li class='page-item'><a class='page-link' href='../controllers/$controller?pagina=$pg'>$pg</a></li>";
				}
			// Renderiza as ... entre a última página do bloco de páginas sendo exibidas e a página final
			if($pagina + $this->maxLinks < $paginas)
  				echo "<li class='page-item disabled'><a class='page-link' href='#' tabindex='-1' aria-disabled='true'>...</a></li>";
  			// Renderiza o link para a última página
  			echo "<li class='page-item'><a class='page-link' href='../controllers/$controller?pagina=$paginas'>Última</a></li>";
			echo "</ul>";
			echo "</nav>";			
		}
		
		function csrf()
		{
			// Cria um hash criptografado para proteção CRSF
			$nro = rand(1000000, 100000000);
			$hash = hash ("sha512", $nro);
			$_SESSION['csrf'] = $hash; // Utiliza a variável de sessão para gerenciar o hash crsf
			return $hash;
		}

		public function view($data)
		{
			// Este método deve ser implementado em uma subclasse para renderizar uma view
		}

		public function data()
		{
			return $this->data; // Retorna o array dos dados que foram passados para a view
		}
		
		public function showMessage()
		{
			// Mostra para o usuário as mensagens definidas na variável de sessão indexada por Status, Aviso, Sucesso, Erro e Erros, sendo cada uma em uma cor diferente
			if (isset($_SESSION['Status']))
			{
				echo "<div class='alert alert-primary alert-dismissible fade show' role='alert'>";
	        	echo "<button type='button' class='close' data-dismiss='alert' aria-hIDden='true'>×</button>";
		        echo "<h4><i class='icon fa fa-info'></i> Aviso!</h4>";
		        echo $_SESSION['Status'];
	        	echo "</div>";
				unset($_SESSION['Status']);
			}
			if (isset($_SESSION['Aviso']))
			{
				echo "<div class='alert alert-warning alert-dismissible fade show' role='alert'>";
	        	echo "<button type='button' class='close' data-dismiss='alert' aria-hIDden='true'>×</button>";
		        echo "<h4><i class='icon fa fa-info'></i> Aviso!</h4>";
		        echo $_SESSION['Aviso'];
	        	echo "</div>";
				unset($_SESSION['Aviso']);
			}
			if (isset($_SESSION['Sucesso']))
			{
				echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>";
	        	echo "<button type='button' class='close' data-dismiss='alert' aria-hIDden='true'>×</button>";
		        echo "<h4><i class='icon fa fa-thumbs-up'></i> Sucesso!</h4>";
		        echo $_SESSION['Sucesso'];
	        	echo "</div>";
				unset($_SESSION['Sucesso']);
			}
			if (isset($_SESSION['Erro']))
			{
				echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>";
	        	echo "<button type='button' class='close' data-dismiss='alert' aria-hIDden='true'>×</button>";
		        echo "<h4><i class='icon fa fa-thumbs-down'></i> Erro!</h4>";
		        echo $_SESSION['Erro'];
	        	echo "</div>";
				unset($_SESSION['Erro']);
			}
			if (isset($_SESSION['Erros']))
			{
				echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>";
	        	echo "<button type='button' class='close' data-dismiss='alert' aria-hIDden='true'>×</button>";
		        echo "<h4><i class='icon fa fa-thumbs-down'></i> Erros!</h4>";
		        echo "<p>Foram encontrdos os seguintes erros:</p>";
		        echo "<ol>";
		        foreach($_SESSION['Erros'] as $e)
		        	echo "<li>" . $e . "</li>";
		        echo "</ol>";
	        	echo "</div>";
				unset($_SESSION['Erros']);
			}			
		}		
	}
