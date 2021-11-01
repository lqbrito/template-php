<?php
	include_once('../parent/Controller.php');
	include_once('../services/UsuariosService.php');
	include_once('../views/usuarios/indexUsuarios.php');
	include_once('../views/usuarios/incluir.php');
	include_once('../views/usuarios/consultar.php');
	include_once('../views/usuarios/alterar.php');
	include_once('../views/usuarios/excluir.php');
	include_once('../views/usuarios/alterarsenha.php');
	include_once('../views/site/Index.php');
	
	class UsuariosController extends Controller
	{
		private $usuariosService;

		function __construct()
		{
			parent::__construct(); // Executa o construtor da superclasse
			$this->usuariosService = new UsuariosService(); // Inicializa a model associada ao controller
		}

		public function alterarSenha()
	    {
	    	if ($this->usuariosService->alterarSenha())
				return $this->rotas("form/index"); // Vai para a página inicial
			else
			  	return $this->back('form/alterarSenha'); // Volta para o formulário de alteração de senha
	    }
	    
	    public function login()
	    {
	    	if ($this->usuariosService->login())
	    		return $this->rotas("form/index"); // Volta para a página de listagem
	    	else
			  	return $this->back('form/login'); // Volta para o formulário de login
	    }
	    
	    public function incluir()
		{
			if ($this->usuariosService->incluir())
				return $this->rotas("form/indexUsuarios"); // Volta para a página de listagem
			else
			  	return $this->back('form/incluir'); // Volta para o formulário de inclusão		
		}

		public function alterar()
		{
			if ($this->usuariosService->alterar())
				return $this->rotas("form/indexUsuarios"); // Volta para a página de listagem
			else
			  	return $this->back('form/alterar'); // Volta para o formulário de alteração
		}

		public function excluir()
		{
			if ($this->usuariosService->excluir())
				return $this->rotas("form/indexUsuarios"); // Volta para a página de listagem
			else
			  	return $this->back('form/excluir'); // Volta para o formulário de alteração
		}

		public function rotas($rota)
		{
			if (!$this->validaSessao($rota)) // Se não houver sessão ativa
				return parent::rotas('form/login'); // Vai para o formulário de login

			// Rotas de formulários
			if ($rota == 'form/indexUsuarios')
				return new IndexUsuariosView($this->usuariosService->dadosIndex());
			if ($rota == 'form/incluir')
				return new IncluirView($this->usuariosService->dadosIncluir());
			if ($rota == 'form/consultar')
				return new ConsultarView($this->usuariosService->dadosConsultar());
			if ($rota == 'form/alterar')
				return new AlterarView($this->usuariosService->dadosAlterar());
			if ($rota == 'form/excluir')
				return new ExcluirView($this->usuariosService->dadosExcluir());
			if ($rota == 'form/index')
				return new IndexView();
			if ($rota == 'form/alterarSenha')
				return new AlterarSenhaView();
			
			// Rotas de ações
			if ($rota == 'action/pesquisar')
				return $this->pesquisar('form/indexUsuarios');
			if ($rota == 'action/incluir')
				return $this->incluir();
			if ($rota == 'action/alterar')
				return $this->alterar();
			if ($rota == 'action/excluir')
				return $this->excluir();
			if ($rota == 'action/login')
				return $this->login();
			if ($rota == 'action/alterarSenha')
				return $this->alterarSenha();

			// Caso não tenha sido informada nenhuma das rotas acima
			return null;
		}

		public static function getInstance()
		{
	    	// Implementa o design pattern Singleton para instanciar cada classe uma única vez
	    	if (!isset(self::$instance))
	        	self::$instance = new UsuariosController();
	    	return self::$instance;
	    }
	}

	// Caso não seja informada uma rota, define form/index como a rota padrão a ser executada para este script
	$operacao = 'form/indexUsuarios';
	
	//var_dump($_POST['operacao']);
	//die();
	
	if (isset($_POST['operacao']))
		$operacao = $_POST['operacao'];		
	
	$index = UsuariosController::getInstance(); // Obtém a instância única desta classe
	$index->rotas($operacao); // Executa o roteamento para saber qual método do controller executar
