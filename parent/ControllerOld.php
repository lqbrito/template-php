<?php
	session_start();

	include_once('Service.php');
	include_once('../views/usuarios/login.php');

	class Controller extends Service
	{
		public static $instance;
		
		public function pesquisar()
	    {
	    	parent::pesquisar();
	        // Volta para a página de listagem para utilizar a variável de sessão com o valor que foi digitado
	        return $this->rotas("form/index");
	    }
	    
	    public function rotas($rota)
		{
			/*  Rotas padrão para serem utilizadas em CRUD
				form/index
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
