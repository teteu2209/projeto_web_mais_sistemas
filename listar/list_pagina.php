<?php
if (!isset($seguranca)) {
    exit;
}
?>
<div class="well conteudo">
    <div class="pull-right">
        <?php
        $botao_cad = carregar_botao('cadastrar/cad_pagina', $conn);
        if ($botao_cad) {
            ?>
            <a href="<?php echo pg . '/cadastrar/cad_pagina' ?>"><button type="button" class="btn btn-xs btn-success">Cadastrar</button></a>
            <?php
        }
        $botao_sin_pg = carregar_botao('processa/proc_sincronizar_pagina', $conn);
        if ($botao_sin_pg) {
            ?>
            <a href="<?php echo pg . '/processa/proc_sincronizar_pagina' ?>"><button type="button" class="btn btn-xs btn-info">Sincronizar</button></a>
            <?php
        }
        ?>
    </div>
    <div class="page-header">
        <h1>Listar Páginas</h1>
    </div>
    <?php
    if (isset($_SESSION['msg'])) {
        echo $_SESSION['msg'];
        unset($_SESSION['msg']);
    }

    /* Verificar o botão */
    $botao_editar = carregar_botao('editar/edit_pagina', $conn);
    $botao_ver = carregar_botao('visualizar/ver_pagina', $conn);

    /* Selecionar no banco de dados os usuário */
    $pagina_atual = filter_input(INPUT_GET, 'pagina', FILTER_SANITIZE_NUMBER_INT);
    $pagina = (!empty($pagina_atual)) ? $pagina_atual : 1;

    //Setar a quantidade de itens por pagina
    $qnt_result_pg = 20;

    //calcular o inicio visualização
    $inicio = ($qnt_result_pg * $pagina) - $qnt_result_pg;

    if ($_SESSION['niveis_acesso_id'] == 1) {
        $result_paginas = "SELECT * FROM paginas ORDER BY id ASC
            LIMIT $inicio, $qnt_result_pg";
    } else {
        $result_paginas = "SELECT * FROM paginas ORDER BY id ASC
            LIMIT $inicio, $qnt_result_pg";
    }

    $resultado_paginas = mysqli_query($conn, $result_paginas);
    ?>
    <div class="row">
        <div class="col-md-12">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Endereço</th>
                        <th>Nome da Página</th>
                        <th class="text-right">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($row_paginas = mysqli_fetch_array($resultado_paginas)) {
                        //echo $row_usuario['nome'] . "<br>";
                        ?>
                        <tr>
                            <td><?php echo $row_paginas['id']; ?></td>
                            <td><?php echo $row_paginas['endereco']; ?></td>
                            <td><?php echo $row_paginas['nome_pagina']; ?></td>
                            <td class="text-right">
                                <?php
                                if ($botao_ver) {
                                    echo "<a href='" . pg . "/visualizar/ver_pagina?id=" . $row_paginas['id'] . "'><button type='button' class='btn btn-xs btn-primary'>Visualizar</button></a> ";
                                }
                                if ($botao_editar) {
                                    echo "<a href='" . pg . "/editar/edit_pagina?id=" . $row_paginas['id'] . "'><button type='button' class='btn btn-xs btn-warning'>Editar</button></a> ";
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
                $result_pg = "SELECT COUNT(id) AS num_result FROM paginas";
            } else {
                $result_pg = "SELECT COUNT(id) AS num_result FROM paginas";
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
            echo "<li><a href='" . pg . "/listar/list_pagina?pagina=1'  aria-label='Previous'><span aria-hidden='true'>Primeira</span> </a></li>";

            for ($iPag = $pagina - $MaxLinks; $iPag <= $pagina - 1; $iPag++) {
                if ($iPag >= 1) {
                    echo "<li><a href='" . pg . "/listar/list_pagina?pagina=$iPag'>$iPag </a></li>";
                }
            }

            echo "<li class='active'><a href='#'> $pagina <span class='sr-only'></span></a></li>";

            for ($dPag = $pagina + 1; $dPag <= $pagina + $MaxLinks; $dPag++) {
                if ($dPag <= $quantidade_pg) {
                    echo "<li><a href='" . pg . "/listar/list_pagina?pagina=$dPag'>$dPag </a></li>";
                }
            }

            echo "<li><a href='" . pg . "/listar/list_pagina?pagina=$quantidade_pg'  aria-label='Previous'><span aria-hidden='true'>Última</span> </a></li>";
            ?>
        </div>
    </div>

</div>