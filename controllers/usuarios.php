<?php
	include_once('../parent/controller.php');
	include_once('../models/usuarios.php');
	include_once('../views/usuarios/listagem.php');
	include_once('../views/usuarios/incluir.php');
	include_once('../views/usuarios/consultar.php');
	include_once('../views/usuarios/alterar.php');
	include_once('../views/usuarios/excluir.php');
	include_once('../views/usuarios/alterarsenha.php');
	include_once('../views/public/index.php');
	
	class usuariosController extends Controller
	{
		public function valida_request()
		{
			$this->input = $this->filterAll(); // Retorna todos os campos de $_POST sanitizados
			$validado = true;
			
			// Utilizar sempre $this->input nas validações em vez de $_POST
			
			return $validado;
		}

		public function alterarSenha()
	    {
	        $this->valida_token(); // Valida token para evitar ataque csrf
			if (!$this->valida_request()) // Se não passar pela validação
				return $this->back('form/alterarsenha'); // Volta para a página de alteração de senha
			try
			{
				$this->model()->find($_SESSION['id_usuario']); // Procura pelo usuário na tabela
				$usuarios = $this->model()->first(); // Pega o primeiro (e único) registro
				$st1 = $this->criptografaSenha($_SESSION['login_usuario'], $this->input['senhaatual']);
				$st2 = $usuarios['senha'];
				
				if ($st1 != $st2) // Verifica se o usuário informou corretamente a sua senha atual
				{
					$_SESSION['Erro'] = "Sua senha atual não confere com a senha informada";
					return $this->back('form/alterarsenha');
				}

				// Verifica se o usuário digitou a nova senha duas vezes sem errar
				if ($this->input['novasenha1'] != $this->input['novasenha2'])
				{
					$_SESSION['Erro'] = "As duas digitações da nova senha devem ser iguais";
					return $this->back('form/alterarsenha');
				}
				
				$this->model()->beginTransaction(); // Inicia uma transação
				$this->model()->update( // Altera a senha do usuário pela nova senha informada
					[
						'senha'	=> $this->criptografaSenha($_SESSION['login_usuario'], $this->input['novasenha1'])
					]
				);
				$this->model()->commit(); // Conclui a transação
				// Cria uma mensagem de status informando que a senha foi alterada com sucesso
				$_SESSION['Status'] = "Senha alterada com sucesso";
				return $this->rotas("form/index"); // Vai para o formulário principal do sistema
			}
			catch(Exception $e) // em caso de exceção
			{
				$this->model()->rollBack(); // Desfaz a transação
				$_SESSION['Erro'] = $e->getMessage(); // Cria uma mensagem com o erro reportado
			  	return $this->back('form/alterarsenha'); // Volta para o formulário de alteração de senha
			}
	    }
	    
	    public function login()
	    {
	    	$this->valida_token(); // Valida token para evitar ataque csrf
			if (!$this->valida_request()) // Se não passar pela validação
				return $this->back('form/login'); // Volta para a página de login
			$this->model()->select( // Consulta os dados do usuário na tabela através do seu login
				["id", "nome", "login", "senha"],
				[
					["AND", "login", "=", $this->input['login']]
				]
			);
			$usuarios = $this->model()->first(); // Obtém a primeira (e única) ocorrência
			if($usuarios == null) // Se o usuário não existir
			{
				$_SESSION ['Erro'] = "Login e/ou senha inválidos";
				$this->rotas("form/login");
			}
			else // Se encontrou o usuário
			{
				$st1 = $this->criptografaSenha($this->input['login'], $this->input['senha']);
				$st2 = $usuarios['senha'];
				// Verifica a senha informada
				if ($this->input['login'] == $usuarios['login'] && $st1 == $st2) // Se a senha está correta
				{
					$this->iniciaSessao(); // Inicia a variável de sessão com os parâmetros abaixo
					$_SESSION['id_usuario'] = $usuarios['id'];
					$_SESSION['nome_usuario'] = $usuarios['nome'];
					$_SESSION['login_usuario'] = $usuarios['login'];
					$this->rotas("form/index"); // Vai para o formulário principal do sistema
				}
				else // Se a senha está incorreta
				{
					$_SESSION ['Erro'] = "Login e/ou senha inválidos";
					$this->rotas("form/login"); // Volta para o formulário de login
				}
			}
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
						'nome'	=> $this->input['nome'],
						'login'	=> $this->input['login'], // Utilizar sempre $this->input em vez de $_POST
					]
				);
				$this->model()->commit(); // Conclui a transação
				// Cria uma mensagem de status informando que a inclusão foi realizada com sucesso
				$_SESSION['Status'] = "Inclusão de '" . $this->input['nome'] . "' realizada com sucesso";
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
						'nome'	=> $this->input['nome'],
						'login'	=> $this->input['login'], // Utilizar sempre $this->input em vez de $_POST
					]
				);
				$this->model()->commit(); // Conclui a transação
				// Cria uma mensagem de status informando que a alteração foi realizada com sucesso
				$_SESSION['Status'] = "Alteração de '" . $this->input['nome'] . "' realizada com sucesso";
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
				$usuarios = $this->model()->find($this->filterInput('id', 'int')); // Busca o registro na tabela
				$this->model()->beginTransaction(); // Inicia uma transação
				$this->model()->delete(); // Exclui o registro
				$this->model()->commit(); // Conclui a transação
				// Cria uma mensagem de status informando que a exclusão foi realizada com sucesso
				$_SESSION['Status'] = "Exclusão de '" . $usuarios['nome'] . "' realizada com sucesso";
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
			$this->model()->raw("SELECT id_cli, id_, descricao FROM usuarios WHERE id_cli = :id_cli ORDER BY descricao ASC", [':id_cli' => '1']);
			*/
			
			$textobusca = $this->buscarpesquisa(); // Verifica se tem alguma string de busc informada pelo usuário
			$listaTudo = strlen($textobusca) >= $this->tamanhoStringBusca; // Configura pra listar todos os registros quando enviar o resultado para a view
			$count = $this->model()->count( // Obtém a quantidade de registros para esta consulta
				[
					["AND", "nome", "LIKE", "%$textobusca%"] // Se houver texto de busca informado
				]
			);
			if (!$listaTudo) // Se houver paginação, define página de offset e quantidade de registros
				$this->model()->defineLimits($this->getOffset($count), $this->getTotRegs());
			$this->model()->select( // Consulta os dados na tabela
				["id", "nome", "login"], // Pode-se usar * em vez dos campos individuais
				// [[null]], caso não utilize a clausula WHERE
				[
					["AND", "nome", "LIKE", "%$textobusca%"] // Se houver texto de busca informado
				],
				// [[null]], caso não utilize a clausula ORDER BY
				[
					["nome", "ASC"],					
				]
			);
			
			$usuarios = $this->model()->all(); // Obtém todos os registros consultados
			// Retorna um array contendo todos os dados necessários para passar para a view
			return ['usuarios' => $usuarios, 'listaTudo' => $listaTudo, 'tamanhoStringBusca' => $this->tamanhoStringBusca, 'textobusca' => $textobusca, 'pagina' => $this->getPagina($count), 'paginas' => $this->getTotPaginas($count)];			
		}

		public function dadosIncluir()
		{
			if (isset($this->input))
				return ['usuarios' => $this->input]; // Retorna os dados já digitados pelo usuário
			else
				return ['usuarios' => $this->clearFields()]; // Retorna os campos vazios
		}
		
		public function dadosAlterar()
		{
			if (isset($this->input))
				return ['usuarios' => $this->input]; // Retorna os dados já digitados pelo usuário
			else
				return $this->dadosconsultar(); // Consulta a tabela para obter o registro a ser alterado
		}

		public function dadosConsultar()
		{
			$usuarios = $this->model()->find($_POST['id']); // Consulta os dados na tabela e os retorna
			return ['usuarios' => $usuarios];
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
			if ($rota == 'form/index')
				return new indexView();
			if ($rota == 'form/alterarsenha')
				return new alterarsenhaView();
			
			// Rotas de ações
			if ($rota == 'action/pesquisar')
				return $this->pesquisar();
			if ($rota == 'action/incluir')
				return $this->incluir();
			if ($rota == 'action/alterar')
				return $this->alterar();
			if ($rota == 'action/excluir')
				return $this->excluir();
			if ($rota == 'action/login')
				return $this->login();
			if ($rota == 'action/alterarsenha')
				return $this->alterarSenha();

			// Caso não tenha sido informada nenhuma das rotas acima
			return null;
		}

		function __construct()
		{
			parent::__construct(); // Executa o construtor da superclasse
			$this->model = new usuariosModel(); // Inicializa a model associada ao controller
		}

		public static function getInstance()
		{
	    	// Implementa o design pattern Singleton para instanciar cada classe uma única vez
	    	if (!isset(self::$instance))
	        	self::$instance = new usuariosController();
	    	return self::$instance;
	    }
	}

	// Caso não seja informada uma rota, define form/listagem como a rota padrão a ser executada para este script
	$operacao = 'form/listagem';
	if (isset($_POST['operacao']))
		$operacao = $_POST['operacao'];		
	
	$index = usuariosController::getInstance(); // Obtém a instância única desta classe
	$index->rotas($operacao); // Executa o roteamento para saber qual método do controller executar
