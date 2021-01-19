<?php
	session_start();

	include_once('../config/app.php');
	include_once('view.php');
	include_once('../views/usuarios/login.php');

	class Controller
	{
		public static $instance;
		
		protected $model; // Associa este controller a alguma model
		protected $tamanhoStringBusca = 3; // Define o tamanho mínimo para uma string de busca nas listagens
    	protected $limiteRegistros = 30; // Define a quantidade máxima de registros recuperados em uma consulta sem paginação
    	protected $totRegs = 10; // Define a quantidade de registros recuperados em uma consulta com paginação
    	
	    function __construct()
		{
			$app = App::getInstance(); // Obtém a instância da classe de configuração App
		}

    	public function getTotPaginas($count)
		{
			// Obtém a quantidade total de páginas obtidas em uma consulta com paginação. $count representa uma contagem dos registros obtidos pela consulta com paginação
			$qtde_pg = ceil ($count / $this->getTotRegs());

			return $qtde_pg;
		}

		public function getOffset($count)
		{
			// Obtém o deslocamento para uma consulta com paginação usando a cláusula LIMIT do SGBD. $count representa uma contagem dos registros obtidos pela consulta com paginação
			$pagina = $this->getPagina($count);
		  	$inicio = ($this->getTotRegs() * $pagina) - $this->getTotRegs();
			return $inicio;
		}

		public function getPagina($count)
		{
			// Retorna o número da página sem deixar ultrapassar os limites superior e inferior de quantidade de páginas
			$qtde_pg = $this->getTotPaginas($count);

			if (!isset($_GET['pagina']))
				$pagina_atual = 1;
			else
			{
				$pagina_atual = $_GET['pagina'];
				if ($pagina_atual < 1)
					$pagina_atual = 1;
				else
					if ($pagina_atual > $qtde_pg)
						$pagina_atual = $qtde_pg;
			}				
						
		  	return $pagina_atual;
		}

		public function getTotRegs()
		{
			return $this->totRegs; // Retorna o total de registros da consulta
		}

		public function setTotRegs($tot)
		{
			$this->totRegs =  $tot; // Registra o total de registros obtidos pela consulta
		}

		public function incluir()
		{
			// Este método deve ser definido em uma subclasse
		}

		public function alterar()
		{
			// Este método deve ser definido em uma subclasse	
		}

		public function excluir()
		{
			// Este método deve ser definido em uma subclasse
		}

		public function criptografaSenha($login, $senha)
	    {        
	    	// Criptografa a senha usando o login mais a senha para ter mais alternativas de senhas sem repetição
	        return strtoupper(hash('sha256', $login . md5($senha)));
	    }

	    public function iniciaSessao()
		{
			$_SESSION['logged'] = true; // Inicia a sessão informando que o usuário está logado
		}
		
		public function finalizaSessao()
		{
			$_SESSION['logged'] = false; // Finaliza a sessão atual
		}
		
		public function validaSessao($rota)
		{
			// Valida a sessão verificando se um usuário está ou não logado
			if ($rota == "action/login")
				return true;
			if (isset($_SESSION['logged']))
				return $_SESSION['logged'];
			else
				return false;
		}
		
		public function filterAll($origem = INPUT_POST)
	    {
	    	// Sanitiza todos os campos enviados ao script via GET ou POST
	    	if ($origem == INPUT_POST)
	    		$vetOrigem = $_POST;
	    	else
	    		$vetOrigem = $_GET;
    		
    		$dados = array();
	    	
	    	foreach ($vetOrigem as $key => $value)
    		{
    			$dados[$key] = filter_input($origem, $key, FILTER_SANITIZE_SPECIAL_CHARS);
    		}	    	
	    	
	    	return $dados;
	    }
	    
	    public function filterInput($campo, $tipo = 'specialchar', $origem = INPUT_POST)
	    {
	    	// Sanitiza um único campo enviado ao script via GET ou POST informando qual tipo se deseja fazer a sanitização
	    	if ($tipo == 'specialchar')
	    		$op = FILTER_SANITIZE_SPECIAL_CHARS;
	    	if ($tipo == 'int')
	    		$op = FILTER_SANITIZE_NUMBER_INT;
	    	if ($tipo == 'float')
	    		$op = FILTER_SANITIZE_NUMBER_FLOAT;
	    	if ($tipo == 'email')
	    		$op = FILTER_SANITIZE_EMAIL;
	    	if ($tipo == 'string')
	    		$op = FILTER_SANITIZE_STRING;
	    	if ($tipo == 'url')
	    		$op = FILTER_SANITIZE_URL;
	    	
	    	return filter_input($origem, $campo, $op);
	    }
	    
	    public function pesquisar()
	    {
	    	// Obtém um valor de pesquisa digitado pelo usuário em uma página de listagem e armazena na variável de sessão
	        $textobusca = '';
	        if (isset($_POST['textobusca']))
	            $textobusca = $_POST['textobusca'];
	        $_SESSION['Pesquisa'] = $textobusca;
	        // Volta para a página de listagem para utilizar a variável de sessão com o valor que foi digitado
	        return $this->rotas("form/listagem");
	    }
	    
	    public function buscarpesquisa()
	    {
	        $textobusca = '';
	        if (isset($_SESSION['Pesquisa']))
	        {
	        	// Armazena o valor de pesquisa digitado pelo usuário na variável $textobusca
	            $textobusca = $_SESSION['Pesquisa']; 
	            // Limpa a variável de sessão para esquecer o valor de busca que foi informado
	            unset($_SESSION['Pesquisa']);
	        }   
	        return $textobusca;     
	    }

		public function clearFields()
		{
			$valores = array();
			$campos = $this->model()->getFields(); // Obtém os nomes dos campos editáveis configurados na model
			foreach ($campos as $c)
				$valores[$c] = ""; // Limpa o valor desse campo
			return $valores; // Retorna o array de campos com todos os valores em branco
		}

		public function valida_token()
		{
			// Valida o csrf armazenado em um formulário enviado via POST 
			if (!(isset($_POST['csrf']) && $_POST['csrf'] == $_SESSION['csrf']))
				die("Operação não autorizada!");
		}
		
		public function valida_request()
		{
			return true; // Este método originalmente valida todos os dados enviados por um form e deve ser redefinido em uma subclasse para validação individual de cada dado enviado pelo formulário
		}
		
		public function model()
		{
			return $this->model; // Retorna a model associada a este controller
		}
				
		public function rotas($rota)
		{
			/*  Rotas padrão para serem utilizadas em CRUD
				form/listagem
				form/incluir
				form/consultar
				form/alterar
				form/excluir
				form/login
				action/pesquisar
				action/incluir
				action/alterar
				action/excluir
			*/
			if ($rota == 'form/login') // Para uma rota de login faz o direcionamento para a página de login
				return new loginView();
			return null;
		}

		public function back($rota = 'form/index')
		{
			// Volta para uma rota anterior ou para a rota padrão form/index
			return $this->rotas($rota);
		}
	}
