<?php
	include_once('../parent/controller.php');
	include_once('../views/public/index.php');
	
	class indexController extends Controller
	{
		public function rotas($rota)
		{
			if (!$this->validaSessao($rota)) // Se o usuário não está logado
				return parent::rotas('form/login'); // Redireciona para a página de login

			return new indexView(); // Inicia a página index da pasta public
		}

		public static function getInstance()
		{
			// Implementa o design pattern Singleton para instanciar cada classe uma única vez
	    	if (!isset(self::$instance))
	        	self::$instance = new indexController();
	    	return self::$instance;
	    }	
	}

	$index = indexController::getInstance(); // Obtém a instância da classe usando o Singleton
	$index->rotas('form/index'); // Rota padrão para as páginas
