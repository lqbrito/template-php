<?php
	include_once('../parent/Model.php');

    class [nome_classe_model]Model extends Model
    {
        protected $table = '[nome_model]'; // Nome da tabela no banco de dados
        // Lista dos campos da tabela que podem ser editados pelo usuário. Campos não autorizados não devem ser colocados aqui
        protected $fillable = [ 
            [nome_campo]
        ];
    }
