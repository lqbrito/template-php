<?php
include_once('../config/Connect.php');

class Model
{
		protected $conn; // Aponta para a conexão PDO da aplicação
		protected $table = ''; // Nome da tabela acessada pela model
        protected $primaryKey = 'id'; // Nome do campo que é chave primária da tabela
        protected $valueprimaryKey; // Armazena o valor da chave primária do registro acessado pela model
        protected $timestamps = true; // Define se a model utiliza os campos de timestamp
        protected $fillable = []; // Armazena os nomes dos campos da tabela que tem permissão para ser editados
        protected $result; // Recebe o resultado de uma query preparada pela conexão PDO
        protected $rows; // Recebe um array contendo os registros retornados por uma query
        protected $pagina = -1; // Informa qual a página de offset em uma query que utiliza a cláusula LIMIT
        protected $totRegs = -1; // Indica a quantidade de registros por página em uma query com LIMIT

        public function dd($dados)
        {
        	var_dump($dados);
        	die();
        }

        public function defineLimits($pagina = -1, $totRegs = -1)
        {
        	// Define a página de offset e a quantidade de registros que serão retornados quando uma query utilizar a clausula LIMIT
        	$this->pagina =  $pagina;
        	$this->totRegs =  $totRegs;
        }

        public function getFields()
        {
        	return $this->fillable; // Retorna o vetor de campos editáveis da tabela
        }

        public function beginTransaction()
        {
        	$this->getConnect()->getPdo()->beginTransaction(); // Inicia uma transação
        }

        public function commit()
        {
        	$this->getConnect()->getPdo()->commit(); // Completa a transação
        }

        public function rollBack()
        {
        	$this->getConnect()->getPdo()->rollBack(); // Desfaz a transação
        }

        public function select($select, $where = null, $orderby = null, $raw = null, $apelido = "m")
        {
			// Executa uma query com diversas possibilidades de parâmetros
			// $select é um array que indica os campos a serem retornados pela query. Para retornar todos, basta usar [*]
			// $where é um array de arrays, onde cada array informa o campo, o operador relacional e o valor a ser comparado
			// $orderby é um array de arrays, onde cada array informa um campo para ordenar e o tipo de ordenação ASC ou DESC
			// $raw é uma string crua com comandos SQL que será anexada ao final da consulta

        	$st = 'SELECT ';
        	$tam = count($select);
			for ($i = 0; $i < $tam; $i++) // Adiciona cada campo de $select à query
			{
				$st .= $select[$i];
				if ($i < $tam - 1)
					$st .= ', ';
				else
					$st .= ' ';
			}

			$st .= 'FROM ' . $this->table . " $apelido";	

            if (isset($raw)) // Adiciona a string $raw à query
            $st .= $raw;

            $st .= ' WHERE 1=1 ';
            $binds = array();
            if (isset($where))
            {
            	$tam = count($where);
				for ($i = 0; $i < $tam; $i++) // Adiciona cada condição do $where à query
				{
					$ress = mb_strpos($where[$i][3], "*");
					$temParametro = $ress === false;
					$tam2 = count($where[$i]);
					for ($k = 0; $k < $tam2; $k++)
					{
						if ($k < $tam2 - 1)
							$st .= $where[$i][$k] . ' ';
						if ($k == 1 && $temParametro)
						{
							$parametro = $where[$i][$k];
							$res = mb_strpos($parametro, ".");
							if(!$res === false)
								$parametro = mb_substr($parametro, $res + 1);
							$binds [$parametro] = $where[$i][$k + 2];
						}
					}					
					if ($temParametro)
					{
						$parametro = $where[$i][1];
						$res = mb_strpos($parametro, ".");
						if(!$res === false)
							$parametro = mb_substr($parametro, $res + 1);
						$st .= ':' . $parametro . ' ';	
					}
					else
						$st .= mb_substr($where[$i][3], $ress + 1) . ' ';		

				}
			}

			if (isset($orderby))
			{
				$st .= 'ORDER BY';	

				$tam = count($orderby);
				for ($i = 0; $i < $tam; $i++) // Adiciona cada campo de $orderby à query
				{
					$tam2 = count($orderby[$i]);
					for ($k = 0; $k < $tam2; $k++)
						if($i == 0)
						{
							$st .= ' ' . $orderby[$i][$k];
						}
						else
						{
							if($k == 0)
								$st .= ', ' . $orderby[$i][$k];
							else
								$st .= ' ' . $orderby[$i][$k];
						}
					}
					$st .= ' ';
				}

			// Se foram definidos um offset e uma quantidade de registros, adiciona a cláusula LIMIT à query
			if($this->pagina != -1 && $this->totRegs != -1)
				$st .= " LIMIT $this->pagina, $this->totRegs";

			//$this->dd([$st, $binds]);

			$this->result = $this->getConnect()->getPdo()->prepare($st); // Prepara a query
			foreach ($binds as $key => $value)
			{
				$this->result->bindValue(":" . $key, $value); // Associa os valores aos parâmetros da query
			}			
			$this->result->execute(); // Executa a query
			$this->rows = null;
			$this->rows = array();
			$this->valueprimaryKey = null;
			while ($row = $this->result->fetch())
				$this->rows[] = $row; // Atribui o resultado da consulta ao atributo rows
			return $this->all(); // Devolve o conteúdo do atributo rows
		}

		public function count($where = null, $raw = null)
		{
			// Executa uma query com COUNT com diversas possibilidades de parâmetros
			// $where é um array de arrays, onde cada array informa o campo, o operador relacional e o valor a ser comparado
			// $raw é uma string crua com comandos SQL que será anexada ao final da consulta
			
			$st = "SELECT COUNT($this->primaryKey) AS _count FROM " . $this->table . ' WHERE 1=1 ';
			
			$binds = array();
			if (isset($where))
			{
				$tam = count($where);
				for ($i = 0; $i < $tam; $i++) // Adiciona cada condição do $where à query
				{
					$tam2 = count($where[$i]);
					for ($k = 0; $k < $tam2; $k++)
					{
						if ($k < $tam2 - 1)
							$st .= $where[$i][$k] . ' ';
						if ($k == 1)
							$binds [$where[$i][$k]] = $where[$i][$k + 2];
					}
					$st .= ':' . $where[$i][1] . ' ';
				}
			}

            if (isset($raw)) // Adiciona a string $raw à query
            $st .= $raw;

			//var_dump($st);
			//die();
            $this->result = $this->getConnect()->getPdo()->prepare($st);
            foreach ($binds as $key => $value)
            {
            	$this->result->bindValue(":" . $key, $value);
            }			
			$this->result->execute(); // Executa a query
			$row = $this->result->fetch();
			//var_dump($row);
			//die();
			return $row['_count']; // Retorna o valor que foi obtido pela query
		}

		public function raw($st, $param = null)
		{
			// Executa uma query crua
			// Se a query tiver parâmetros, deve ser passado um array com cada posição indexada por :NomeDoParâmetro e armazenando o seu valor
			$this->result = $this->getConnect()->getPdo()->prepare($st);
			if (isset($param))
				foreach ($param as $p => $valor)
				{
					$this->result->bindValue($p, $valor);
				}
				$this->result->execute();			
				$this->rows = null;
				$this->rows = array();
				$this->valueprimaryKey = null;
				while ($row = $this->result->fetch())
				$this->rows[] = $row; // Atribui o resultado da consulta ao atributo rows
			return $this->all(); // Devolve o conteúdo do atributo rows
		}

		public function find($id) // Localiza um registro com base na sua chave primária
		{
			$st = 'SELECT * FROM ' . $this->table . ' WHERE ' . $this->primaryKey . '= :' . $this->primaryKey;
			
			$this->result = $this->getConnect()->getPdo()->prepare($st);

			$this->result->bindValue(':' . $this->primaryKey, $id);
			$this->result->execute();			
			$this->rows = null;
			$this->rows = array();
			$this->valueprimaryKey = $id;
			while ($row = $this->result->fetch())
				$this->rows[] = $row; // Atribui o resultado da consulta ao atributo rows
			return $this->first(); // Retorna apenas o registro armazenado na primeira posição de rows
		}

		public function all()
		{
			return $this->rows; // Retorna todo o conjunto de linhas de uma consulta
		}

		public function first()
		{
			if (isset($this->rows[0]))
				return $this->rows[0]; // Retorna apenas a primeira linha de uma consulta
			else
				return null;
		}

		public function insert($dados, $ativavalidacao = true)
		{
			// Insere um registro na tabela
			// Se o parâmetro $ativavalidacao for true, só são adicionados à query os campos informados no atributo fillable
			
			// se não passar nenhum dado ou se não tiver o que validar, finaliza o método
			if (($ativavalidacao && count($this->fillable) == 0) || count($dados) == 0)
				return false;

			$st1 = 'INSERT INTO ' . $this->table . ' ('; 
			$st2 = ') VALUES (';
			$cont = 0;
			$binds = array();
			if ($ativavalidacao) // Se utilizar a validação
			{
				foreach ($this->fillable as $f) // Só insere na query os campos informados em fillable
				{
					if (isset($dados[$f]))
					{
						if($cont > 0)
						{
							$st1 .= ', ';
							$st2 .= ', ';
						}
						$cont++;
						$st1 .= $f;
						$st2 .= ':' . $f;
						$binds[':' . $f] = $dados[$f];
					}						
				}
			}
			else // Se não utilizar a validação
			{
				foreach ($dados as $key => $value) // Insere na query todos os campos que foram passados
				{
					if($cont > 0)
					{
						$st1 .= ', ';
						$st2 .= ', ';
					}
					$cont++;
					$st1 .= $key;
					$st2 .= ':' . $key;
					$binds[':' . $key] = $value;
				}
			}			
			$st3 = ')';
			if($this->timestamps) // Se utilizar timestamps
			{
				$data = new DateTime();
             	$st1 .= ', created_at, updated_at'; // Insere a data e a hora atuais
             	$st2 .= ', \'' . $data->format('Y-m-d H:i:s') . '\', \'' . $data->format('Y-m-d H:i:s') . '\'';
             }
             $st = $st1 . $st2 . $st3;
			//var_dump($st);
			//die();
             $this->result = $this->getConnect()->getPdo()->prepare($st);
             foreach ($binds as $key => $value)
             {
             	$this->result->bindValue($key, $value);
             }

             if($this->result->execute())
             	return $this->raw("SELECT MAX($this->primaryKey) FROM $this->table");
             else
             	return false;
         }

         public function update($dados, $ativavalidacao = true)
         {				
			// Altera um registro localizado pelo método find
			// Se o parâmetro $ativavalidacao for true, só são adicionados à query os campos informados no atributo fillable

         	$_id = -1;

			// Se chamar o método update sem ter feito uma consulta na tabela, não faz nada
         	if (isset($this->valueprimaryKey)) 
         		$_id = $this->valueprimaryKey;
         	else
         		return false;

			// se não passar nenhum dado ou se não tiver o que validar, finaliza o método
         	if (($ativavalidacao && count($this->fillable) == 0) || count($dados) == 0)
         		return false;

         	$st1 = 'UPDATE ' . $this->table;
         	$st2 = ' SET ';
         	$cont = 0;
         	$binds = array();
			if ($ativavalidacao) // Se utilizar a validação
			{
				foreach ($this->fillable as $f) // Só insere na query os campos informados em fillable
				{
					if (isset($dados[$f]))
					{
						if($cont > 0)
							$st2 .= ', ';
						$cont++;
						$st2 .= $f . ' = :' . $f;
						$binds[':' . $f] = $dados[$f];
					}
				}
			}
			else // Se não utilizar a validação
			{
				foreach ($dados as $key => $value) // Insere na query todos os campos que foram passados
				{
					if($cont > 0)
						$st2 .= ', ';
					$cont++;
					$st2 .= $key . ' = :' . $key;
					$binds[':' . $key] = $value;
				}
			}
			$st3 = ' WHERE ' . $this->primaryKey . ' = :' . $this->primaryKey;
			if($this->timestamps) // Se utilizar timestamps
			{
				$data = new DateTime();
             	$st2 .= ', updated_at = '; // Insere a data e a hora atuais
             	$st2 .= '\'' . $data->format('Y-m-d H:i:s') . '\'';
             }			
             $st = $st1 . $st2 . $st3;
			//var_dump($st);
			//die();
             $this->result = $this->getConnect()->getPdo()->prepare($st);
             $this->result->bindValue(':' . $this->primaryKey, $_id);
             foreach ($binds as $key => $value)
             {
             	$this->result->bindValue($key, $value);
             }

             return $this->result->execute();
         }

         public function delete()
         {
			// Exclui um registro localizado pelo método find

         	$_id = -1;

			// Se chamar o método delete sem ter feito uma consulta na tabela, não faz nada
         	if (isset($this->valueprimaryKey))
         		$_id = $this->valueprimaryKey;
         	else
         		return false;

         	$st = 'DELETE FROM ' . $this->table . ' WHERE ' . $this->primaryKey . '= :' . $this->primaryKey;

			//var_dump($st);
			//die();
         	$this->valueprimaryKey = null;
         	$this->result = $this->getConnect()->getPdo()->prepare($st);
         	$this->result->bindValue(':' . $this->primaryKey, $_id);
         	return $this->result->execute();			
         }

         private function fetch($result = null)
         {
			// Converte o resultado de uma consulta em um array e devolve o array
         	if ($result != null)
         		$this->result = $result;			
         	$row = $this->result->fetch(PDO::FETCH_OBJ);			
         	return $row;
         }

         public function getConnect()
         {
			return $this->conn; // Retorna o objeto PDO da conexão
		}

		function __construct()
		{
			$this->conn = connect::getInstance(); // Obtém o objeto PDO da conexão
			$this->valueprimaryKey = null; // Inicializa a classe sem nenhum valor de chave primária
		}		
	}
