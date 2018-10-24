<?php
if (!isset($seguranca)) {
    exit;
}
//Recuperar o valor do botao
$SendEditComentarioProd = filter_input(INPUT_POST, 'SendEditComentarioProd', FILTER_SANITIZE_STRING);
//Botão vazio redireciona para o listar
if ($SendEditComentarioProd) {
    $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

    //validar nenhum campo vazio
    $erro = false;
    $dados_validos = vazio($dados);
    if (!$dados_validos) {
        $erro = true;
        $_SESSION['msg'] = "<div class='alert alert-danger'>Necessário preencher todos os campos para editar o comentário do produto!</div>";
    } else {
        //Proibir cadastro de categoria de produto duplicado
        $result_comentario_prod = "SELECT id FROM comentarios_produtos WHERE comentario='" . $dados_validos['comentario'] . "' AND id <> '" . $dados['id'] . "' LIMIT 1";
        $resultado_comentario_prod = mysqli_query($conn, $result_comentario_prod);
        if (($resultado_comentario_prod) AND ($resultado_comentario_prod->num_rows != 0)) {
            $erro = true;
            $_SESSION['msg'] = "<div class='alert alert-danger'>Esta categoria de produto já está cadastrada!</div>";
        }
    }

    //Houve erro em algum campo será redirecionado para o formulário, não há erro no formulário tenta editar no banco
    if ($erro) {
        $_SESSION['dados'] = $dados;
        $url_destino = pg . "/editar/edit_comentario_prod?id=" . $dados['id'];
        header("Location: $url_destino");
    } else {
        $result_comentario_prod = "UPDATE comentarios_produtos SET
                comentario='" . $dados_validos['comentario'] . "', 
                produto_id='" . $dados_validos['produto_id'] . "', 
                situacao_id='" . $dados_validos['situacao_id'] . "', 
                modified=NOW()
                WHERE id='" . $dados_validos['id'] . "'";
        $resultado_comentario_prod = mysqli_query($conn, $result_comentario_prod);
        if (mysqli_affected_rows($conn)) {
            unset($_SESSION['dados']);

            $_SESSION['msg'] = "<div class='alert alert-success'>Comentário do Produto editado com sucesso</div>";
            $url_destino = pg . "/visualizar/ver_comentario_prod?id=" . $dados['id'];
            header("Location: $url_destino");
        } else {
            $_SESSION['msg'] = "<div class='alert alert-danger'>Erro ao editar o comentário do produto</div>";
            $url_destino = pg . "/editar/edit_comentario_prod?id=" . $dados['id'];
            header("Location: $url_destino");
        }
    }
} else {
    $_SESSION['msg'] = "<div class='alert alert-danger'>Erro ao carregar a página</div>";
    $url_destino = pg . "/listar/list_comentario_prod";
    header("Location: $url_destino");
}
