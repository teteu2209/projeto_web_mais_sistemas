<?php
if (!isset($seguranca)) {
    exit;
}
?>
<div class="well conteudo">
    <?php
    $botao_cad = carregar_botao('cadastrar/cad_niv_acessos', $conn);
    if ($botao_cad) {
        ?>
        <div class="pull-right">
            <a href="<?php echo pg . '/cadastrar/cad_niv_acessos' ?>"><button type="button" class="btn btn-xs btn-success">Cadastrar</button></a>
        </div><?php
    }
    ?>
    <div class="page-header">
        <h1>Listar Nível de Acesso</h1>
    </div>
    <?php
    if (isset($_SESSION['msg'])) {
        echo $_SESSION['msg'];
        unset($_SESSION['msg']);
    }

    /* Verificar o botão */
    $botao_permissao = carregar_botao('listar/list_permissao', $conn);
    $botao_editar = carregar_botao('editar/edit_niv_acessos', $conn);
    $botao_ver = carregar_botao('visualizar/ver_niv_acessos', $conn);
    $botao_apagar = carregar_botao('processa/proc_apagar_niv_acessos', $conn);

    /* Selecionar no banco de dados os usuário */
    $pagina_atual = filter_input(INPUT_GET, 'pagina', FILTER_SANITIZE_NUMBER_INT);
    $pagina = (!empty($pagina_atual)) ? $pagina_atual : 1;

    //Setar a quantidade de itens por pagina
    $qnt_result_pg = 20;

    //calcular o inicio visualização
    $inicio = ($qnt_result_pg * $pagina) - $qnt_result_pg;

    if ($_SESSION['niveis_acesso_id'] == 1) {
        $result_niv_acesso = "SELECT * FROM niveis_acessos ORDER BY ordem ASC
            LIMIT $inicio, $qnt_result_pg";
    } else {
        $result_niv_acesso = "SELECT *
            FROM niveis_acessos
            WHERE ordem > '" . $_SESSION['ordem'] . "'
            ORDER BY ordem ASC
            LIMIT $inicio, $qnt_result_pg";
    }

    $resultado_niv_acesso = mysqli_query($conn, $result_niv_acesso);
    ?>
    <div class="row">
        <div class="col-md-12">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Ordem</th>
                        <th class="text-right">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $qnt_linhas_exe = 1;
                    while ($row_niv_acesso = mysqli_fetch_array($resultado_niv_acesso)) {
                        //echo $row_usuario['nome'] . "<br>";
                        ?>
                        <tr>
                            <td><?php echo $row_niv_acesso['id']; ?></td>
                            <td><?php echo $row_niv_acesso['nome_nivel_acesso']; ?></td>
                            <td><?php echo $row_niv_acesso['ordem']; ?></td>
                            <td class="text-right">
                                <?php
                                if ($qnt_linhas_exe == 1) {
                                    echo "<button type='button' class='btn btn-xs btn-info'>";
                                    echo "<span class='glyphicon glyphicon-arrow-up'></span>";
                                    echo "</button> ";
                                } else {
                                    echo "<a href='" . pg . "/processa/proc_ordem_niv_acessos?ordem=" . $row_niv_acesso['ordem'] . "'><button type='button' class='btn btn-xs btn-info'>";
                                    echo "<span class='glyphicon glyphicon-arrow-up'></span>";
                                    echo "</button></a> ";
                                }
                                $qnt_linhas_exe++;

                                if ($botao_permissao) {
                                    echo "<a href='" . pg . "/listar/list_permissao?id=" . $row_niv_acesso['id'] . "'><button type='button' class='btn btn-xs btn-roxo'>Permissão</button></a> ";
                                }
                                if ($botao_ver) {
                                    echo "<a href='" . pg . "/visualizar/ver_niv_acessos?id=" . $row_niv_acesso['id'] . "'><button type='button' class='btn btn-xs btn-primary'>Visualizar</button></a> ";
                                }
                                if ($botao_editar) {
                                    echo "<a href='" . pg . "/editar/edit_niv_acessos?id=" . $row_niv_acesso['id'] . "'><button type='button' class='btn btn-xs btn-warning'>Editar</button></a> ";
                                }
                                if ($botao_apagar) {
                                    echo "<a href='" . pg . "/processa/proc_apagar_niv_acessos?id=" . $row_niv_acesso['id'] . "' onclick=\"return confirm('Deseja mesmo apagar?');\"><button type='button' class='btn btn-xs btn-danger'>Apagar</button></a> ";
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
            if ($_SESSION['niveis_acesso_id'] == 1) {
                $result_pg = "SELECT COUNT(id) AS num_result FROM niveis_acessos";
            } else {
                $result_pg = "SELECT COUNT(id) AS num_result
                    FROM niveis_acessos
                    WHERE ordem > '" . $_SESSION['ordem'] . "'";
            }

            $resultado_pg = mysqli_query($conn, $result_pg);
            $row_pg = mysqli_fetch_assoc($resultado_pg);
            //echo $row_pg['num_result'];
            //Quantidade de pagina 
            $quantidade_pg = ceil($row_pg['num_result'] / $qnt_result_pg);

            //Limitar os link antes depois
            $MaxLinks = 2;
            echo "<nav class='text-center'>";
            echo "<ul class='pagination'>";
            echo "<li><a href='" . pg . "/listar/list_niv_acessos?pagina=1'  aria-label='Previous'><span aria-hidden='true'>Primeira</span> </a></li>";

            for ($iPag = $pagina - $MaxLinks; $iPag <= $pagina - 1; $iPag++) {
                if ($iPag >= 1) {
                    echo "<li><a href='" . pg . "/listar/list_niv_acessos?pagina=$iPag'>$iPag </a></li>";
                }
            }

            echo "<li class='active'><a href='#'> $pagina <span class='sr-only'></span></a></li>";

            for ($dPag = $pagina + 1; $dPag <= $pagina + $MaxLinks; $dPag++) {
                if ($dPag <= $quantidade_pg) {
                    echo "<li><a href='" . pg . "/listar/list_niv_acessos?pagina=$dPag'>$dPag </a></li>";
                }
            }

            echo "<li><a href='" . pg . "/listar/list_niv_acessos?pagina=$quantidade_pg'  aria-label='Previous'><span aria-hidden='true'>Última</span> </a></li>";
            ?>
        </div>
    </div>

</div>