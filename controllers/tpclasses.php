<?php
	include_once('../parent/Controller.php');
	include_once('../services/TpclassesService.php');
	include_once('../views/tpclasses/index.php');
	include_once('../views/tpclasses/incluir.php');
	include_once('../views/tpclasses/consultar.php');
	include_once('../views/tpclasses/alterar.php');
	include_once('../views/tpclasses/excluir.php');
	
	class TpclassesController extends Controller
	{
		private $tpclassesService;

		function __construct()
		{
			parent::__construct(); // Executa o construtor da superclasse
			$this->tpclassesService = new TpclassesService();
		}

		public function incluir()
		{
			if ($this->tpclassesService->incluir())
				return $this->rotas("form/index"); // Volta para a página de listagem
			else
			  	return $this->back('form/incluir'); // Volta para o formulário de inclusão			
		}

		public function alterar()
		{
			if ($this->tpclassesService->alterar())
				return $this->rotas("form/index"); // Volta para a página de listagem
			else
			  	return $this->back('form/alterar'); // Volta para o formulário de alteração
		}

		public function excluir()
		{
			if ($this->tpclassesService->excluir())
				return $this->rotas("form/index"); // Volta para a página de listagem
			else
			  	return $this->back('form/excluir'); // Volta para o formulário de alteração
		}

		public function rotas($rota)
		{
			if (!$this->validaSessao($rota)) // Se não houver sessão ativa
				return parent::rotas('form/login'); // Vai para o formulário de login

			// Rotas de formulários
			if ($rota == 'form/index')
				return new IndexView($this->tpclassesService->dadosIndex());
			if ($rota == 'form/incluir')
				return new IncluirView($this->tpclassesService->dadosIncluir());
			if ($rota == 'form/consultar')
				return new ConsultarView($this->tpclassesService->dadosConsultar());
			if ($rota == 'form/alterar')
				return new AlterarView($this->tpclassesService->dadosAlterar());
			if ($rota == 'form/excluir')
				return new ExcluirView($this->tpclassesService->dadosExcluir());

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
	        	self::$instance = new TpclassesController();
	    	return self::$instance;
	    }
	}

	// Caso não seja informada uma rota, define form/index como a rota padrão a ser executada para este script
	$operacao = 'form/index';
	if (isset($_POST['operacao']))
		$operacao = $_POST['operacao'];		
	
	$index = TpclassesController::getInstance(); // Obtém a instância única desta classe
	$index->rotas($operacao); // Executa o roteamento para saber qual método do controller executar
