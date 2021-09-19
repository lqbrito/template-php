<?php
	session_start();

	include_once('../config/App.php');
	include_once('View.php');
	include_once('../views/usuarios/login.php');

	class Controller
	{
		public static $instance;
		
		protected $model; // Associa este controller a alguma model
		protected $tamanhoStringBusca = 3; // Define o tamanho mínimo para uma string de busca nas listagens
    	protected $limiteRegistros = 30; // Define a quantidade máxima de registros recuperados em uma consulta sem paginação
    	protected $totRegs = 10; // Define a quantidade de registros recuperados em uma consulta com paginação
    	
	    function __construct()
		{
			$app = App::getInstance(); // Obtém a instância da classe de configuração App
		}

    	public function incluir()
		{
			// Este método deve ser definido em uma subclasse
		}

		public function alterar()
		{
			// Este método deve ser definido em uma subclasse	
		}

		public function excluir()
		{
			// Este método deve ser definido em uma subclasse
		}

		public function iniciaSessao()
		{
			$_SESSION['logged'] = true; // Inicia a sessão informando que o usuário está logado
		}
		
		public function finalizaSessao()
		{
			$_SESSION['logged'] = false; // Finaliza a sessão atual
		}
		
		public function validaSessao($rota)
		{
			// Valida a sessão verificando se um usuário está ou não logado
			if ($rota == "action/login")
				return true;
			if (isset($_SESSION['logged']))
				return $_SESSION['logged'];
			else
				return false;
		}
		
		public function pesquisar($rota = 'form/index')
	    {
	    	// Obtém um valor de pesquisa digitado pelo usuário em uma página de listagem e armazena na variável de sessão
	        $textobusca = '';
	        if (isset($_POST['textobusca']))
	            $textobusca = $_POST['textobusca'];
	        $_SESSION['Pesquisa'] = $textobusca;
	        // Volta para a página de listagem para utilizar a variável de sessão com o valor que foi digitado
	        return $this->rotas($rota);
	    }
	    
	    public function rotas($rota)
		{
			/*  
				Rotas padrão para serem utilizadas em CRUD
				form/index
				form/incluir
				form/consultar
				form/alterar
				form/excluir
				form/login
				action/pesquisar
				action/incluir
				action/alterar
				action/excluir
			*/

			if ($rota == 'form/login') // Para uma rota de login faz o direcionamento para a página de login
				return new loginView();
				
			return null;
		}

		public function back($rota = 'form/index')
		{
			// Volta para uma rota anterior ou para a rota padrão form/index
			return $this->rotas($rota);
		}
	}
