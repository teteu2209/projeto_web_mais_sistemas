<?php

if (!isset($seguranca)) {
    exit;
}
$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);
if (!empty($id)) {
    if ($_SESSION['niveis_acesso_id'] == 1) {
        //Pesquisar os dados da tabela niveis_acessos_paginas
        $result_niv_ac_pg = "SELECT nivacpg.*,
            nivac.ordem ordem_nivac
            FROM niveis_acessos_paginas nivacpg
            INNER JOIN niveis_acessos nivac ON nivac.id=nivacpg.niveis_acesso_id
            WHERE nivacpg.id='$id' LIMIT 1";
    } else {
        //Pesquisar os dados da tabela niveis_acessos_paginas
        $result_niv_ac_pg = "SELECT nivacpg.*,
            nivac.ordem ordem_nivac
            FROM niveis_acessos_paginas nivacpg
            INNER JOIN niveis_acessos nivac ON nivac.id=nivacpg.niveis_acesso_id
            WHERE nivacpg.id='$id' AND nivac.ordem > '" . $_SESSION['ordem'] . "' LIMIT 1";
    }

    $resultado_niv_ac_pg = mysqli_query($conn, $result_niv_ac_pg);
    //Retornou algum valor do banco de dados e acesso o IF, senão acessa o ELSe
    if (($resultado_niv_ac_pg) AND ( $resultado_niv_ac_pg->num_rows != 0)) {
        $row_niv_ac_pg = mysqli_fetch_assoc($resultado_niv_ac_pg);

        //Verificar o status da página e atribuir o inverso na variável status
        if ($row_niv_ac_pg['menu'] == 1) {
            $status = 2;
        } else {
            $status = 1;
        }

        $result_niv_pg_up = "UPDATE niveis_acessos_paginas SET
                menu='$status',
                modified=NOW()
                WHERE id='$id'";
        $resultado_niv_pg_up = mysqli_query($conn, $result_niv_pg_up);
        if (mysqli_affected_rows($conn)) {
            $_SESSION['msg'] = "<div class='alert alert-success'>Situação do menu editado com sucesso!</div>";
            $url_destino = pg . "/listar/list_permissao?id=" . $row_niv_ac_pg['niveis_acesso_id'];
            header("Location: $url_destino");
        } else {
            $_SESSION['msg'] = "<div class='alert alert-danger'>Erro: A situação do menu não foi alterada com sucesso!</div>";
            $url_destino = pg . "/listar/list_permissao?id=" . $row_niv_ac_pg['niveis_acesso_id'];
            header("Location: $url_destino");
        }
    } else {
        $_SESSION['msg'] = "<div class='alert alert-danger'>Nível de Acesso não encontrado</div>";
        $url_destino = pg . "/listar/list_niv_acessos";
        header("Location: $url_destino");
    }
} else {
    $_SESSION['msg'] = "<div class='alert alert-danger'>Nível de Acesso não encontrado</div>";
    $url_destino = pg . "/listar/list_niv_acessos";
    header("Location: $url_destino");
}
