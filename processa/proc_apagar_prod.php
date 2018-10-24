<?php

if (!isset($seguranca)) {
    exit;
}

$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
if (!empty($id)) {
    $result_prod = "DELETE FROM produtos WHERE id='$id'";
    $resultado_prod = mysqli_query($conn, $result_prod);

    if (mysqli_affected_rows($conn)) {
        $_SESSION['msg'] = "<div class='alert alert-success'>Produto apagado com sucesso!</div>";
        $url_destino = pg . "/listar/list_prod";
        header("Location: $url_destino");
    } else {
        $_SESSION['msg'] = "<div class='alert alert-danger'>Erro: Produto não foi apagada!</div>";
        $url_destino = pg . "/listar/list_prod";
        header("Location: $url_destino");
    }
} else {
    $_SESSION['msg'] = "<div class='alert alert-danger'>Produto não encontrado!</div>";
    $url_destino = pg . "/listar/list_prod";
    header("Location: $url_destino");
}

