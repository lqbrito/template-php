<?php
	include_once('../parent/controller.php');
	include_once('../models/[nome_model].php');
	include_once('../views/[nome_model]/listagem.php');
	include_once('../views/[nome_model]/incluir.php');
	include_once('../views/[nome_model]/consultar.php');
	include_once('../views/[nome_model]/alterar.php');
	include_once('../views/[nome_model]/excluir.php');
	
	class [nome_controller]Controller extends Controller
	{
		public function valida_request()
		{
			$this->input = $this->filterAll(); // Retorna todos os campos de $_POST sanitizados
			$validado = true;
			
			// Utilizar sempre $this->input nas validações em vez de $_POST
			
			return $validado;
		}

		public function incluir()
		{
			$this->valida_token();
			if (!$this->valida_request())
				return $this->back('form/incluir');
			try
			{
				$this->model()->beginTransaction();
				$this->model()->insert(
					[
						[nome_campo]
					]
				);
				$this->model()->commit();
				$_SESSION['Status'] = "Inclusão de '" . $this->input['descricao'] . "' realizada com sucesso";
				return $this->rotas("form/listagem");
			}
			catch(Exception $e)
			{
				$this->model()->rollBack();
				$_SESSION['Erro'] = $e->getMessage();
			  	return $this->back('form/incluir');
			}				
		}

		public function alterar()
		{
			$this->valida_token();
			if (!$this->valida_request())
				return $this->back('form/alterar');
			try
			{
				$this->model()->find($this->filterInput('id', 'int'));
				$this->model()->beginTransaction();
				$this->model()->update(
					[
						[nome_campo]
					]
				);
				$this->model()->commit();
				$_SESSION['Status'] = "Alteração de '" . $this->input['descricao'] . "' realizada com sucesso";
				return $this->rotas("form/listagem");
			}
			catch(Exception $e)
			{
				$this->model()->rollBack();
				$_SESSION['Erro'] = $e->getMessage();
			  	return $this->back('form/alterar');
			}
		}

		public function excluir()
		{
			$this->valida_token();
			try
			{
				$[nome_model] = $this->model()->find($this->filterInput('id', 'int'));
				$this->model()->beginTransaction();
				$this->model()->delete();
				$this->model()->commit();
				$_SESSION['Status'] = "Exclusão de '" . $[nome_model]['descricao'] . "' realizada com sucesso";
				return $this->rotas("form/listagem");
			}
			catch(Exception $e)
			{
				$this->model()->rollBack();
				$_SESSION['Erro'] = $e->getMessage();
			  	return $this->back('form/excluir');
			}
		}

		public function dadosListagem()
		{
			$textobusca = $this->buscarpesquisa();
			$listaTudo = strlen($textobusca) >= $this->tamanhoStringBusca;
			$count = $this->model()->count(
				[
					["AND", "", "=", ""],
					["AND", "descricao", "LIKE", "%$textobusca%"]
				]
			);
			if (!$listaTudo)
				$this->model()->defineLimits($this->getOffset($count), $this->getTotRegs());
			$this->model()->select(
				["*"], // Pode-se usar * em vez dos campos individuais
				// [[null]], caso não utilize a clausula WHERE
				[
					["AND", "", "=", ""],
					["AND", "descricao", "LIKE", "%$textobusca%"]
				],
				// [[null]], caso não utilize a clausula ORDER BY
				[
					["descricao", "ASC"],					
				]
			);
						
			$[nome_model] = $this->model()->all();
			return ['[nome_model]' => $[nome_model], 'listaTudo' => $listaTudo, 'tamanhoStringBusca' => $this->tamanhoStringBusca, 'textobusca' => $textobusca, 'pagina' => $this->getPagina($count), 'paginas' => $this->getTotPaginas($count)];			
		}

		public function dadosIncluir()
		{
			if (isset($this->input))
				return ['[nome_model]' => $this->input];
			else
				return ['[nome_model]' => $this->clearFields()];
		}
		
		public function dadosAlterar()
		{
			if (isset($this->input))
				return ['[nome_model]' => $this->input];
			else
				return $this->dadosconsultar();
		}

		public function dadosConsultar()
		{
			$[nome_model] = $this->model()->find($_POST['id']);
			return ['[nome_model]' => $[nome_model]];
		}

		public function dadosExcluir()
		{
			return $this->dadosconsultar();
		}
		
		public function rotas($rota)
		{
			if (!$this->validaSessao($rota))
				return parent::rotas('form/login');

			// Rotas de formulários
			if ($rota == 'form/listagem')
				return new listagemView($this->dadosListagem());
			if ($rota == 'form/incluir')
				return new incluirView($this->dadosIncluir());
			if ($rota == 'form/consultar')
				return new consultarView($this->dadosConsultar());
			if ($rota == 'form/alterar')
				return new alterarView($this->dadosAlterar());
			if ($rota == 'form/excluir')
				return new excluirView($this->dadosExcluir());

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

		function __construct()
		{
			parent::__construct();
			$this->model = new [nome_model]Model();
		}

		public static function getInstance()
		{
	    	if (!isset(self::$instance))
	        	self::$instance = new [nome_controller]Controller();
	    	return self::$instance;
	    }
	}

	$operacao = 'form/listagem';
	if (isset($_POST['operacao']))
		$operacao = $_POST['operacao'];		
	
	$index = [nome_controller]Controller::getInstance();
	$index->rotas($operacao);
