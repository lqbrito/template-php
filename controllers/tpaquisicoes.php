<?php
	include_once('../parent/controller.php');
	include_once('../models/tpaquisicoes.php');
	include_once('../views/tpaquisicoes/listagem.php');
	include_once('../views/tpaquisicoes/incluir.php');
	include_once('../views/tpaquisicoes/consultar.php');
	include_once('../views/tpaquisicoes/alterar.php');
	include_once('../views/tpaquisicoes/excluir.php');
	
	class tpaquisicoesController extends Controller
	{
		public function valida_request()
		{
			$this->input = $this->filterAll(); // Retorna todos os campos de $_POST sanitizados
			$validado = true;
			
			// Utilizar sempre $this->input nas validações em vez de $_POST
			
			// Abaixo está um exemplo de como utilizar o método para fazer as validações
			if ($this->input['descricao'] == "Compraaa" || $this->input['descricao'] == "Comprasss")
			{
				$_SESSION ['Erros'] [] = "Não pode digitar Compraaa ou Comprasss";
				$validado = false;
			}
			
			return $validado;
		}

		public function incluir()
		{
			$this->valida_token(); // Valida token para evitar ataque csrf
			if (!$this->valida_request()) // Se não passar pela validação
				return $this->back('form/incluir'); // Volta para a página do formulário
			try
			{
				$this->model()->beginTransaction(); // Inicia uma transação
				$this->model()->insert( // Insere os dados na tabela
					[
						'id_cli'	=> 1,
						'id_'		=> 100,
						'descricao'	=> $this->input['descricao'], // Utilizar sempre $this->input em vez de $_POST
					]
				);
				$this->model()->commit(); // Conclui a transação
				// Cria uma mensagem de status informando que a inclusão foi realizada com sucesso
				$_SESSION['Status'] = "Inclusão de '" . $this->input['descricao'] . "' realizada com sucesso";
				return $this->rotas("form/listagem"); // Volta para a página de listagem
			}
			catch(Exception $e) // em caso de exceção
			{
				$this->model()->rollBack(); // Desfaz a transação
				$_SESSION['Erro'] = $e->getMessage(); // Cria uma mensagem com o erro reportado
			  	return $this->back('form/incluir'); // Volta para o formulário de inclusão
			}
		}

		public function alterar()
		{
			$this->valida_token(); // Valida token para evitar ataque csrf
			if (!$this->valida_request()) // Se não passar pela validação
				return $this->back('form/alterar'); // Volta para a página do formulário
			try
			{
				$this->model()->find($this->filterInput('id', 'int')); // Busca o registro na tabela
				$this->model()->beginTransaction(); // Inicia uma transação
				$this->model()->update( // Altera os dados da tabela
					[
						'descricao'	=> $this->input['descricao'], // Utilizar sempre $this->input em vez de $_POST
					]
				);
				$this->model()->commit(); // Conclui a transação
				// Cria uma mensagem de status informando que a alteração foi realizada com sucesso
				$_SESSION['Status'] = "Alteração de '" . $this->input['descricao'] . "' realizada com sucesso";
				return $this->rotas("form/listagem"); // Volta para a página de listagem
			}
			catch(Exception $e) // em caso de exceção
			{
				$this->model()->rollBack(); // Desfaz a transação
				$_SESSION['Erro'] = $e->getMessage(); // Cria uma mensagem com o erro reportado
			  	return $this->back('form/alterar'); // Volta para o formulário de alteração
			}
		}

		public function excluir()
		{
			$this->valida_token(); // Valida token para evitar ataque csrf
			try
			{
				$tpaquisicoes = $this->model()->find($this->filterInput('id', 'int')); // Busca o registro na tabela
				$this->model()->beginTransaction(); // Inicia uma transação
				$this->model()->delete(); // Exclui o registro
				$this->model()->commit(); // Conclui a transação
				// Cria uma mensagem de status informando que a exclusão foi realizada com sucesso
				$_SESSION['Status'] = "Exclusão de '" . $tpaquisicoes['descricao'] . "' realizada com sucesso";
				return $this->rotas("form/listagem"); // Volta para a página de listagem
			}
			catch(Exception $e) // em caso de exceção
			{
				$this->model()->rollBack(); // Desfaz a transação
				$_SESSION['Erro'] = $e->getMessage(); // Cria uma mensagem com o erro reportado
			  	return $this->back('form/excluir'); // Volta para o formulário de alteração
			}
		}

		public function dadosListagem()
		{
			/*		
			$this->model()->raw("SELECT id_cli, id_, descricao FROM tpaquisicoes WHERE id_cli = :id_cli ORDER BY descricao ASC", [':id_cli' => '1']);
			*/
			
			$textobusca = $this->buscarpesquisa(); // Verifica se tem alguma string de busc informada pelo usuário
			$listaTudo = strlen($textobusca) >= $this->tamanhoStringBusca; // Configura pra listar todos os registros quando enviar o resultado para a view
			$count = $this->model()->count( // Obtém a quantidade de registros para esta consulta
				[
					["AND", "id_cli", "=", "1"],
					["AND", "descricao", "LIKE", "%$textobusca%"] // Se houver texto de busca informado
				]
			);
			if (!$listaTudo) // Se houver paginação, define página de offset e quantidade de registros
				$this->model()->defineLimits($this->getOffset($count), $this->getTotRegs());
			$this->model()->select( // Consulta os dados na tabela
				["id", "id_cli", "id_", "descricao"], // Pode-se usar * em vez dos campos individuais
				// [[null]], caso não utilize a clausula WHERE
				[
					["AND", "id_cli", "=", "1"],
					["AND", "descricao", "LIKE", "%$textobusca%"] // Se houver texto de busca informado
				],
				// [[null]], caso não utilize a clausula ORDER BY
				[
					["descricao", "ASC"],					
				]
			);
			$tpaquisicoes = $this->model()->all(); // Obtém todos os registros consultados
			// Retorna um array contendo todos os dados necessários para passar para a view
			return ['tpaquisicoes' => $tpaquisicoes, 'listaTudo' => $listaTudo, 'tamanhoStringBusca' => $this->tamanhoStringBusca, 'textobusca' => $textobusca, 'pagina' => $this->getPagina($count), 'paginas' => $this->getTotPaginas($count)];			
		}

		public function dadosIncluir()
		{
			if (isset($this->input))
				return ['tpaquisicoes' => $this->input]; // Retorna os dados já digitados pelo usuário
			else
				return ['tpaquisicoes' => $this->clearFields()]; // Retorna os campos vazios
		}
		
		public function dadosAlterar()
		{
			if (isset($this->input))
				return ['tpaquisicoes' => $this->input]; // Retorna os dados já digitados pelo usuário
			else
				return $this->dadosconsultar(); // Consulta a tabela para obter o registro a ser alterado
		}

		public function dadosConsultar()
		{
			$tpaquisicoes = $this->model()->find($_POST['id']); // Consulta os dados na tabela e os retorna
			return ['tpaquisicoes' => $tpaquisicoes];
		}

		public function dadosExcluir()
		{
			return $this->dadosconsultar(); // Consulta a tabela para obter o registro a ser excluído
		}
		
		public function rotas($rota)
		{
			if (!$this->validaSessao($rota)) // Se não houver sessão ativa
				return parent::rotas('form/login'); // Vai para o formulário de login

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
			parent::__construct(); // Executa o construtor da superclasse
			$this->model = new tpaquisicoesModel(); // Inicializa a model associada ao controller
		}

		public static function getInstance()
		{
	    	// Implementa o design pattern Singleton para instanciar cada classe uma única vez
	    	if (!isset(self::$instance))
	        	self::$instance = new tpaquisicoesController();
	    	return self::$instance;
	    }
	}

	// Caso não seja informada uma rota, define form/listagem como a rota padrão a ser executada para este script
	$operacao = 'form/listagem';
	if (isset($_POST['operacao']))
		$operacao = $_POST['operacao'];		
	
	$index = tpaquisicoesController::getInstance(); // Obtém a instância única desta classe
	$index->rotas($operacao); // Executa o roteamento para saber qual método do controller executar
