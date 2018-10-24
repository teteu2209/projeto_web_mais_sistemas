<?php

if (!isset($seguranca)) {
    exit;
}

$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
if (!empty($id)) {
    echo "$id <br>";
    //Pesquisar no banco de dados as informações na tabela niveis_acessos_paginas
    if ($_SESSION['niveis_acesso_id'] == 1) {
        echo "Super Administrador <br>";
        $result_niv_ac_pg = "SELECT * 
                FROM niveis_acessos_paginas
                WHERE id='$id' LIMIT 1";
    } else {
        echo "Administrador <br>";
        $result_niv_ac_pg = "SELECT nivacpg.* 
                FROM niveis_acessos_paginas nivacpg
                INNER JOIN niveis_acessos nivac ON nivac.id=nivacpg.niveis_acesso_id
                WHERE nivacpg.id='$id' AND nivac.ordem > '" . $_SESSION['ordem'] . "' LIMIT 1";
    }

    $resultado_niv_ac_pg = mysqli_query($conn, $result_niv_ac_pg);
    //Retornou algum valor do banco de dados acessa o IF, senão acessa ELSE
    if (($resultado_niv_ac_pg) AND ( $resultado_niv_ac_pg->num_rows != 0)) {
        echo "Encontrou o item: Alterar ordem <br>";
        $row_niv_ac_pg = mysqli_fetch_assoc($resultado_niv_ac_pg);
        var_dump($row_niv_ac_pg);
        //Pesquisar o ID do niveis_acessos_paginas a ser movido para baixo
        echo "Ordem atual: " . $row_niv_ac_pg['ordem'] . "<br>";
        $ordem_num_menor = $row_niv_ac_pg['ordem'] - 1;
        echo "Ordem menor: " . $ordem_num_menor . "<br>";
        $result_niv_num_men = "SELECT id, ordem FROM niveis_acessos_paginas 
            WHERE ordem='$ordem_num_menor' AND niveis_acesso_id ='" . $row_niv_ac_pg['niveis_acesso_id'] . "' LIMIT 1";
        $resultado_niv_num_men = mysqli_query($conn, $result_niv_num_men);
        $row_niv_num_men = mysqli_fetch_assoc($resultado_niv_num_men);
        var_dump($row_niv_num_men);

        //Alterar a ordem do número menor para o número maior
        $result_ins_num_maior = "UPDATE niveis_acessos_paginas SET
                ordem='" . $row_niv_ac_pg['ordem'] . "', 
                modified=NOW()
                WHERE id='" . $row_niv_num_men['id'] . "'";
        $resultado_ins_num_maior = mysqli_query($conn, $result_ins_num_maior);

        //Alterar a ordem do número maior para o número menor
        $result_ins_num_menor = "UPDATE niveis_acessos_paginas SET
                ordem='$ordem_num_menor',
                modified=NOW() 
                WHERE id='" . $row_niv_ac_pg['id'] . "'";
        $resultado_ins_num_menor = mysqli_query($conn, $result_ins_num_menor);

        //Redirecionar conforme a situação do alterar: sucesso ou erro
        if (mysqli_affected_rows($conn)) {
            $_SESSION['msg'] = "<div class='alert alert-success'>Ordem do menu editado com sucesso!</div>";
            $url_destino = pg . "/listar/list_permissao?id=".$row_niv_ac_pg['niveis_acesso_id'];
            header("Location: $url_destino");
        } else {
            $_SESSION['msg'] = "<div class='alert alert-danger'>Erro ao editar a ordem do menu!</div>";
            $url_destino = pg . "/listar/list_permissao?id=".$row_niv_ac_pg['niveis_acesso_id'];
            header("Location: $url_destino");
        }
    } else {
        $_SESSION['msg'] = "<div class='alert alert-danger'>A ordem do menu não pode ser alterado!</div>";
        $url_destino = pg . "/listar/list_niv_acessos";
        header("Location: $url_destino");
    }
} else {
    $_SESSION['msg'] = "<div class='alert alert-danger'>Item do menu não encontrado!</div>";
    $url_destino = pg . "/listar/list_niv_acessos";
    header("Location: $url_destino");
}

