<?php
	include_once('../parent/model.php');

    class tpaquisicoesModel extends Model
    {
        protected $table = 'tpaquisicoes'; // Nome da tabela no banco de dados
        // Lista dos campos da tabela que podem ser editados pelo usuário. Campos não autorizados não devem ser colocados aqui
        protected $fillable = [
            'id_cli', 'id_', 'descricao',
        ];
    }
