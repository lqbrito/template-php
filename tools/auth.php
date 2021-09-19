<?php
	include_once('../parent/Controller.php');
	
	class AuthController extends Controller
	{
		public function finalizaSessao()
	    {
	    	parent::finalizaSessao(); // Finaliza uma sessão
	    	$this->rotas("form/login"); // Redireciona para a página de login
	    }
	    
	    public function rotas($rota)
		{
			if (!$this->validaSessao($rota)) // Se o usuário não está logado
				return parent::rotas('form/login'); // Redireciona para a página de login
			
			// Rotas de ações
			if ($rota == 'action/logout')
				return $this->finalizaSessao();

			// Caso não tenha sido informada nenhuma das rotas acima
			return null;
		}

		public static function getInstance()
		{
	    	// Implementa o design pattern Singleton para instanciar cada classe uma única vez
	    	if (!isset(self::$instance))
	        	self::$instance = new AuthController();
	    	return self::$instance;
	    }
	}

	$operacao = 'action/logout';
	// Se foi passada uma rota via GET ou POST utiliza a rota passada
	if (isset($_GET['operacao']))
		$operacao = $_POST['operacao'];		

	if (isset($_POST['operacao']))
		$operacao = $_POST['operacao'];		
	
	$index = AuthController::getInstance(); // Obtém a instância da classe usando o Singleton
	$index->rotas($operacao);
