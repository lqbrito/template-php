<?php
	include_once('../parent/Service.php');
	include_once('../models/[nome_classe_model].php');
	
	class [nome_classe_service]Service extends Service
	{
		function __construct()
		{
			parent::__construct(); // Executa o construtor da superclasse
			$this->model = new [nome_classe_model]Model(); // Inicializa a model associada ao serviço
		}

		public function valida_request()
		{
			$this->input = $this->filterAll(); // Retorna todos os campos de $_POST sanitizados
			$validado = true;
			
			// Utilizar sempre $this->input nas validações em vez de $_POST
			/* Exemplo de validação de campo
			if ($this->input['campo'] == "Valor qualquer" || $this->input['campo'] == "Valor qualquer")
			{
				$_SESSION ['Erros'] [] = "Mensagem de erro";
				$validado = false;
			}
			*/

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
						[nome_campo]
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
					[	// Utilizar sempre $this->input em vez de $_POST
						[nome_campo]
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
				$[nome_model] = $this->model()->find($this->filterInput('id', 'int')); // Busca o registro na tabela
				$this->model()->beginTransaction(); // Inicia uma transação
				$this->model()->delete(); // Exclui o registro
				$this->model()->commit(); // Conclui a transação
				// Cria uma mensagem de status informando que a exclusão foi realizada com sucesso
				$_SESSION['Status'] = "Exclusão de '" . $[nome_model]['descricao'] . "' realizada com sucesso";
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
			$count = 0;
			try
			{
				$textobusca = $this->buscarpesquisa(); // Verifica se tem alguma string de busca informada pelo usuário
				$listaTudo = strlen($textobusca) >= $this->tamanhoStringBusca; // Configura pra listar todos os registros quando enviar o resultado para a view
				$count = $this->model()->count( // Obtém a quantidade de registros para esta consulta
					[
						["AND", "", "=", ""], // Informar um campo ou remover esta linha
						["AND", "descricao", "LIKE", "%$textobusca%"] // Se houver texto de busca informado
					]
				);

				if (!$listaTudo) // Se houver paginação, define página de offset e quantidade de registros
					$this->model()->defineLimits($this->getOffset($count), $this->getTotRegs());

				$this->model()->select( // Consulta os dados na tabela
					["*"], // Pode-se usar * em vez dos campos individuais

					// [[null]], caso não utilize a clausula WHERE
					[
						["AND", "", "=", ""],
						["AND", "descricao", "LIKE", "%$textobusca%"] // Se houver texto de busca informado
					],

					// [[null]], caso não utilize a clausula ORDER BY
					[
						["descricao", "ASC"],					
					]
				);
							
				$[nome_model] = $this->model()->all(); // Obtém todos os registros consultados
			}
			catch (Exception $e) // em caso de exceção
			{
				$[nome_model] = null;
			}
			
			// Retorna um array contendo todos os dados necessários para passar para a view
			return ['[nome_model]' => $[nome_model], 'listaTudo' => $listaTudo, 'tamanhoStringBusca' => $this->tamanhoStringBusca, 'textobusca' => $textobusca, 'pagina' => $this->getPagina($count), 'paginas' => $this->getTotPaginas($count)];			
		}

		public function dadosIncluir()
		{
			if (isset($this->input))
				return ['[nome_model]' => $this->input]; // Retorna os dados já digitados pelo usuário
			else
				return ['[nome_model]' => $this->clearFields()]; // Retorna os campos vazios
		}
		
		public function dadosAlterar()
		{
			if (isset($this->input))
				return ['[nome_model]' => $this->input]; // Retorna os dados já digitados pelo usuário
			else
				return $this->dadosconsultar(); // Consulta a tabela para obter o registro a ser alterado
		}

		public function dadosConsultar()
		{
			$[nome_model] = $this->model()->find($_POST['id']); // Consulta os dados na tabela e os retorna
			return ['[nome_model]' => $[nome_model]];
		}

		public function dadosExcluir()
		{
			return $this->dadosconsultar(); // Consulta a tabela para obter o registro a ser excluído
		}
	
	}
