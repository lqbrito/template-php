<?php
	include_once('../parent/model.php');

    class tpclassesModel extends Model
    {
        protected $table = 'tpclasses'; // Nome da tabela no banco de dados
        // Lista dos campos da tabela que podem ser editados pelo usuário. Campos não autorizados não devem ser colocados aqui
        protected $fillable = [
            'descricao',
        ];
    }
