<?php
	// Definições padrão para acesso ao banco de dados
	
	if (ENVIRONMENT == 'development')
	{
		DEFINE ('DB_SGBD', 'mysql');
		DEFINE ('DB_HOST', 'localhost');
		DEFINE ('DB_NAME', 'template_php');
		DEFINE ('DB_USER', 'root');
		DEFINE ('DB_PASSWORD', '');	
	}
	
	if (ENVIRONMENT == 'test')
	{
		DEFINE ('DB_SGBD', 'mysql');
		DEFINE ('DB_HOST', 'localhost');
		DEFINE ('DB_NAME', 'template_php');
		DEFINE ('DB_USER', 'root');
		DEFINE ('DB_PASSWORD', '');	
	}
	
	if (ENVIRONMENT == 'production')
	{
		DEFINE ('DB_SGBD', 'mysql');
		DEFINE ('DB_HOST', 'localhost');
		DEFINE ('DB_NAME', 'template_php');
		DEFINE ('DB_USER', 'root');
		DEFINE ('DB_PASSWORD', '');	
	}
	
	class Connect
	{
		public static $instance;
		
		protected $pdo;
		protected $sgbd = DB_SGBD;
		protected $servername = DB_HOST;
		protected $databasename = DB_NAME;
		protected $username = DB_USER;
		protected $password = DB_PASSWORD;

		function __construct()
		{
			try 
			{
				$this->pdo = new PDO("$this->sgbd:host=$this->servername;dbname=$this->databasename", $this->username, $this->password); // Conecta com o banco de dados
				// Define atributos padrão para a conexão
				$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			}
			catch(PDOException $e) 
			{
				echo "<div class='alert alert-danger alert-dismissible'>";
		        echo "<button type='button' class='close' data-dismiss='alert' aria-hIDden='true'>×</button>";
		        echo "<h4><i class='icon fa fa-info'></i> Erro!</h4>";
		        echo $e->getMessage();
		        echo "</div>";
			}
		}

		public static function getInstance()
		{
	    	// Implementa o design pattern Singleton para instanciar cada classe uma única vez
	    	if (!isset(self::$instance))
	        	self::$instance = new Connect();
	    	return self::$instance;
	    }
	
		public function getPdo()
		{
			return $this->pdo; // Retorna a conexão PDO da aplicação
		}
	}
