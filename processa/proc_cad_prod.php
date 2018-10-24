<?php

if (!isset($seguranca)) {
    exit;
}

$SendCadProd = filter_input(INPUT_POST, 'SendCadProd', FILTER_SANITIZE_STRING);
if ($SendCadProd) {
    $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

    //validar nenhum campo vazio
    $erro = false;
    $dados_validos = vazio($dados);
    if (!$dados_validos) {
        $erro = true;
        $_SESSION['msg'] = "<div class='alert alert-danger'>Necessário preencher todos os campos para cadastrar o produto!</div>";
    } //validar extensão da imagem
    elseif (!empty($_FILES['foto']['name'])) {
        $foto = $_FILES['foto'];
        if (!validarExtesao($foto['type'])) {
            $erro = true;
            $_SESSION['msg'] = "<div class='alert alert-danger'>Extensão da foto inválida!</div>";
        } else {
            $foto['name'] = caracterEspecial($foto['name']);
            $campo_foto = "foto,";
            $valor_foto = "'" . $foto['name'] . "',";
        }
    }

    //Proibir Cadastrar Código do Produto duplicado
    $dados_validos['codigo_produto'] = caracterEspecial($dados_validos['codigo_produto']);
    $result_codigo_produto = "SELECT id FROM produtos WHERE codigo_produto='" . $dados_validos['codigo_produto'] . "' AND id <> '" . $dados['id'] . "' LIMIT 1";
    $resultado_codigo_produto = mysqli_query($conn, $result_codigo_produto);
    if (($resultado_codigo_produto) AND ( $resultado_codigo_produto->num_rows != 0)) {
        $erro = true;
        $_SESSION['msg'] = "<div class='alert alert-danger'>O código do produto já está cadastrado!</div>";
    }

    //Criar as variaveis da foto quando a mesma não está sendo cadastrada
    if (empty($_FILES['foto']['name'])) {
        $campo_foto = "";
        $valor_foto = "";
    }

    if ($erro) {
        $_SESSION['dados'] = $dados;
        $url_destino = pg . "/cadastrar/cad_prod";
        header("Location: $url_destino");
    } else {
        $dados_validos['valor_compra'] = str_replace(".", "", $dados_validos['valor_compra']);
        $dados_validos['valor_compra'] = str_replace(",", ".", $dados_validos['valor_compra']);

        $dados_validos['valor_venda'] = str_replace(".", "", $dados_validos['valor_venda']);
        $dados_validos['valor_venda'] = str_replace(",", ".", $dados_validos['valor_venda']);

        $dados_validos['valor_venda'] = str_replace(".", "", $dados_validos['altura_produto']);
        $dados_validos['valor_venda'] = str_replace(",", ".", $dados_validos['altura_produto']);

        $dados_validos['valor_venda'] = str_replace(".", "", $dados_validos['largura_produto']);
        $dados_validos['valor_venda'] = str_replace(",", ".", $dados_validos['largura_produto']);

        $dados_validos['valor_venda'] = str_replace(".", "", $dados_validos['profundidade_produto']);
        $dados_validos['valor_venda'] = str_replace(",", ".", $dados_validos['profundidade_produto']);

        $result_prod = "INSERT INTO produtos (nome, $campo_foto  descricao, altura_produto, largura_produto, profundidade_produto,  codigo_produto,  valor_compra, valor_venda,  disponivel_estoque, min_estoque, max_estoque, cadastro, categorias_produto_id, fornecedore_id, situacao_id, created) 
                VALUES(
                '" . $dados_validos['nome'] . "', 
              $valor_foto                 
                '" . $dados_validos['descricao'] . "',
                '" . $dados_validos['altura_produto'] . "',
                '" . $dados_validos['largura_produto'] . "',
                '" . $dados_validos['profundidade_produto'] . "',               
                '" . $dados_validos['codigo_produto'] . "',          
                '" . $dados_validos['valor_compra'] . "',
                '" . $dados_validos['valor_venda'] . "',        
                '" . $dados_validos['disponivel_estoque'] . "',
                '" . $dados_validos['min_estoque'] . "',
                '" . $dados_validos['max_estoque'] . "',
                '" . $_SESSION['id'] . "',
                '" . $dados_validos['categorias_produto_id'] . "',
                '" . $dados_validos['fornecedore_id'] . "',
                '" . $dados_validos['situacao_id'] . "',
                 NOW())";
        $resultado_prod = mysqli_query($conn, $result_prod);
        if (mysqli_insert_id($conn)) {
            unset($_SESSION['dados']);

            //Redimensionar a imagem e fazer upload
            if (!empty($_FILES['foto']['name'])) {
                $destino = "assets/imagens/produto/" . mysqli_insert_id($conn) . "/";
                upload($foto, $destino, 200, 200);
            }

            $_SESSION['msg'] = "<div class='alert alert-success'>Produto cadastrado com sucesso</div>";
            $url_destino = pg . "/listar/list_prod";
            header("Location: $url_destino");
        } else {
            $_SESSION['msg'] = "<div class='alert alert-danger'>Erro ao cadastrar o produto</div>";
            $url_destino = pg . "/cadastrar/cad_prod";
            header("Location: $url_destino");
        }
    }
} else {
    $_SESSION['msg'] = "<div class='alert alert-danger'>Erro ao carregar a página</div>";
    $url_destino = pg . "/listar/list_prod";
    header("Location: $url_destino");
}