<?php
	include_once('../parent/Service.php');
	include_once('../models/Tpclasses.php');
	
	class TpclassesService extends Service
	{
		function __construct()
		{
			parent::__construct(); // Executa o construtor da superclasse
			$this->model = new TpclassesModel(); // Inicializa a model associada ao serviço
		}

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
				return false;
			try
			{
				$this->model()->beginTransaction(); // Inicia uma transação
				$this->model()->insert( // Insere os dados na tabela
					[
						'descricao'	=> $this->input['descricao'], // Utilizar sempre $this->input em vez de $_POST
					]
				);
				$this->model()->commit(); // Conclui a transação
				// Cria uma mensagem de status informando que a inclusão foi realizada com sucesso
				$_SESSION['Status'] = "Inclusão de '" . $this->input['descricao'] . "' realizada com sucesso";
				return true;
			}
			catch(Exception $e) // em caso de exceção
			{
				$this->model()->rollBack(); // Desfaz a transação
				$_SESSION['Erro'] = $e->getMessage(); // Cria uma mensagem com o erro reportado
			  	return false;
			}
		}

		public function alterar()
		{
			$this->valida_token(); // Valida token para evitar ataque csrf
			if (!$this->valida_request()) // Se não passar pela validação
				return false;
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
				return true;
			}
			catch(Exception $e) // em caso de exceção
			{
				$this->model()->rollBack(); // Desfaz a transação
				$_SESSION['Erro'] = $e->getMessage(); // Cria uma mensagem com o erro reportado
			  	return false;
			}
		}

		public function excluir()
		{
			$this->valida_token(); // Valida token para evitar ataque csrf
			try
			{
				$tpclasses = $this->model()->find($this->filterInput('id', 'int')); // Busca o registro na tabela
				$this->model()->beginTransaction(); // Inicia uma transação
				$this->model()->delete(); // Exclui o registro
				$this->model()->commit(); // Conclui a transação
				// Cria uma mensagem de status informando que a exclusão foi realizada com sucesso
				$_SESSION['Status'] = "Exclusão de '" . $tpclasses['descricao'] . "' realizada com sucesso";
				return true;
			}
			catch(Exception $e) // em caso de exceção
			{
				$this->model()->rollBack(); // Desfaz a transação
				$_SESSION['Erro'] = $e->getMessage(); // Cria uma mensagem com o erro reportado
			  	return false;
			}
		}

		public function dadosIndex()
		{
			try
			{
				/*		
				$this->model()->raw("SELECT id_cli, id_, descricao FROM tpclasses WHERE id_cli = :id_cli ORDER BY descricao ASC", [':id_cli' => '1']);
				*/
				
				$textobusca = $this->buscarpesquisa(); // Verifica se tem alguma string de busc informada pelo usuário
				$listaTudo = strlen($textobusca) >= $this->tamanhoStringBusca; // Configura pra listar todos os registros quando enviar o resultado para a view
				$count = $this->model()->count( // Obtém a quantidade de registros para esta consulta
					[
						["AND", "descricao", "LIKE", "%$textobusca%"] // Se houver texto de busca informado
					]
				);

				if (!$listaTudo) // Se houver paginação, define página de offset e quantidade de registros
					$this->model()->defineLimits($this->getOffset($count), $this->getTotRegs());

				$this->model()->select( // Consulta os dados na tabela
					["id", "descricao"], // Pode-se usar * em vez dos campos individuais
					// [[null]], caso não utilize a clausula WHERE
					[
						["AND", "descricao", "LIKE", "%$textobusca%"] // Se houver texto de busca informado
					],
					// [[null]], caso não utilize a clausula ORDER BY
					[
						["descricao", "ASC"],					
					]
				);

				$tpclasses = $this->model()->all(); // Obtém todos os registros consultados
				// Retorna um array contendo todos os dados necessários para passar para a view
				
			}
			catch (Exception $e) // em caso de exceção
			{
				$tpclasses = null;
			}

			return ['tpclasses' => $tpclasses, 'listaTudo' => $listaTudo, 'tamanhoStringBusca' => $this->tamanhoStringBusca, 'textobusca' => $textobusca, 'pagina' => $this->getPagina($count), 'paginas' => $this->getTotPaginas($count)];			
		}

		public function dadosIncluir()
		{
			if (isset($this->input))
				return ['tpclasses' => $this->input]; // Retorna os dados já digitados pelo usuário
			else
				return ['tpclasses' => $this->clearFields()]; // Retorna os campos vazios
		}
		
		public function dadosAlterar()
		{
			if (isset($this->input))
				return ['tpclasses' => $this->input]; // Retorna os dados já digitados pelo usuário
			else
				return $this->dadosconsultar(); // Consulta a tabela para obter o registro a ser alterado
		}

		public function dadosConsultar()
		{
			$tpclasses = $this->model()->find($_POST['id']); // Consulta os dados na tabela e os retorna
			return ['tpclasses' => $tpclasses];
		}

		public function dadosExcluir()
		{
			return $this->dadosconsultar(); // Consulta a tabela para obter o registro a ser excluído
		}
		
	}
