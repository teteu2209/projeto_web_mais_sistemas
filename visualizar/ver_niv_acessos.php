<?php
if (!isset($seguranca)) {
    exit;
}

$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
if (!empty($id)) {
    if ($_SESSION['niveis_acesso_id'] == 1) {
        $result_niv_acesso = "SELECT * FROM niveis_acessos 
            WHERE id='$id'
            LIMIT 1";
    } else {
        $result_niv_acesso = "SELECT * FROM niveis_acessos 
            WHERE ordem > '" . $_SESSION['ordem'] . "' AND id='$id'
            LIMIT 1";
    }
    $resultado_niv_acesso = mysqli_query($conn, $result_niv_acesso);
    //Verificar se encontrou algum usuarios
    if (($resultado_niv_acesso) AND ( $resultado_niv_acesso->num_rows != 0)) {
        $row_niv_acesso = mysqli_fetch_assoc($resultado_niv_acesso);
        ?>
        <div class="well conteudo">
            <div class="pull-right">
                <?php
                /* Verificar o botão */
                $botao_editar = carregar_botao('editar/edit_niv_acessos', $conn);
                $botao_listar = carregar_botao('listar/list_niv_acessos', $conn);
                $botao_apagar = carregar_botao('processa/proc_apagar_niv_acessos', $conn);
                if ($botao_listar) {
                    echo "<a href='" . pg . "/listar/list_niv_acessos'><button type='button' class='btn btn-xs btn-roxo'>Listar</button></a> ";
                }
                if ($botao_editar) {
                    echo "<a href='" . pg . "/editar/edit_niv_acessos?id=" . $row_niv_acesso['id'] . "'><button type='button' class='btn btn-xs btn-warning'>Editar</button></a> ";
                }
                if ($botao_apagar) {
                    echo "<a href='" . pg . "/processa/proc_apagar_niv_acessos?id=" . $row_niv_acesso['id'] . "' onclick=\"return confirm('Deseja mesmo apagar?');\"><button type='button' class='btn btn-xs btn-danger'>Apagar</button></a> ";
                }
                ?>
            </div>

            <div class="page-header">
                <h1>Detalhes do Nivel de Acesso</h1>
            </div>
            <?php
            if (isset($_SESSION['msg'])) {
                echo $_SESSION['msg'];
                unset($_SESSION['msg']);
            }
            ?>
            <dl class="dl-horizontal">
                <dt>ID</dt>
                <dd><?php echo $row_niv_acesso['id']; ?></dd>

                <dt>Nome do Nível de Acesso</dt>
                <dd><?php echo $row_niv_acesso['nome_nivel_acesso']; ?></dd>

                <dt>Ordem</dt>
                <dd><?php echo $row_niv_acesso['ordem']; ?></dd>

                <dt>Inserido</dt>
                <dd><?php echo date('d/m/Y H:i:s', strtotime($row_niv_acesso['created'])); ?></dd>

                <dt>Alterado</dt>
                <dd><?php
                    if (!empty($row_niv_acesso['modified'])) {
                        echo date('d/m/Y H:i:s', strtotime($row_niv_acesso['modified']));
                    }
                    ?></dd>
            </dl>
        </div>
        <?php
    } else {
        $_SESSION['msg'] = "<div class='alert alert-danger'>Nível de Acesso não encontrado!</div>";
        $url_destino = pg . "/listar/list_niv_acessos";
        header("Location: $url_destino");
    }
} else {
    $_SESSION['msg'] = "<div class='alert alert-danger'>Nível de Acesso não encontrado!</div>";
    $url_destino = pg . "/listar/list_niv_acessos";
    header("Location: $url_destino");
}

