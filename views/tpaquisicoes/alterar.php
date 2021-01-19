<?php
	include_once('../parent/view.php');

	class alterarView extends View
	{
		public function view($data)
		{
			$pag = 3; // Use esta numeração para representar o crud atual e indicar sua opção de menu como ativa
			$tpaquisicoes = $data['tpaquisicoes']; // Esta variável representa a model passada para a página
			$titulo = "Alteração de tipo de aquisição"; // Título da página
			// Cabeçalho comum a todas as páginas
            include_once ("../views/layouts/" . $_SESSION['app_ui'] . "_cabecalho.php");
			$this->showMessage(); // Caso hajam msgs elas são mostradas ao usuário
			?>

			<div class="row mt-3">
				<div class="col-md-12">
					<div class="card">
						<div class="card-header">
							<?php echo $titulo; ?>
						</div>

						<div class="card-body">
						
							<form action='../controllers/tpaquisicoes.php' method="post">
								<input type = "hidden" name = "operacao" value = "action/alterar">
								<input type = "hidden" name = "id" value = "<?php echo $tpaquisicoes['id'] ?>">
								<input type = "hidden" name = "csrf" value = "<?php echo $this->csrf()?>">
								
								<div class="row">
			                        <div class="form-group col-12">
			                            <label class="form-label" for = "descricao">Descrição</label>
			                            <input type="text" class="form-control" id="descricao" maxlength="50" name="descricao" value="<?php echo $tpaquisicoes['descricao']; ?>" required="true" autofocus>
			                        </div>                    
			                    </div>

			                    <div class="row">
			                    	<div class="col-12">
			                    		<button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Salvar</button>
			                    		<a href="../controllers/tpaquisicoes.php" class="btn btn-light" title="Fechar'"><i class="fas fa-undo"></i> Cancelar</i></a>
			                    	</div>
			                    </div>
			                </form>
						
						</div>

					</div>
				</div>
			</div>
			
			<?php
			// Rodapé comum a todas as páginas
            include_once ("../views/layouts/" . $_SESSION['app_ui'] . "_rodape.php");
		}
	}
?>