<?php

if (!isset($seguranca)) {
    exit;
}

$SendCadCatProd = filter_input(INPUT_POST, 'SendCadCatProd', FILTER_SANITIZE_STRING);
if ($SendCadCatProd) {
    $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

    //validar nenhum campo vazio
    $erro = false;
    $dados_validos = vazio($dados);
    if (!$dados_validos) {
        $erro = true;
        $_SESSION['msg'] = "<div class='alert alert-danger'>Necessário preencher todos os campos para cadastrar a categoria de produto!</div>";
    }
    
    else {
        //Proibir cadastro de categoria de produto duplicado
        $result_cat_prod = "SELECT id FROM categorias_produtos WHERE nome='" . $dados_validos['nome'] . "' LIMIT 1";
        $resultado_cat_prod = mysqli_query($conn, $result_cat_prod);
        if (($resultado_cat_prod) AND ( $resultado_cat_prod->num_rows != 0)) {
            $erro = true;
            $_SESSION['msg'] = "<div class='alert alert-danger'>Esta categoria de produto já está cadastrada!</div>";
        }
    }

    if ($erro) {
        $_SESSION['dados'] = $dados;
        $url_destino = pg . "/cadastrar/cad_cat_prod";
        header("Location: $url_destino");
    } else {
        $result_cat_prod = "INSERT INTO categorias_produtos (nome, situacao_id, created) VALUES (
                '" . $dados_validos['nome'] . "',
                '" . $dados_validos['situacao_id'] . "',
                NOW())";
        $resultado_cat_prod = mysqli_query($conn, $result_cat_prod);
        if (mysqli_insert_id($conn)) {
            unset($_SESSION['dados']);
            $_SESSION['msg'] = "<div class='alert alert-success'>Cadastrado com sucesso a categoria de produto</div>";
            $url_destino = pg . "/listar/list_cat_prod";
            header("Location: $url_destino");
        }else{
            $_SESSION['msg'] = "<div class='alert alert-danger'>Erro ao cadastrar a categoria de produto</div>";
            $url_destino = pg . "/cadastrar/cad_cat_prod";
            header("Location: $url_destino");
        }
    }
} else {
    $_SESSION['msg'] = "<div class='alert alert-danger'>Erro ao carregar a página</div>";
    $url_destino = pg . "/listar/list_cat_prod";
    header("Location: $url_destino");
}