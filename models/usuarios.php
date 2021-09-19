<?php
	include_once('../parent/Model.php');

    class UsuariosModel extends Model
    {
        protected $table = 'usuarios'; // Nome da tabela no banco de dados
        // Lista dos campos da tabela que podem ser editados pelo usuário. Campos não autorizados não devem ser colocados aqui
        protected $fillable = [
            'nome', 'login', 'senha',
        ];
    }
