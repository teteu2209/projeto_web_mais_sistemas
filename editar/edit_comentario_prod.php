<?php
if (!isset($seguranca)) {
    exit;
}
$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
if (!empty($id)) {
    $result_comentario_prod = "SELECT * FROM comentarios_produtos WHERE id='$id' LIMIT 1";
    $resultado_comentario_prod = mysqli_query($conn, $result_comentario_prod);

    //Verificar se encontrou o comentario do produto no BD
    if (($resultado_comentario_prod) AND ($resultado_comentario_prod->num_rows != 0)) {
        $row_comentario_prod = mysqli_fetch_assoc($resultado_comentario_prod);
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
                <h1>Editar Comentário do Produto</h1>
            </div>

            <?php
            if (isset($_SESSION['msg'])) {
                echo $_SESSION['msg'];
                unset($_SESSION['msg']);
            }
            ?>
            <form action="<?php echo pg; ?>/processa/proc_edit_comentario_prod" method="POST"
                  class="form-horizontal" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo $row_comentario_prod['id']; ?>">

                <div class="form-group">
                    <label class="col-sm-2 control-label">Comentario: </label>
                    <div class="col-sm-10">
                        <input type="text" name="comentario" class="form-control" placeholder="Comentário do produto"
                               value="<?php
                               if (isset($_SESSION['dados']['comentario'])) {
                                   echo $_SESSION['dados']['comentario'];
                               } elseif (isset($row_comentario_prod['comentario'])) {
                                   echo $row_comentario_prod['comentario'];
                               }
                               ?>">
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
                                //Preencher com as informações que estão salva na sessão
                                if (isset($_SESSION['dados']['produto_id']) AND ($_SESSION['dados']['produto_id'] == $row_prod['id'])) {
                                    echo "<option value='" . $row_prod['id'] . "' selected>" . $row_prod['nome'] . "</option>";
                                } //Preencher com informações do banco de dados caso não tenha nenhum valor salvo na sessão $_SESSION['dados']
                                elseif (!isset($_SESSION['dados']['produto_id']) AND isset ($row_comentario_prod['produto_id']) AND ($row_comentario_prod['produto_id'] == $row_prod['id'])) {
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
                    <label class="col-sm-2 control-label">Situação: </label>
                    <div class="col-sm-10">
                        <select class="form-control" name="situacao_id">
                            <option value="">Selecione</option>
                            <?php
                            while ($row_sit = mysqli_fetch_array($resultado_sit)) {
                                //Preencher com as informações que estão salva na sessão
                                if (isset($_SESSION['dados']['situacao_id']) AND ($_SESSION['dados']['situacao_id'] == $row_sit['id'])) {
                                    echo "<option value='" . $row_sit['id'] . "' selected>" . $row_sit['nome_situacao'] . "</option>";
                                } //Preencher com informações do banco de dados caso não tenha nenhum valor salvo na sessão $_SESSION['dados']
                                elseif (!isset($_SESSION['dados']['situacao_id']) AND isset ($row_comentario_prod['situacao_id']) AND ($row_comentario_prod['situacao_id'] == $row_sit['id'])) {
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
                        <input type="submit" class="btn btn-success" value="Editar" name="SendEditComentarioProd">
                    </div>
                </div>

            </form>
        </div>
        <?php
        unset($_SESSION['dados']);
    }
} else {
    $_SESSION['msg'] = "<div class='alert alert-danger'>Nenhum comentario do produto encontrado</div>";
    $url_destino = pg . "/listar/list_comentario_prod";
    header("Location: $url_destino");
}