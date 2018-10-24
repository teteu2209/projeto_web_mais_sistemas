<?php

if (!isset($seguranca)) {
    exit;
}
$SendCadNivAcesso = filter_input(INPUT_POST, 'SendCadNivAcesso', FILTER_SANITIZE_STRING);
if ($SendCadNivAcesso) {
    $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

    //validar nenhum campo vazio
    $erro = false;
    $dados_validos = vazio($dados);
    if (!$dados_validos) {
        $erro = true;
        $_SESSION['msg'] = "<div class='alert alert-danger'>Necessário preencher todos os campos para cadastrar o nível de acesso!</div>";
    }
    
    else {
        //Proibir cadastro de nivel de acesso duplicado
        $result_niv_acesso = "SELECT id FROM niveis_acessos WHERE nome_nivel_acesso='" . $dados_validos['nome_nivel_acesso'] . "' LIMIT 1";
        $resultado_niv_acesso = mysqli_query($conn, $result_niv_acesso);
        if (($resultado_niv_acesso) AND ( $resultado_niv_acesso->num_rows != 0)) {
            $erro = true;
            $_SESSION['msg'] = "<div class='alert alert-danger'>Este nível de acesso já está cadastrado!</div>";
        }
    }

    if ($erro) {
        $_SESSION['dados'] = $dados;
        $url_destino = pg . "/cadastrar/cad_niv_acessos";
        header("Location: $url_destino");
    } else {
        //Pesquisar a última ordem do nivel de acesso
        $result_niv_ordem = "SELECT ordem FROM niveis_acessos ORDER BY ordem DESC LIMIT 1";
        $resultado_niv_ordem = mysqli_query($conn, $result_niv_ordem);
        $row_niv_ordem = mysqli_fetch_assoc($resultado_niv_ordem);
        $row_niv_ordem['ordem'] ++;

        $result_niv_acesso = "INSERT INTO niveis_acessos (nome_nivel_acesso, ordem, created) VALUES (
                '" . $dados_validos['nome_nivel_acesso'] . "',
                '" . $row_niv_ordem['ordem'] . "',
                NOW())";
        $resultado_niv_acesso = mysqli_query($conn, $result_niv_acesso);
        if (mysqli_insert_id($conn)) {
            unset($_SESSION['dados']);
            $_SESSION['msg'] = "<div class='alert alert-success'>Cadastrado com sucesso o nível de acesso</div>";
            $url_destino = pg . "/listar/list_niv_acessos";
            header("Location: $url_destino");
        } else {
            $_SESSION['msg'] = "<div class='alert alert-danger'>Erro ao cadastrar o nível de acesso</div>";
            $url_destino = pg . "/cadastrar/cad_niv_acessos";
            header("Location: $url_destino");
        }
    }
} else {
    $_SESSION['msg'] = "<div class='alert alert-danger'>Erro ao carregar a página</div>";
    $url_destino = pg . "/listar/list_niv_acessos";
    header("Location: $url_destino");
}

