<?php
	include_once('../parent/View.php');

	class IndexView extends View
	{
		public function view($data)
		{
			$pag = 1; // Use esta numeração para representar o crud atual e indicar sua opção de menu como ativa
			$titulo = "Título da aplicação"; // Título da página
			$empresa = APP_EMPRESA;
			$mensagem = APP_MENSAGEM;
			// Cabeçalho comum a todas as páginas
            include_once ("../views/layouts/" . $_SESSION['app_ui'] . "_cabecalho.php");
			$this->showMessage(); // Caso hajam msgs elas são mostradas ao usuário
			?>		
			

			<?php
				if (ENVIRONMENT == 'development')
				{ ?>
				<a class="navbar-brand" href="../tools/fdev">Clique aqui para acessar o FDev (Lite).</a>
				<p><b>Remova o link do FDev (Lite) quando liberar a aplicação para produção.</b></p>
				<p class="text-danger"><b>Por questões de segurança não é aconselhável fazer upload da pasta do FDev (Lite) para o servidor de produção.</b></p>
				<br>
				<a class="navbar-brand" href="../themes/architectui">Clique aqui para acessar o tema ArchitectUI.</a>
				<?php }
			?>			

			<?php
			// Rodapé comum a todas as páginas
            include_once ("../views/layouts/" . $_SESSION['app_ui'] . "_rodape.php");
		}
	}
