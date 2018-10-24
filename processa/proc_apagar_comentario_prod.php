<?php

if (!isset($seguranca)) {
    exit;
}

$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
if (!empty($id)) {
    $result_comentario = "DELETE FROM comentarios_produtos WHERE id='$id'";
    $resultado_comentario = mysqli_query($conn, $result_comentario);

    if (mysqli_affected_rows($conn)) {
        $_SESSION['msg'] = "<div class='alert alert-success'>Comentário apagado com sucesso!</div>";
        $url_destino = pg . "/listar/list_comentario_prod";
        header("Location: $url_destino");
    } else {
        $_SESSION['msg'] = "<div class='alert alert-danger'>Erro: Comentário não foi apagada!</div>";
        $url_destino = pg . "/listar/list_comentario_prod";
        header("Location: $url_destino");
    }
} else {
    $_SESSION['msg'] = "<div class='alert alert-danger'>Comentário não encontrado!</div>";
    $url_destino = pg . "/listar/list_comentario_prod";
    header("Location: $url_destino");
}

