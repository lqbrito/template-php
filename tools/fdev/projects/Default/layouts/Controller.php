<?php
	include_once('../parent/Controller.php');
	include_once('../services/[nome_classe_service]Service.php');
	include_once('../views/[nome_model]/index.php');
	include_once('../views/[nome_model]/incluir.php');
	include_once('../views/[nome_model]/consultar.php');
	include_once('../views/[nome_model]/alterar.php');
	include_once('../views/[nome_model]/excluir.php');
	
	class [nome_classe_controller]Controller extends Controller
	{
		private $[nome_service]Service;

		function __construct()
		{
			parent::__construct(); // Executa o construtor da superclasse
			$this->[nome_service]Service = new [nome_classe_service]Service(); // Instancia a classe de serviços
		}

		public function incluir()
		{
			if ($this->[nome_service]Service->incluir())
				return $this->rotas("form/index"); // Volta para a página index
			else
			  	return $this->back('form/incluir'); // Volta para o formulário de inclusão			
		}

		public function alterar()
		{
			if ($this->[nome_service]Service->alterar())
				return $this->rotas("form/index"); // Volta para a página index
			else
			  	return $this->back('form/alterar'); // Volta para o formulário de alteração
		}

		public function excluir()
		{
			if ($this->[nome_service]Service->excluir())
				return $this->rotas("form/index"); // Volta para a página index
			else
			  	return $this->back('form/excluir'); // Volta para o formulário de exclusão
		}

		public function rotas($rota)
		{
			if (!$this->validaSessao($rota)) // Se não houver sessão ativa
				return parent::rotas('form/login'); // Vai para o formulário de login

			// Rotas de formulários
			if ($rota == 'form/index')
				return new IndexView($this->[nome_service]Service->dadosIndex());
			if ($rota == 'form/incluir')
				return new IncluirView($this->[nome_service]Service->dadosIncluir());
			if ($rota == 'form/consultar')
				return new ConsultarView($this->[nome_service]Service->dadosConsultar());
			if ($rota == 'form/alterar')
				return new AlterarView($this->[nome_service]Service->dadosAlterar());
			if ($rota == 'form/excluir')
				return new ExcluirView($this->[nome_service]Service->dadosExcluir());

			// Rotas de ações
			if ($rota == 'action/pesquisar')
				return $this->pesquisar();
			if ($rota == 'action/incluir')
				return $this->incluir();
			if ($rota == 'action/alterar')
				return $this->alterar();
			if ($rota == 'action/excluir')
				return $this->excluir();

			// Caso não tenha sido informada nenhuma das rotas acima
			return null;
		}

		public static function getInstance()
		{
	    	// Implementa o design pattern Singleton para instanciar cada classe uma única vez
	    	if (!isset(self::$instance))
	        	self::$instance = new [nome_classe_controller]Controller();
	    	return self::$instance;
	    }
	}

	// Caso não seja informada uma rota, define form/index como a rota padrão a ser executada para este script
	$operacao = 'form/index';
	if (isset($_POST['operacao']))
		$operacao = $_POST['operacao'];		
	
	$index = [nome_classe_controller]Controller::getInstance(); // Obtém a instância única desta classe
	$index->rotas($operacao); // Executa o roteamento para saber qual método do controller executar
