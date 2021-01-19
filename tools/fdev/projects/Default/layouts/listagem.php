<?php
    include_once('../parent/view.php');

    class listagemView extends View
    {
        public function view($data)
        {
            $pag = 0;
            $[nome_model] = $data['[nome_model]'];
            $listaTudo = $data['listaTudo'];
            $tamanhoStringBusca = $data['tamanhoStringBusca'];
            $textobusca = $data['textobusca'];
            $pagina = $data['pagina'];
            $paginas = $data['paginas'];
            $titulo = "Cadastro de [nome_model]";
            $campoBusca = "uma descrição";
            $controller = "[nome_controller].php";
            include_once ("../views/layouts/" . $_SESSION['app_ui'] . "_cabecalho.php");
            $this->showMessage();
            ?>

            <div class="row mt-3">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <?php echo $titulo; ?>
                        </div>

                        <div class="card-body">
                        
                            <div class="btn-group" role="group" aria-label="Basic example">
                                <a href='../public' class="btn btn-sm btn-primary mr-1"><i class="fas fa-reply"></i> Voltar</a>
                                <form action='../controllers/[nome_model].php' method="post">
                                    <input type = "hidden" name = "operacao" value = "form/incluir">
                                    <button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-plus"></i> Incluir</button>
                                </form>
                            </div>
                                                
                            <div class="table-responsive mt-3">
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
                                                        <form action='../controllers/[nome_controller].php' method="post">
                                                            <input type = "hidden" name = "operacao" value = "form/consultar">
                                                            <input type = "hidden" name = "id" value = "<?php echo $tp['id'] ?>">
                                                            <button type="submit" class="btn btn-sm btn-link"><i class="fas fa-search"></i> Consultar</button>
                                                        </form>
                                                        <form action='../controllers/[nome_controller].php' method="post">
                                                            <input type = "hidden" name = "operacao" value = "form/alterar">
                                                            <input type = "hidden" name = "id" value = "<?php echo $tp['id'] ?>">
                                                            <button type="submit" class="btn btn-sm btn-link"><i class="fas fa-edit"></i> Alterar</button>
                                                        </form>
                                                        <form action='../controllers/[nome_controller].php' method="post">
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

                                <?php if (!$listaTudo) $this->links($controller, $pagina, $paginas);?>
                                
                            </div>
                        
                        </div>

                    </div>
                </div>
            </div>

            <?php
            include_once ("../views/layouts/" . $_SESSION['app_ui'] . "_rodape.php");
        }
    }
?>