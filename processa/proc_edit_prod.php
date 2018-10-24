<?php

if (!isset($seguranca)) {
    exit;
}
//Recuperar o valor do botao
$SendEditProd = filter_input(INPUT_POST, 'SendEditProd', FILTER_SANITIZE_STRING);
//Botão vazio redireciona para o listar
if ($SendEditProd) {
    $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

    //Retira o campo "foto_antiga" da validação vazio
    $foto_antiga = $dados['foto_antiga'];
    unset($dados['foto_antiga']);

    //validar nenhum campo vazio
    $erro = false;
    $dados_validos = vazio($dados);
    if (!$dados_validos) {
        $erro = true;
        $_SESSION['msg'] = "<div class='alert alert-danger'>Necessário preencher todos os campos para editar o produto!</div>";
    } //validar extensão da imagem
    elseif (!empty($_FILES['foto']['name'])) {
        $foto = $_FILES['foto'];
        if (!validarExtesao($foto['type'])) {
            $erro = true;
            $_SESSION['msg'] = "<div class='alert alert-danger'>Extensão da foto inválida!</div>";
        } else {
            $foto['name'] = caracterEspecial($foto['name']);
            $campo_foto = "foto=";
            $valor_foto = "'" . $foto['name'] . "',";
        }
    }

    //Proibir Editar Código do Produto duplicado
    $dados_validos['codigo_produto'] = caracterEspecial($dados_validos['codigo_produto']);
    $result_codigo_produto = "SELECT id FROM produtos WHERE codigo_produto='" . $dados_validos['codigo_produto'] . "' AND id <> '" . $dados['id'] . "' LIMIT 1";
    $resultado_codigo_produto = mysqli_query($conn, $result_codigo_produto);
    if (($resultado_codigo_produto) AND ($resultado_codigo_produto->num_rows != 0)) {
        $erro = true;
        $_SESSION['msg'] = "<div class='alert alert-danger'>O código do produto já está cadastrado!</div>";
    }

    //Criar as variaveis da foto quando a mesma não está sendo cadastrada
    if (empty($_FILES['foto']['name'])) {
        $campo_foto = "";
        $valor_foto = "";
    }

    //Houve erro em algum campo será redirecionado para o formulário, não há erro no formulário tenta editar no banco
    if ($erro) {
        $_SESSION['dados'] = $dados;
        $url_destino = pg . "/editar/edit_prod?id=" . $dados['id'];
        header("Location: $url_destino");
    } else {
        $dados_validos['valor_compra'] = str_replace(".", "", $dados_validos['valor_compra']);
        $dados_validos['valor_compra'] = str_replace(",", ".", $dados_validos['valor_compra']);

        $dados_validos['valor_venda'] = str_replace(".", "", $dados_validos['valor_venda']);
        $dados_validos['valor_venda'] = str_replace(",", ".", $dados_validos['valor_venda']);
        $result_prod = "UPDATE produtos SET
                nome='" . $dados_validos['nome'] . "', 
                $campo_foto $valor_foto                            
                descricao='" . $dados_validos['descricao'] . "', 
                codigo_produto='" . $dados_validos['codigo_produto'] . "', 
                altura_produto='" . $dados_validos['altura_produto'] . "', 
                largura_produto='" . $dados_validos['largura_produto'] . "', 
                profundidade_produto='" . $dados_validos['profundidade_produto'] . "',                 
                valor_compra='" . $dados_validos['valor_compra'] . "', 
                valor_venda='" . $dados_validos['valor_venda'] . "',               
                disponivel_estoque='" . $dados_validos['disponivel_estoque'] . "', 
                min_estoque='" . $dados_validos['min_estoque'] . "', 
                max_estoque='" . $dados_validos['max_estoque'] . "', 
                edito='" . $_SESSION['id'] . "', 
                categorias_produto_id='" . $dados_validos['categorias_produto_id'] . "', 
                fornecedore_id='" . $dados_validos['fornecedore_id'] . "', 
                situacao_id='" . $dados_validos['situacao_id'] . "', 
                modified=NOW()
                WHERE id='" . $dados_validos['id'] . "'";
        $resultado_prod = mysqli_query($conn, $result_prod);
        if (mysqli_affected_rows($conn)) {
            unset($_SESSION['dados']);

            //Redimensionar a imagem e fazer upload
            if (!empty($_FILES['foto']['name'])) {
                $destino = "assets/imagens/produto/" . $dados['id'] . "/";
                $destino_apagar = $destino . $foto_antiga;
                apagarFoto($destino_apagar);
                upload($foto, $destino, 200, 200);
            }

            $_SESSION['msg'] = "<div class='alert alert-success'>Produto editado com sucesso</div>";
            $url_destino = pg . "/listar/list_prod";
            header("Location: $url_destino");
        } else {
            $_SESSION['msg'] = "<div class='alert alert-danger'>Erro ao editar o produto</div>";
            $url_destino = pg . "/editar/edit_prod?id=" . $dados['id'];
            header("Location: $url_destino");
        }
    }
} else {
    $_SESSION['msg'] = "<div class='alert alert-danger'>Erro ao carregar a página</div>";
    $url_destino = pg . "/listar/list_prod";
    header("Location: $url_destino");
}
