<?php
if (!isset($seguranca)) {
    exit;
}
$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
if (!empty($id)) {
    $result_comentario_prod = "SELECT comentprod.*,
            coment.nome_situacao, coment.cor_situacao, prod.nome
            FROM comentarios_produtos comentprod
            INNER JOIN situacaos coment ON coment.id=comentprod.situacao_id
            INNER JOIN produtos prod ON prod.id=comentprod.produto_id
            WHERE comentprod.id='$id'
            LIMIT 1";
    $resultado_comentario_prod = mysqli_query($conn, $result_comentario_prod);

    //Verificar se encontrou alguma categoria de produto no BD
    if (($resultado_comentario_prod) AND ($resultado_comentario_prod->num_rows != 0)) {
        $row_comentario_prod = mysqli_fetch_array($resultado_comentario_prod);
        //var_dump($row_cat_prod);
        ?>
        <div class="well conteudo">
            <div class="pull-right">
                <?php
                /* Verificar o botão */
                $botao_editar = carregar_botao('editar/edit_comentario_prod', $conn);
                $botao_listar = carregar_botao('listar/list_comenatario_prod', $conn);
                $botao_apagar = carregar_botao('processa/proc_apagar_comentario_prod', $conn);
                if ($botao_listar) {
                    echo "<a href='" . pg . "/listar/list_comentario_prod'><button type='button' class='btn btn-xs btn-roxo'>Listar</button></a> ";
                }
                if ($botao_editar) {
                    echo "<a href='" . pg . "/editar/edit_comentario_prod?id=" . $row_comentario_prod['id'] . "'><button type='button' class='btn btn-xs btn-warning'>Editar</button></a> ";
                }
                if ($botao_apagar) {
                    echo "<a href='" . pg . "/processa/proc_apagar_comentario_prod?id=" . $row_comentario_prod['id'] . "' onclick=\"return confirm('Deseja mesmo apagar?');\"><button type='button' class='btn btn-xs btn-danger'>Apagar</button></a> ";
                }
                ?>
            </div>
            <div class="page-header">
                <h1>Detalhes do Comentário do Produto</h1>
            </div>
            <?php
            if (isset($_SESSION['msg'])) {
                echo $_SESSION['msg'];
                unset($_SESSION['msg']);
            }
            ?>
            <dl class="dl-horizontal">
                <dt>ID</dt>
                <dd><?php echo $row_comentario_prod['id']; ?></dd>

                <dt>Comentário do Produto</dt>
                <dd><?php echo $row_comentario_prod['comentario']; ?></dd>

                <dt>Produto</dt>
                <dd><?php echo $row_comentario_prod['nome']; ?></dd>

                <dt>Situação</dt>
                <dd>
                    <span class="label label-<?php echo $row_comentario_prod['cor_situacao']; ?>"><?php echo $row_comentario_prod['nome_situacao']; ?></span>
                </dd>

                <dt>Inserido</dt>
                <dd><?php echo date('d/m/Y H:i:s', strtotime($row_comentario_prod['created'])); ?></dd>

                <dt>Alterado</dt>
                <dd><?php
                    if (!empty($row_comentario_prod['modified'])) {
                        echo date('d/m/Y H:i:s', strtotime($row_comentario_prod['modified']));
                    }
                    ?>
                </dd>
            </dl>
        </div>
        <?php
    }
} else {
    $_SESSION['msg'] = "<div class='alert alert-danger'>Comentário do produto não encontrado!</div>";
    $url_destino = pg . "/listar/list_cat_prod";
    header("Location: $url_destino");
}