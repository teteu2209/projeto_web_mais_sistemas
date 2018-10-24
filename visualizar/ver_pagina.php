<?php
if (!isset($seguranca)) {
    exit;
}

$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
if (!empty($id)) {
    if ($_SESSION['niveis_acesso_id'] == 1) {
        $result_paginas = "SELECT * FROM paginas 
            WHERE id='$id'
            LIMIT 1";
    } else {
        $result_paginas = "SELECT * FROM paginas 
            WHERE id='$id'
            LIMIT 1";
    }
    $resultado_paginas = mysqli_query($conn, $result_paginas);
    //Verificar se encontrou algum usuarios
    if (($resultado_paginas) AND ( $resultado_paginas->num_rows != 0)) {
        $row_paginas = mysqli_fetch_assoc($resultado_paginas);
        ?>
        <div class="well conteudo">
            <div class="pull-right">
                <?php
                /* Verificar o botão */
                $botao_editar = carregar_botao('editar/edit_pagina', $conn);
                $botao_listar = carregar_botao('listar/list_pagina', $conn);
                
                if ($botao_listar) {
                    echo "<a href='" . pg . "/listar/list_pagina'><button type='button' class='btn btn-xs btn-roxo'>Listar</button></a> ";
                }
                if ($botao_editar) {
                    echo "<a href='" . pg . "/editar/edit_pagina?id=" . $row_paginas['id'] . "'><button type='button' class='btn btn-xs btn-warning'>Editar</button></a> ";
                }
                ?>
            </div>

            <div class="page-header">
                <h1>Detalhes da Página</h1>
            </div>
            <?php
            if (isset($_SESSION['msg'])) {
                echo $_SESSION['msg'];
                unset($_SESSION['msg']);
            }
            ?>
            <dl class="dl-horizontal">
                <dt>ID</dt>
                <dd><?php echo $row_paginas['id']; ?></dd>

                <dt>Endereço</dt>
                <dd><?php echo $row_paginas['endereco']; ?></dd>

                <dt>Nome da Página</dt>
                <dd><?php echo $row_paginas['nome_pagina']; ?></dd>

                <dt>Observação</dt>
                <dd><?php
                    if (!empty($row_paginas['obs'])) {
                        echo $row_paginas['obs'];
                    }
                    ?>
                </dd>
                
                <dt>Inserido</dt>
                <dd><?php echo date('d/m/Y H:i:s', strtotime($row_paginas['created'])); ?></dd>

                <dt>Alterado</dt>
                <dd><?php
                    if (!empty($row_paginas['modified'])) {
                        echo date('d/m/Y H:i:s', strtotime($row_paginas['modified']));
                    }
                    ?>
                </dd>
            </dl>
        </div>
        <?php
    } else {
        $_SESSION['msg'] = "<div class='alert alert-danger'>Página não encontrada!</div>";
        $url_destino = pg . "/listar/list_pagina";
        header("Location: $url_destino");
    }
} else {
    $_SESSION['msg'] = "<div class='alert alert-danger'>Página não encontrada!</div>";
    $url_destino = pg . "/listar/list_pagina";
    header("Location: $url_destino");
}

