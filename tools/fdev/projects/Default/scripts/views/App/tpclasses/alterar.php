<?php
    include_once('../parent/View.php');

    class AlterarView extends View
    {
        public function view($data)
        {
            $pag = 0; // Use esta numeração para representar o crud atual e indicar sua opção de menu como ativa
            $tpclasses = $data['tpclasses']; // Esta variável representa a model passada para a página
            $titulo = "Alteração de tpclasses"; // Título da página
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
                        
                            <form action='../controllers/Tpclasses.php' method="post">
                                <input type = "hidden" name = "operacao" value = "action/alterar">
                                <input type = "hidden" name = "id" value = "<?php echo $tpclasses['id'] ?>">
                                <input type = "hidden" name = "csrf" value = "<?php echo $this->csrf()?>">
                                
                                <div class='row'>
                                	<div class='form-group col-6'>
                                		<label class='bmd-label-floating' for='descricao'>descricao</label>
                                		<input type='text' class='form-control' id='descricao' name='descricao' maxlength='50' required='true' autofocus value="<?php echo $tpclasses['descricao']; ?>">
                                	</div>
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Salvar</button>
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
