<?php

if (!isset($seguranca)) {
    exit;
}

$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
if (!empty($id)) {
    //Verificar se há produtos cadastrado nessa categoria
    $result_prod = "SELECT id FROM produtos WHERE categorias_produto_id = '$id' LIMIT 2";
    $resultado_prod = mysqli_query($conn, $result_prod);
    if (($resultado_prod) AND ( $resultado_prod->num_rows != 0)) {
        $_SESSION['msg'] = "<div class='alert alert-danger'>A categoria de produto não pode ser apagada, há produtos cadastrados neste categoria!</div>";
        $url_destino = pg . "/listar/list_cat_prod";
        header("Location: $url_destino");
    } else {//Não há nenhum produto cadastro nessa categoria
        $result_cat_prod = "DELETE FROM categorias_produtos WHERE id='$id'";
        $resultado_cat_prod = mysqli_query($conn, $result_cat_prod);

        if (mysqli_affected_rows($conn)) {
            $_SESSION['msg'] = "<div class='alert alert-success'>Categoria de produto apagado com sucesso!</div>";
            $url_destino = pg . "/listar/list_cat_prod";
            header("Location: $url_destino");
        } else {
            $_SESSION['msg'] = "<div class='alert alert-danger'>Erro: Categoria de produto não foi apagada!</div>";
            $url_destino = pg . "/listar/list_cat_prod";
            header("Location: $url_destino");
        }
    }
} else {
    $_SESSION['msg'] = "<div class='alert alert-danger'>Categoria de produto não encontrado!</div>";
    $url_destino = pg . "/listar/list_cat_prod";
    header("Location: $url_destino");
}

