<?php
	include_once('../parent/View.php');

	class ConsultarView extends View
	{
		public function view($data)
		{
			$pag = 3; // Use esta numeração para representar o crud atual e indicar sua opção de menu como ativa
			$tpclasses = $data['tpclasses']; // Esta variável representa a model passada para a página
			$titulo = "Consulta de tipo de classe"; // Título da página
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
						
							<div class="row">
			                    <div class="col-lg-12">
			                        <dl class="row">
			                            <dt class="col-sm-2 text-right">Descrição</dt>
			                            <dd class="col-sm-10"><?php echo $tpclasses['descricao']; ?></dd>
			                        </dl>
			                    </div>
                			</div>

			                <div class="btn-group">
			                    <form action='../controllers/Tpclasses.php' method="post">
									<input type = "hidden" name = "operacao" value = "form/alterar">
									<input type = "hidden" name = "id" value = "<?php echo $tpclasses['id'] ?>">
									<button type="submit" class="btn btn-sm btn-primary mr-1"><i class="fas fa-edit"></i> Alterar</button>
								</form>											                   
			                    <form action='../controllers/tpclasses.php' method="post">
									<input type = "hidden" name = "operacao" value = "form/excluir">
									<input type = "hidden" name = "id" value = "<?php echo $tpclasses['id'] ?>">
									<button type="submit" class="btn btn-sm btn-primary mr-1"><i class="fas fa-trash"></i> Excluir</button>
								</form>
			                    
			                    <a href="../controllers/Tpclasses.php" class="btn btn-sm btn-primary mr-1"><i class="fas fa-times"></i> Fechar</i></a>
			                </div>
						
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