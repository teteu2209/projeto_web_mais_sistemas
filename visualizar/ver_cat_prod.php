<?php
if (!isset($seguranca)) {
    exit;
}
$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
if (!empty($id)) {
    $result_cat_prod = "SELECT catprod.*,
            sit.nome_situacao, sit.cor_situacao
            FROM categorias_produtos catprod
            INNER JOIN situacaos sit ON sit.id=catprod.situacao_id
            WHERE catprod.id='$id'
            LIMIT 1";
    $resultado_cat_prod = mysqli_query($conn, $result_cat_prod);

    //Verificar se encontrou alguma categoria de produto no BD
    if (($resultado_cat_prod) AND ( $resultado_cat_prod->num_rows != 0)) {
        $row_cat_prod = mysqli_fetch_array($resultado_cat_prod);
        //var_dump($row_cat_prod);
        ?>
        <div class="well conteudo">
            <div class="pull-right">
                <?php
                /* Verificar o botão */
                $botao_editar = carregar_botao('editar/edit_cat_prod', $conn);
                $botao_listar = carregar_botao('listar/list_cat_prod', $conn);
                $botao_apagar = carregar_botao('processa/proc_apagar_cat_prod', $conn);
                if ($botao_listar) {
                    echo "<a href='" . pg . "/listar/list_cat_prod'><button type='button' class='btn btn-xs btn-roxo'>Listar</button></a> ";
                }
                if ($botao_editar) {
                    echo "<a href='" . pg . "/editar/edit_cat_prod?id=" . $row_cat_prod['id'] . "'><button type='button' class='btn btn-xs btn-warning'>Editar</button></a> ";
                }
                if ($botao_apagar) {
                    echo "<a href='" . pg . "/processa/proc_apagar_cat_prod?id=" . $row_cat_prod['id'] . "' onclick=\"return confirm('Deseja mesmo apagar?');\"><button type='button' class='btn btn-xs btn-danger'>Apagar</button></a> ";
                }
                ?>
            </div>
            <div class="page-header">
                <h1>Detalhes da Categoria de Produto</h1>
            </div>
            <?php
            if (isset($_SESSION['msg'])) {
                echo $_SESSION['msg'];
                unset($_SESSION['msg']);
            }
            ?>
            <dl class="dl-horizontal">
                <dt>ID</dt>
                <dd><?php echo $row_cat_prod['id']; ?></dd>

                <dt>Categoria de Produto</dt>
                <dd><?php echo $row_cat_prod['nome']; ?></dd>

                <dt>Situação</dt>
                <dd>
                    <span class="label label-<?php echo $row_cat_prod['cor_situacao']; ?>"><?php echo $row_cat_prod['nome_situacao']; ?></span>
                </dd>

                <dt>Inserido</dt>
                <dd><?php echo date('d/m/Y H:i:s', strtotime($row_cat_prod['created'])); ?></dd>

                <dt>Alterado</dt>
                <dd><?php
                    if (!empty($row_cat_prod['modified'])) {
                        echo date('d/m/Y H:i:s', strtotime($row_cat_prod['modified']));
                    }
                    ?>
                </dd>
            </dl>
        </div>
        <?php
    }
} else {
    $_SESSION['msg'] = "<div class='alert alert-danger'>Categoria de produto não encontrado!</div>";
    $url_destino = pg . "/listar/list_cat_prod";
    header("Location: $url_destino");
}