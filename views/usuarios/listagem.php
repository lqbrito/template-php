<?php
	include_once('../parent/view.php');

	class listagemView extends View
	{
		public function view($data)
		{
			$pag = 2; // Use esta numeração para representar o crud atual e indicar sua opção de menu como ativa
			$usuarios = $data['usuarios']; // Esta variável representa a model passada para a página
			$listaTudo = $data['listaTudo']; // Indica se é para listar todos os registros da tabela
			$tamanhoStringBusca = $data['tamanhoStringBusca']; //Tamanho mínimo da string de busca
			$textobusca = $data['textobusca']; // Texto digitado pelo usuário para busca na tabela
			$pagina = $data['pagina']; // Página atual da tabela em caso de paginação
			$paginas = $data['paginas']; // Total de páginas da tabela em caso de paginação
			$titulo = "Cadastro de usuários"; // Título da página
			$empresa = APP_EMPRESA;
			$mensagem = APP_MENSAGEM;
			$campoBusca = "um nome"; // Placeholder para o campo de busca na tabela
			$controller = "usuarios.php"; // Nome do controller para retornar da página
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
								<div class="col-md-12 text-right"> 
									<div class="btn-group" role="group" aria-label="Basic example">
										<a href='../public' class="btn btn-sm btn-outline-primary mr-1 float-right"><i class="fas fa-reply"></i> Voltar</a>
										<form action='../controllers/usuarios.php' method="post">
											<input type = "hidden" name = "operacao" value = "form/incluir">
											<button type="submit" class="btn btn-sm btn-outline-primary float-right"><i class="fas fa-plus"></i> Incluir</button>
										</form>
									</div>
								</div>
							</div>
												
							<div class="table-responsive mt-3">
								<table class="table table-striped table-hover table-sm table-condensed">
									<thead class="thead-dark">
										<tr>
											<th scope="col">Nome</th>
											<th scope="col">Login</th>
											<th scope="col">Ações</th>
										</tr>
									</thead>

									<tbody>
										<?php 
										foreach($usuarios as $tp)
										{                            		
										?>
											<tr>
												<td><?php echo $tp['nome']; ?></td>
												<td><?php echo $tp['login']; ?></td>
												<td>
													<div class="btn-group">
														<form action='../controllers/usuarios.php' method="post">
															<input type = "hidden" name = "operacao" value = "form/consultar">
															<input type = "hidden" name = "id" value = "<?php echo $tp['id'] ?>">
															<button type="submit" class="btn btn-sm btn-link"><i class="fas fa-search"></i> Consultar</button>
														</form>
														<form action='../controllers/usuarios.php' method="post">
															<input type = "hidden" name = "operacao" value = "form/alterar">
															<input type = "hidden" name = "id" value = "<?php echo $tp['id'] ?>">
															<button type="submit" class="btn btn-sm btn-link"><i class="fas fa-edit"></i> Alterar</button>
														</form>
														<form action='../controllers/usuarios.php' method="post">
															<input type = "hidden" name = "operacao" value = "form/excluir">
															<input type = "hidden" name = "id" value = "<?php echo $tp['id'] ?>">
															<button type="submit" class="btn btn-sm btn-link"><i class="fas fa-trash"></i> Excluir</button>
														</form>
													</div>
												</td>
											</tr>
										<?php 
										}
										?>
									</tbody>
								</table>

								<?php 
                                    if (!$listaTudo) // Verifica se é para fazer a paginação dos dados
                                        $this->links($controller, $pagina, $paginas);
                                ?>
								
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