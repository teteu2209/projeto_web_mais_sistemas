<?php
if (!isset($seguranca)) {
    exit;
}
?>
    <div class="well conteudo">
        <div class="pull-right">
            <?php
            $botao_listar = carregar_botao('listar/list_comentario_prod', $conn);
            if ($botao_listar) {
                echo "<a href='" . pg . "/listar/list_comentario_prod'<button type='button' class='btn btn-xs btn-roxo'>Listar</button></a> ";
            }
            ?>
        </div>
        <div class="page-header">
            <h1>Cadastrar Comentário do Produto</h1>
        </div>

        <?php
        if (isset($_SESSION['msg'])) {
            echo $_SESSION['msg'];
            unset($_SESSION['msg']);
        }
        ?>
        <form action="<?php echo pg; ?>/processa/proc_cad_comentario_prod" method="POST" class="form-horizontal"
              enctype="multipart/form-data">

            <div class="form-group">
                <label class="col-sm-2 control-label">Comentário: </label>
                <div class="col-sm-10">
                    <input type="text" name="comentario" class="form-control" placeholder="Comentário do produto"
                           value="<?php if (isset($_SESSION['dados']['comentario'])) {
                               echo $_SESSION['dados']['comentario'];
                           } ?>">
                </div>
            </div>

            <?php
            $result_prod = "SELECT * FROM produtos";
            $resultado_prod = mysqli_query($conn, $result_prod);

            ?>

            <div class="form-group">
                <label class="col-sm-2 control-label">Produto: </label>
                <div class="col-sm-10">
                    <select class="form-control" name="produto_id">
                        <option value="">Selecione</option>
                        <?php
                        while ($row_prod = mysqli_fetch_array($resultado_prod)) {
                            if (isset($_SESSION['dados']['produto_id']) AND ($_SESSION['dados']['produto_id'] == $row_prod['id'])) {
                                echo "<option value='" . $row_prod['id'] . "' selected>" . $row_prod['nome'] . "</option>";
                            } else {
                                echo "<option value='" . $row_prod['id'] . "'>" . $row_prod['nome'] . "</option>";
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
                <label class="col-sm-2 control-label">Situação do Comentário: </label>
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
                    <input type="submit" class="btn btn-success" value="Cadastrar" name="SendCadComentarioProd">
                </div>
            </div>

        </form>
    </div>
<?php
unset($_SESSION['dados']);