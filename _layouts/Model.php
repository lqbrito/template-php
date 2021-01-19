<?php
	include_once('../parent/model.php');

    class [nome_model]Model extends Model
    {
        protected $table = '[nome_model]'; // Nome da tabela no banco de dados
        // Lista dos campos da tabela que podem ser editados pelo usuário. Campos não autorizados não devem ser colocados aqui
        protected $fillable = [ 
            [nome_campo]
        ];
    }
