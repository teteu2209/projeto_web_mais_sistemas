<?php
if (!isset($seguranca)) {
    exit;
}
$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
if (!empty($id)) {
    $result_prod = "SELECT prod.*,
            catprod.nome cat_prod_nome,
            forn.nome forn_nome,
            sit.nome_situacao, sit.cor_situacao
            FROM produtos prod 
            INNER JOIN categorias_produtos catprod ON catprod.id=prod.categorias_produto_id
            INNER JOIN fornecedores forn ON forn.id=prod.fornecedore_id
            INNER JOIN situacaos sit ON sit.id=prod.situacao_id
            WHERE prod.id='$id'
            LIMIT 1";
    $resultado_prod = mysqli_query($conn, $result_prod);

    //Verificar se encontrou alguma categoria de produto no BD
    if (($resultado_prod) AND ($resultado_prod->num_rows != 0)) {
        $row_prod = mysqli_fetch_array($resultado_prod);
        //var_dump($row_cat_prod);
        ?>
        <div class="well conteudo">
            <div class="pull-right">
                <?php
                /* Verificar o botão */
                $botao_editar = carregar_botao('editar/edit_prod', $conn);
                $botao_listar = carregar_botao('listar/list_prod', $conn);
                $botao_apagar = carregar_botao('processa/proc_apagar_prod', $conn);
                if ($botao_listar) {
                    echo "<a href='" . pg . "/listar/list_prod'><button type='button' class='btn btn-xs btn-roxo'>Listar</button></a> ";
                }
                if ($botao_editar) {
                    echo "<a href='" . pg . "/editar/edit_prod?id=" . $row_prod['id'] . "'><button type='button' class='btn btn-xs btn-warning'>Editar</button></a> ";
                }
                if ($botao_apagar) {
                    echo "<a href='" . pg . "/processa/proc_apagar_prod?id=" . $row_prod['id'] . "' onclick=\"return confirm('Deseja mesmo apagar?');\"><button type='button' class='btn btn-xs btn-danger'>Apagar</button></a> ";
                }
                ?>
            </div>
            <div class="page-header">
                <h1>Detalhes do Produto</h1>
            </div>
            <?php
            if (isset($_SESSION['msg'])) {
                echo $_SESSION['msg'];
                unset($_SESSION['msg']);
            }
            ?>
            <dl class="dl-horizontal">
                <dt>Imagem do Produto</dt>
                <dd>
                    <?php
                    if (!empty($row_prod['foto'])) {
                        echo "<img src='" . pg . "/assets/imagens/produto/" . $row_prod['id'] . "/" . $row_prod['foto'] . "' width='200' height='200'>";
                    }
                    ?>
                </dd>

                <dt>ID</dt>
                <dd><?php echo $row_prod['id']; ?></dd>

                <dt>Nome do Produto</dt>
                <dd><?php echo $row_prod['nome']; ?></dd>

                <dt>Código do Produto</dt>
                <dd><?php echo $row_prod['codigo_produto']; ?></dd>

                <dt>Altura do Produto</dt>
                <dd><?php echo $row_prod['altura_produto']; ?></dd>

                <dt>Largura do Produto</dt>
                <dd><?php echo $row_prod['largura_produto']; ?></dd>

                <dt>Profundidade do Produto</dt>
                <dd><?php echo $row_prod['profundidade_produto']; ?></dd>

                <dt>Preço de Compra</dt>
                <dd>R$ <?php echo number_format($row_prod['valor_compra'], 2, ",", "."); ?></dd>

                <dt>Preço de Venda</dt>
                <dd>R$ <?php echo number_format($row_prod['valor_venda'], 2, ",", "."); ?></dd>

                <dt>Disponível</dt>
                <dd><?php echo $row_prod['disponivel_estoque']; ?></dd>

                <dt>Mínimo estoque</dt>
                <dd><?php echo $row_prod['min_estoque']; ?></dd>

                <dt>Máximo estoque</dt>
                <dd><?php echo $row_prod['max_estoque']; ?></dd>


                <?php
                $result_comentario = "SELECT * FROM comentarios_produtos WHERE produto_id='$id'";
                $resultado_comentario = mysqli_query($conn, $result_comentario);
                ?>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Comentário(s)</label>
                    <div class="col-sm-10">
                        <?php
                        while ($row_comentario = mysqli_fetch_array($resultado_comentario)) {
                            //Preencher com as informações que estão salva na sessão
                            if (isset($_SESSION['dados']['produto_id']) AND ($_SESSION['dados']['produto_id'] == $row_comentario['id'])) {
                                echo "<option value='" . $row_comentario['id'] . "' selected>" . $row_comentario['comentario'] . "</option>";
                            } //Preencher com informações do banco de dados caso não tenha nenhum valor salvo na sessão $_SESSION['dados']
                            elseif (!isset($_SESSION['dados']['produto_id']) AND isset ($row_comentario_prod['produto_id']) AND ($row_comentario_prod['produto_id'] == $row_comentario['id'])) {
                                echo "<option value='" . $row_comentario['id'] . "' selected>" . $row_comentario['comentario'] . "</option>";
                            } else {
                                echo "<option value='" . $row_comentario['id'] . "'>" . $row_comentario['comentario'] . "</option>";
                            }
                        }
                        ?>
                    </div>
                </div>

                <dt>Categoria do Produto</dt>
                <dd>
                    <a href="<?php echo pg . "/visualizar/ver_cat_prod?id=" . $row_prod['categorias_produto_id']; ?>"><?php echo $row_prod['cat_prod_nome']; ?></a>
                </dd>

                <dt>Fornecedor do Produto</dt>
                <dd><?php echo $row_prod['forn_nome']; ?></dd>

                <dt>Situação</dt>
                <dd>
                    <span class="label label-<?php echo $row_prod['cor_situacao']; ?>"><?php echo $row_prod['nome_situacao']; ?></span>
                </dd>

                <dt>Inserido</dt>
                <dd><?php echo date('d/m/Y H:i:s', strtotime($row_prod['created'])); ?></dd>

                <dt>Alterado</dt>
                <dd><?php
                    if (!empty($row_prod['modified'])) {
                        echo date('d/m/Y H:i:s', strtotime($row_prod['modified']));
                    }
                    ?>
                </dd>

                <dt>Descrição Curta</dt>
                <dd><?php echo $row_prod['descricao']; ?></dd>

            </dl>
        </div>
        <?php
    }
} else {
    $_SESSION['msg'] = "<div class='alert alert-danger'>Produto não encontrado!</div>";
    $url_destino = pg . "/listar/list_prod";
    header("Location: $url_destino");
}