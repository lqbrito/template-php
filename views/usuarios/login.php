<?php
	include_once('../parent/view.php');

	class loginView extends View
	{
		public function view($data)
		{
			$pag = 2; // Use esta numeração para representar o crud atual e indicar sua opção de menu como ativa
			$titulo = "Login no sistema"; // Título da página
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
						
							<form action='../controllers/usuarios.php' method="post">
								<input type = "hidden" name = "operacao" value = "action/login">
								<input type = "hidden" name = "csrf" value = "<?php echo $this->csrf()?>">
								
								<div class="row">
			                        <div class="form-group col-6">
			                            <label class="form-label" for = "login">Login</label>
			                            <input type="text" class="form-control" id="login" maxlength="50" name="login" required="true" autofocus>
			                        </div>
			                        <div class="form-group col-6">
			                            <label class="form-label" for = "senha">Senha</label>
			                            <input type="password" class="form-control" id="senha" maxlength="20" name="senha">
			                        </div>
			                    </div>

			                    <div class="row">
			                    	<div class="col-12">
			                    		<button type="submit" class="btn btn-primary"><i class="fas fa-door-open"></i> Entrar</button>
			                    		<a href="../public/index.php" class="btn btn-light" title="Fechar'"><i class="fas fa-undo"></i> Cancelar</i></a>
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