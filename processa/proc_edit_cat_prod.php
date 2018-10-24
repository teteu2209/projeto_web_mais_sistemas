<?php
if(!isset($seguranca)){exit;}
//Recuperar o valor do botao
$SendEditCatProd = filter_input(INPUT_POST, 'SendEditCatProd', FILTER_SANITIZE_STRING);
//Botão vazio redireciona para o listar
if($SendEditCatProd){
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
        $result_cat_prod = "SELECT id FROM categorias_produtos WHERE nome='" . $dados_validos['nome'] . "' AND id <> '".$dados['id']."' LIMIT 1";
        $resultado_cat_prod = mysqli_query($conn, $result_cat_prod);
        if (($resultado_cat_prod) AND ( $resultado_cat_prod->num_rows != 0)) {
            $erro = true;
            $_SESSION['msg'] = "<div class='alert alert-danger'>Esta categoria de produto já está cadastrada!</div>";
        }
    }
        
    //Houve erro em algum campo será redirecionado para o formulário, não há erro no formulário tenta editar no banco
    if ($erro) {
        $_SESSION['dados'] = $dados;
        $url_destino = pg . "/editar/edit_cat_prod?id=".$dados['id'];
        header("Location: $url_destino");
    } else {
        $result_cat_prod = "UPDATE categorias_produtos SET
                nome='" . $dados_validos['nome'] . "', 
                situacao_id='" . $dados_validos['situacao_id'] . "', 
                modified=NOW()
                WHERE id='".$dados_validos['id']."'";
        $resultado_cat_prod = mysqli_query($conn, $result_cat_prod);
        if(mysqli_affected_rows($conn)){
            unset($_SESSION['dados']);
                        
            $_SESSION['msg'] = "<div class='alert alert-success'>Categoria de Produto editado com sucesso</div>";
            $url_destino = pg . "/visualizar/ver_cat_prod?id=".$dados['id'];
            header("Location: $url_destino");
        }else{
            $_SESSION['msg'] = "<div class='alert alert-danger'>Erro ao editar a categoria de produto</div>";
            $url_destino = pg . "/editar/edit_cat_prod?id=".$dados['id'];
            header("Location: $url_destino");
        }
    }
}else {
    $_SESSION['msg'] = "<div class='alert alert-danger'>Erro ao carregar a página</div>";
    $url_destino = pg . "/listar/list_cat_prod";
    header("Location: $url_destino");
}
