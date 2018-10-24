<?php
if (!isset($seguranca)) {
    exit;
}
?>
    <div class="well conteudo">
        <div class="pull-right">
            <?php
            $botao_listar = carregar_botao('listar/list_prod', $conn);
            if ($botao_listar) {
                echo "<a href='" . pg . "/listar/list_prod'<button type='button' class='btn btn-xs btn-roxo'>Listar</button></a> ";
            }
            ?>
        </div>

        <div class="page-header">
            <h1>Cadastrar Produto</h1>
        </div>

        <?php
        if (isset($_SESSION['msg'])) {
            echo $_SESSION['msg'];
            unset($_SESSION['msg']);
        }
        ?>
        <form action="<?php echo pg; ?>/processa/proc_cad_prod" method="POST" class="form-horizontal"
              enctype="multipart/form-data">

            <div class="form-group">
                <label class="col-sm-2 control-label">Nome: </label>
                <div class="col-sm-10">
                    <input type="text" name="nome" class="form-control" placeholder="Nome do produto" value="<?php
                    if (isset($_SESSION['dados']['nome'])) {
                        echo $_SESSION['dados']['nome'];
                    }
                    ?>">
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">Descrição: </label>
                <div class="col-sm-10">
                    <input type="text" name="descricao" class="form-control" placeholder="Descrição do produto"
                           value="<?php
                           if (isset($_SESSION['dados']['descricao'])) {
                               echo $_SESSION['dados']['descricao'];
                           }
                           ?>">
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">Foto: </label>
                <div class="col-sm-10">
                    <input type="file" name="foto">
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">Código do Produto: </label>
                <div class="col-sm-10">
                    <input type="text" name="codigo_produto" class="form-control" placeholder="Código do produto"
                           minlength="6" maxlength="7" value="<?php
                    if (isset($_SESSION['dados']['codigo_produto'])) {
                        echo $_SESSION['dados']['codigo_produto'];
                    }
                    ?>">
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">Altura do Produto: </label>
                <div class="col-sm-10">
                    <input type="text" name="altura_produto" class="form-control" placeholder="Altura do produto"
                           value="<?php
                           if (isset($_SESSION['dados']['altura_produto'])) {
                               echo $_SESSION['dados']['altura_produto'];
                           }
                           ?>">
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">Largura do Produto: </label>
                <div class="col-sm-10">
                    <input type="text" name="largura_produto" class="form-control" placeholder="Largura do produto" "
                    value="<?php
                    if (isset($_SESSION['dados']['largura_produto'])) {
                        echo $_SESSION['dados']['largura_produto'];
                    }
                    ?>">
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">Profundidade do Produto: </label>
                <div class="col-sm-10">
                    <input type="text" name="profundidade_produto" class="form-control"
                           placeholder="Profundidade do produto" value="<?php
                    if (isset($_SESSION['dados']['profundidade_produto'])) {
                        echo $_SESSION['dados']['profundidade_produto'];
                    }
                    ?>">
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">Preço de Compra: </label>
                <div class="col-sm-10">
                    <input type="text" name="valor_compra" id="valor_compra" class="form-control"
                           placeholder="Preço de compra do produto" value="<?php
                    if (isset($_SESSION['dados']['valor_compra'])) {
                        echo $_SESSION['dados']['valor_compra'];
                    }
                    ?>">
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">Preço de Venda: </label>
                <div class="col-sm-10">
                    <input type="text" name="valor_venda" id="valor_venda" class="form-control"
                           placeholder="Preço de venda do produto" value="<?php
                    if (isset($_SESSION['dados']['valor_venda'])) {
                        echo $_SESSION['dados']['valor_venda'];
                    }
                    ?>">
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">Quantidade Disponível: </label>
                <div class="col-sm-10">
                    <input type="number" name="disponivel_estoque" class="form-control"
                           placeholder="Quantidade de produto disponível no estoque" value="<?php
                    if (isset($_SESSION['dados']['disponivel_estoque'])) {
                        echo $_SESSION['dados']['disponivel_estoque'];
                    }
                    ?>">
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">Quantidade Miníma: </label>
                <div class="col-sm-10">
                    <input type="number" name="min_estoque" class="form-control"
                           placeholder="Quantidade miníma disponível no estoque" value="<?php
                    if (isset($_SESSION['dados']['min_estoque'])) {
                        echo $_SESSION['dados']['min_estoque'];
                    }
                    ?>">
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">Quantidade Máxima: </label>
                <div class="col-sm-10">
                    <input type="number" name="max_estoque" class="form-control"
                           placeholder="Quantidade máxima disponível no estoque" value="<?php
                    if (isset($_SESSION['dados']['max_estoque'])) {
                        echo $_SESSION['dados']['max_estoque'];
                    }
                    ?>">
                </div>
            </div><?php
            $result_cat_prod = "SELECT * FROM categorias_produtos";
            $resultado_cat_prod = mysqli_query($conn, $result_cat_prod);
            ?>
            <div class="form-group">
                <label class="col-sm-2 control-label">Categoria do Produto: </label>
                <div class="col-sm-10">
                    <select class="form-control" name="categorias_produto_id">
                        <option value="">Selecione</option>
                        <?php
                        while ($row_cat_prod = mysqli_fetch_array($resultado_cat_prod)) {
                            if (isset($_SESSION['dados']['categorias_produto_id']) AND ($_SESSION['dados']['categorias_produto_id'] == $row_cat_prod['id'])) {
                                echo "<option value='" . $row_cat_prod['id'] . "' selected>" . $row_cat_prod['nome'] . "</option>";
                            } else {
                                echo "<option value='" . $row_cat_prod['id'] . "'>" . $row_cat_prod['nome'] . "</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>

            <?php
            $result_forn = "SELECT * FROM fornecedores";
            $resultado_forn = mysqli_query($conn, $result_forn);
            ?>
            <div class="form-group">
                <label class="col-sm-2 control-label">Fornecedor do Produto: </label>
                <div class="col-sm-10">
                    <select class="form-control" name="fornecedore_id">
                        <option value="">Selecione</option>
                        <?php
                        while ($row_forn = mysqli_fetch_array($resultado_forn)) {
                            if (isset($_SESSION['dados']['fornecedore_id']) AND ($_SESSION['dados']['fornecedore_id'] == $row_forn['id'])) {
                                echo "<option value='" . $row_forn['id'] . "' selected>" . $row_forn['nome'] . "</option>";
                            } else {
                                echo "<option value='" . $row_forn['id'] . "'>" . $row_forn['nome'] . "</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>

            <?php
            $result_sit = "SELECT * FROM situacaos";
            $resultado_sit = mysqli_query($conn, $result_sit);
            ?>
            <div class="form-group">
                <label class="col-sm-2 control-label">Situação da Categoria: </label>
                <div class="col-sm-10">
                    <select class="form-control" name="situacao_id">
                        <option value="">Selecione</option>
                        <?php
                        while ($row_sit = mysqli_fetch_array($resultado_sit)) {
                            if (isset($_SESSION['dados']['situacao_id']) AND ($_SESSION['dados']['situacao_id'] == $row_sit['id'])) {
                                echo "<option value='" . $row_sit['id'] . "' selected>" . $row_sit['nome_situacao'] . "</option>";
                            } else {
                                echo "<option value='" . $row_sit['id'] . "'>" . $row_sit['nome_situacao'] . "</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <input type="submit" class="btn btn-success" value="Cadastrar" name="SendCadProd">
                </div>
            </div>

        </form>
    </div>
<?php
unset($_SESSION['dados']);
