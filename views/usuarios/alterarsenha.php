<?php
	include_once('../parent/View.php');

	class AlterarSenhaView extends View
	{
		public function view($data)
		{
			$pag = 2; // Use esta numeração para representar o crud atual e indicar sua opção de menu como ativa
			$titulo = "Alteração de senha"; // Título da página
			$empresa = APP_EMPRESA;
			$mensagem = APP_MENSAGEM;
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
						
							<form action='../controllers/Usuarios.php' method="post">
								<input type = "hidden" name = "operacao" value = "action/alterarSenha">
								<input type = "hidden" name = "csrf" value = "<?php echo $this->csrf()?>">
								
								<div class="row">
			                        <div class="form-group col-4">
			                            <label class="form-label" for = "senhaatual">Senha atual</label>
			                            <input type="password" class="form-control" id="senhaatual" maxlength="20" name="senhaatual" autofocus>
			                        </div>
			                        <div class="form-group col-4">
			                            <label class="form-label" for = "novasenha1">Nova senha</label>
			                            <input type="password" class="form-control" id="novasenha1" maxlength="20" name="novasenha1">
			                        </div>
			                        <div class="form-group col-4">
			                            <label class="form-label" for = "novasenha2">Repita a nova senha</label>
			                            <input type="password" class="form-control" id="novasenha2" maxlength="20" name="novasenha2">
			                        </div>
			                    </div>

			                    <div class="row">
			                    	<div class="col-12">
			                    		<button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Salvar</button>
			                    		<a href="../site/Index.php" class="btn btn-light" title="Fechar'"><i class="fas fa-undo"></i> Cancelar</i></a>
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