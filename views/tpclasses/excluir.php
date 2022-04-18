<?php
    include_once('../parent/View.php');

    class ExcluirView extends View
    {
        public function view($data)
        {
            $pag = 0; // Use esta numeração para representar o crud atual e indicar sua opção de menu como ativa
            $tpclasses = $data['tpclasses']; // Esta variável representa a model passada para a página
            $titulo = "Exclusão de tpclasses"; // Título da página
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
                                        <dt class='col-sm-2 text-right'>id</dt>
                                        <dd class='col-sm-10'><?php echo $tpclasses['id']; ?></dd>
                                        <dt class='col-sm-2 text-right'>descricao</dt>
                                        <dd class='col-sm-10'><?php echo $tpclasses['descricao']; ?></dd>
                                        <dt class='col-sm-2 text-right'>created_at</dt>
                                        <dd class='col-sm-10'><?php echo $tpclasses['created_at']; ?></dd>
                                        <dt class='col-sm-2 text-right'>updated_at</dt>
                                        <dd class='col-sm-10'><?php echo $tpclasses['updated_at']; ?></dd>
                                    </dl>
                                </div>
                            </div>

                            <form action='../controllers/Tpclasses.php' method="post">

                                <input type = "hidden" name = "operacao" value = "action/excluir">
                                <input type = "hidden" name = "id" value = "<?php echo $tpclasses['id']; ?>">
                                <input type = "hidden" name = "csrf" value = "<?php echo $this->csrf()?>">

                                <div class="row">
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i> Excluir</button>
                                        <a href="../controllers/Tpclasses.php" class="btn btn-light" title="Fechar'"><i class="fas fa-undo"></i> Cancelar</i></a>
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