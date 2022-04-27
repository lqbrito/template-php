<?php
	DEFINE ('APP_UI', 'architectui'); // default, architectui, monster
	DEFINE ('APP_EMPRESA', 'Nome da empresa');
	DEFINE ('APP_MENSAGEM', 'Mensagem para o usuário.');
	DEFINE ('APP_ICONE', 'home');
	DEFINE ('ENVIRONMENT', 'development');
	//DEFINE ('ENVIRONMENT', 'test');
	//DEFINE ('ENVIRONMENT', 'production');

	
	class App
	{
		public static $instance;
		
		protected $app_ui = APP_UI;

		function __construct()
		{
			$_SESSION['app_ui'] = $this->app_ui; // Define qual GUI utilizar
			date_default_timezone_set('America/Sao_Paulo'); // Define timezone padrão
		}

		public static function getInstance()
		{
	    	// Implementa o design pattern Singleton para instanciar cada classe uma única vez
	    	if (!isset(self::$instance))
	        	self::$instance = new App();
	    	return self::$instance;
	    }
	}
