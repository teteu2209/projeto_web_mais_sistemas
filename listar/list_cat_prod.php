<?php
if (!isset($seguranca)) {
    exit;
}
?>
<div class="well conteudo">
    <?php
    $botao_cad = carregar_botao('cadastrar/cad_cat_prod', $conn);
    if ($botao_cad) {
        ?>
        <div class="pull-right">
            <a href="<?php echo pg . '/cadastrar/cad_cat_prod' ?>"><button type="button" class="btn btn-xs btn-success">Cadastrar</button></a>
        </div><?php
    }
    ?>    
    <div class="page-header">
        <h1>Listar Categoria de Produto</h1>
    </div>
    <?php
    if (isset($_SESSION['msg'])) {
        echo $_SESSION['msg'];
        unset($_SESSION['msg']);
    }
    /* Verificar o botão */
    $botao_editar = carregar_botao('editar/edit_cat_prod', $conn);
    $botao_ver = carregar_botao('visualizar/ver_cat_prod', $conn);
    $botao_apagar = carregar_botao('processa/proc_apagar_cat_prod', $conn);

    /* Selecionar no banco de dados os categoria de produto */
    $pagina_atual = filter_input(INPUT_GET, 'pagina', FILTER_SANITIZE_NUMBER_INT);
    $pagina = (!empty($pagina_atual)) ? $pagina_atual : 1;

    //Setar a quantidade de itens por pagina
    $qnt_result_pg = 50;

    //calcular o inicio visualização
    $inicio = ($qnt_result_pg * $pagina) - $qnt_result_pg;


    $result_cat_prod = "SELECT catprod.*,
            sit.nome_situacao, sit.cor_situacao
            FROM categorias_produtos catprod
            INNER JOIN situacaos sit ON sit.id=catprod.situacao_id
            ORDER BY catprod.id ASC
            LIMIT $inicio, $qnt_result_pg";

    $resultado_cat_prod = mysqli_query($conn, $result_cat_prod);
    ?>

    <div class="row">
        <div class="col-md-12">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Situação</th>
                        <th class="text-right">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($row_cat_prod = mysqli_fetch_array($resultado_cat_prod)) {
                        //echo $row_usuario['nome'] . "<br>";
                        ?>
                        <tr>
                            <td><?php echo $row_cat_prod['id']; ?></td>
                            <td><?php echo $row_cat_prod['nome']; ?></td>
                            <td>
                                <span class="label label-<?php echo $row_cat_prod['cor_situacao']; ?>">
                                    <?php echo $row_cat_prod['nome_situacao']; ?>
                                </span>
                            </td>
                            <td class="text-right">
                                <?php
                                if ($botao_ver) {
                                    echo "<a href='" . pg . "/visualizar/ver_cat_prod?id=" . $row_cat_prod['id'] . "'><button type='button' class='btn btn-xs btn-primary'>Visualizar</button></a> ";
                                }
                                if ($botao_editar) {
                                    echo "<a href='" . pg . "/editar/edit_cat_prod?id=" . $row_cat_prod['id'] . "'><button type='button' class='btn btn-xs btn-warning'>Editar</button></a> ";
                                }
                                if ($botao_apagar) {
                                    echo "<a href='" . pg . "/processa/proc_apagar_cat_prod?id=" . $row_cat_prod['id'] . "' onclick=\"return confirm('Deseja mesmo apagar?');\"><button type='button' class='btn btn-xs btn-danger'>Apagar</button></a> ";
                                }
                                ?>                                
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
            <?php
            //Paginção - Somar a quantidade de usuários
            $result_pg = "SELECT COUNT(id) AS num_result FROM categorias_produtos";

            $resultado_pg = mysqli_query($conn, $result_pg);
            $row_pg = mysqli_fetch_assoc($resultado_pg);
            //echo $row_pg['num_result'];
            //Quantidade de pagina 
            $quantidade_pg = ceil($row_pg['num_result'] / $qnt_result_pg);

            //Limitar os link antes depois
            $MaxLinks = 2;
            echo "<nav class='text-center'>";
            echo "<ul class='pagination'>";
            echo "<li><a href='" . pg . "/listar/list_cat_prod?pagina=1'  aria-label='Previous'><span aria-hidden='true'>Primeira</span> </a></li>";

            for ($iPag = $pagina - $MaxLinks; $iPag <= $pagina - 1; $iPag++) {
                if ($iPag >= 1) {
                    echo "<li><a href='" . pg . "/listar/list_cat_prod?pagina=$iPag'>$iPag </a></li>";
                }
            }

            echo "<li class='active'><a href='#'> $pagina <span class='sr-only'></span></a></li>";

            for ($dPag = $pagina + 1; $dPag <= $pagina + $MaxLinks; $dPag++) {
                if ($dPag <= $quantidade_pg) {
                    echo "<li><a href='" . pg . "/listar/list_cat_prod?pagina=$dPag'>$dPag </a></li>";
                }
            }

            echo "<li><a href='" . pg . "/listar/list_cat_prod?pagina=$quantidade_pg'  aria-label='Previous'><span aria-hidden='true'>Última</span> </a></li>";
            ?>
        </div>
    </div>

</div>