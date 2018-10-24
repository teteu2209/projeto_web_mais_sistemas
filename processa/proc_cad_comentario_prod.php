<?php

if (!isset($seguranca)) {
    exit;
}

$SendCadComentarioProd = filter_input(INPUT_POST, 'SendCadComentarioProd', FILTER_SANITIZE_STRING);
if ($SendCadComentarioProd) {
    $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

    //validar nenhum campo vazio
    $erro = false;
    $dados_validos = vazio($dados);
    if (!$dados_validos) {
        $erro = true;
        $_SESSION['msg'] = "<div class='alert alert-danger'>Necessário preencher todos os campos para cadastrar o comentário do produto!</div>";
    }

    if ($erro) {
        $_SESSION['dados'] = $dados;
        $url_destino = pg . "/cadastrar/cad_comentario_prod";
        header("Location: $url_destino");
    } else {
        $result_comentario_prod = "INSERT INTO comentarios_produtos (comentario, produto_id, situacao_id, created) VALUES (
                '" . $dados_validos['comentario'] . "',
                '" . $dados_validos['produto_id'] . "',
                '" . $dados_validos['situacao_id'] . "',
                NOW())";
        $resultado_comentario_prod = mysqli_query($conn, $result_comentario_prod);
        if (mysqli_insert_id($conn)) {
            unset($_SESSION['dados']);
            $_SESSION['msg'] = "<div class='alert alert-success'>Cadastrado com sucesso o comentário do produto</div>";
            $url_destino = pg . "/listar/list_comentario_prod";
            header("Location: $url_destino");
        } else {
            $_SESSION['msg'] = "<div class='alert alert-danger'>Erro ao cadastrar o comentário do produto</div>";
            $url_destino = pg . "/cadastrar/cad_comentario_prod";
            header("Location: $url_destino");
        }
    }
} else {
    $_SESSION['msg'] = "<div class='alert alert-danger'>Erro ao carregar a página</div>";
    $url_destino = pg . "/listar/list_comentario_prod";
    header("Location: $url_destino");
}