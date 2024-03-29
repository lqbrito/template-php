<?php
    include_once('../parent/View.php');

    class IndexView extends View
    {
        public function view($data)
        {
            $pag = 0; // Use esta numeração para representar o crud atual e indicar sua opção de menu como ativa
            $[nome_model] = $data['[nome_model]']; // Esta variável representa a model passada para a página
            $listaTudo = $data['listaTudo']; // Indica se é para listar todos os registros da tabela
            $tamanhoStringBusca = $data['tamanhoStringBusca']; //Tamanho mínimo da string de busca
            $textobusca = $data['textobusca']; // Texto digitado pelo usuário para busca na tabela
            $pagina = $data['pagina']; // Página atual da tabela em caso de paginação
            $paginas = $data['paginas']; // Total de páginas da tabela em caso de paginação
            $titulo = "Cadastro de [nome_model]"; // Título da página
            $empresa = APP_EMPRESA;
            $mensagem = APP_MENSAGEM;
            $campoBusca = "uma descrição"; // Placeholder para o campo de busca na tabela
            $controller = "[nome_classe_controller].php"; // Nome do controller para retornar da página
            // Cabeçalho comum a todas as páginas
            include_once ("../views/layouts/" . $_SESSION['app_ui'] . "_cabecalho.php");
            $this->showMessage(); // Caso hajam msgs elas são mostradas ao usuário
            ?>

            <div class="row mt-3">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12">
                                    <?php echo $titulo; ?>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-10 col-sm-12 mb-3">
                                    <form action="../controllers/<?php echo $controller; ?>" method="post">
                                        <div class="input-group input-group-sm">
                                            <input type = "hidden" name = "operacao" value = "action/pesquisar">
                                            <input type="text" class="form-control" id="textobusca" name="textobusca" placeholder="Informe <?php echo $campoBusca; ?>" title="Digite pelo menos <?php echo $tamanhoStringBusca; ?> caracteres" autofocus value="<?php echo $textobusca; ?>">
                                            <span class="input-group-append">
                                                <button type = "submit" class="btn btn-primary btn-flat" id="pesquisar" name = "pesquisar" title="Pesquisar"><i class="fa fa-search"></i></button>
                                            </span>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-lg-2 col-sm-12 mb-3">
                                    <div class="btn-group float-right" role="group" aria-label="Basic example">
                                        <a href='../site' class="btn btn-sm btn-outline-primary mr-1"><i class="fas fa-reply"></i> Voltar</a>
                                        <form action='../controllers/<?php echo $controller; ?>' method="post">
                                            <input type = "hidden" name = "operacao" value = "form/incluir">
                                            <button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-plus"></i> Incluir</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover table-sm table-condensed">
                                    <thead class="thead-dark">
                                        <tr>
                                            [nome_label]
                                            <th scope="col">Ações</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php 
                                        foreach($[nome_model] as $tp)
                                        {                                   
                                        ?>
                                            <tr>
                                                [nome_campo]
                                                <td>
                                                    <div class="btn-group">
                                                        <form action='../controllers/[nome_classe_controller].php' method="post">
                                                            <input type = "hidden" name = "operacao" value = "form/consultar">
                                                            <input type = "hidden" name = "id" value = "<?php echo $tp['id'] ?>">
                                                            <button type="submit" class="btn btn-sm btn-link"><i class="fas fa-search"></i> Consultar</button>
                                                        </form>
                                                        <form action='../controllers/[nome_classe_controller].php' method="post">
                                                            <input type = "hidden" name = "operacao" value = "form/alterar">
                                                            <input type = "hidden" name = "id" value = "<?php echo $tp['id'] ?>">
                                                            <button type="submit" class="btn btn-sm btn-link"><i class="fas fa-edit"></i> Alterar</button>
                                                        </form>
                                                        <form action='../controllers/[nome_classe_controller].php' method="post">
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