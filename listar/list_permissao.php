<?php
if (!isset($seguranca)) {
    exit;
}
$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
if (!empty($id)) {
    if ($_SESSION['niveis_acesso_id'] == 1) {
        $result_niv_acesso = "SELECT nivpg.*,
                pg.nome_pagina, pg.obs,
                nivac.nome_nivel_acesso
                FROM niveis_acessos_paginas nivpg
                INNER JOIN paginas pg on pg.id=nivpg.pagina_id
                INNER JOIN niveis_acessos nivac on nivac.id=nivpg.niveis_acesso_id
                WHERE nivpg.niveis_acesso_id='$id'
                ORDER BY nivpg.ordem ASC";
    } else {
        $result_niv_acesso = "SELECT nivpg.*,
                pg.nome_pagina, pg.obs,
                nivac.nome_nivel_acesso
                FROM niveis_acessos_paginas nivpg
                INNER JOIN paginas pg on pg.id=nivpg.pagina_id
                INNER JOIN niveis_acessos nivac on nivac.id=nivpg.niveis_acesso_id
                WHERE nivpg.niveis_acesso_id='$id' AND nivac.ordem > '" . $_SESSION['ordem'] . "'
                ORDER BY nivpg.ordem ASC";
    }

    $resultado_niv_acesso = mysqli_query($conn, $result_niv_acesso);

    //verificar se encontrou alguma página cadastra
    if (($resultado_niv_acesso) AND ( $resultado_niv_acesso->num_rows != 0)) {
        ?>
        <div class="well conteudo">
            <div class="pull-right">
                <a href="<?php echo pg . '/listar/list_niv_acessos' ?>"><button type="button" class="btn btn-xs btn-roxo">Listar Nível de Acesso</button></a>
            </div>
            <?php
            $result_nivel_acesso = "SELECT nivpg.*,
                nivac.nome_nivel_acesso
                FROM niveis_acessos_paginas nivpg
                INNER JOIN niveis_acessos nivac on nivac.id=nivpg.niveis_acesso_id
                WHERE nivpg.niveis_acesso_id='$id'
                LIMIT 1";
            $resultado_nivel_acesso = mysqli_query($conn, $result_nivel_acesso);
            $row_nivel_acesso = mysqli_fetch_assoc($resultado_nivel_acesso);
            ?>
            <div class="page-header">
                <h1>Listar Permissões - <?php echo $row_nivel_acesso['nome_nivel_acesso']; ?></h1>
            </div>
            <?php
            if (isset($_SESSION['msg'])) {
                echo $_SESSION['msg'];
                unset($_SESSION['msg']);
            }
            ?>
            <div class="row">
                <div class="col-md-12">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Página</th>
                                <th class="hidden-xs">Permissão</th>
                                <th class="hidden-xs">Menu</th>
                                <th class="hidden-xs">Ordem</th>
                                <th class="text-right">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $qnt_linhas_exe = 1;
                            while ($row_niv_acesso = mysqli_fetch_array($resultado_niv_acesso)) {
                                $result_pg = "SELECT COUNT(id) AS num_result FROM niveis_acessos_paginas 
                                       WHERE pagina_id='" . $row_niv_acesso['pagina_id'] . "' AND niveis_acesso_id='" . $_SESSION['niveis_acesso_id'] . "' AND permissao='1' LIMIT 1";
                                $resultado_pg = mysqli_query($conn, $result_pg);
                                $row_pg = mysqli_fetch_assoc($resultado_pg);
                                if ($row_pg['num_result'] != 0) {
                                    ?>
                                    <tr>
                                        <td><?php echo $row_niv_acesso['id']; ?></td>
                                        <td>
                                                <span class="glyphicon glyphicon-question-sign" aria-hidden="true"  aria-label="Left Align" data-toggle="tooltip" data-placement="top" title="<?php echo $row_niv_acesso['obs']; ?>"></span>
                                            <?php echo $row_niv_acesso['nome_pagina']; ?>
                                        </td>
                                        <td class="hidden-xs"><?php
                                            if ($row_niv_acesso['permissao'] == 1) {
                                                echo "<a href='" . pg . "/processa/proc_liberar_permissao?id=" . $row_niv_acesso['id'] . "'><button type='button' class='btn btn-xs btn-success'>Liberado</button></a>";
                                            } else {
                                                echo "<a href='" . pg . "/processa/proc_liberar_permissao?id=" . $row_niv_acesso['id'] . "'><button type='button' class='btn btn-xs btn-danger'>Bloqueado</button></a>";
                                            }
                                            ?>
                                        </td>
                                        <td class="hidden-xs"><?php
                                            if ($row_niv_acesso['menu'] == 1) {
                                                echo "<a href='" . pg . "/processa/proc_liberar_menu?id=" . $row_niv_acesso['id'] . "'><button type='button' class='btn btn-xs btn-success'>Liberado</button></a>";
                                            } else {
                                                echo "<a href='" . pg . "/processa/proc_liberar_menu?id=" . $row_niv_acesso['id'] . "'><button type='button' class='btn btn-xs btn-danger'>Bloqueado</button></a>";
                                            }
                                            ?>
                                        </td>
                                        <td class="hidden-xs"><?php echo $row_niv_acesso['ordem']; ?></td>
                                        <td class="text-right">
                                            <?php
                                            if ($qnt_linhas_exe == 1) {
                                                echo "<button type='button' class='btn btn-xs btn-info'>";
                                                echo "<span class='glyphicon glyphicon-arrow-up'></span>";
                                                echo "</button> ";
                                            } else {
                                                echo "<a href='" . pg . "/processa/proc_ordem_menu?id=" . $row_niv_acesso['id'] . "'><button type='button' class='btn btn-xs btn-info'>";
                                                echo "<span class='glyphicon glyphicon-arrow-up'></span>";
                                                echo "</button></a> ";
                                            }
                                            $qnt_linhas_exe++;
                                            ?>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            }
                            ?>
                        </tbody>
                    </table>         
                </div>
            </div>
            <?php
        } else {
            $_SESSION['msg'] = "<div class='alert alert-danger'>Permissão não encontrada!</div>";
            $url_destino = pg . "/listar/list_niv_acessos";
            header("Location: $url_destino");
        }
    } else {
        $_SESSION['msg'] = "<div class='alert alert-danger'>Permissão não encontrada!</div>";
        $url_destino = pg . "/listar/list_niv_acessos";
        header("Location: $url_destino");
    }
    ?>