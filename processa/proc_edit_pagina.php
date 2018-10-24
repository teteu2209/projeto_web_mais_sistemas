<?php
if(!isset($seguranca)){exit;}
//Recuperar o valor do botao
$SendEditPagina = filter_input(INPUT_POST, 'SendEditPagina', FILTER_SANITIZE_STRING);
//Botão vazio redireciona para o listar
if($SendEditPagina){
    $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        
    //validar nenhum campo vazio
    $erro = false;
    $dados_validos = vazio($dados);
    if (!$dados_validos) {
        $erro = true;
        $_SESSION['msg'] = "<div class='alert alert-danger'>Necessário preencher todos os campos para cadastrar a página!</div>";
    }
    
    else {
        //Proibir cadastro de página duplicado
        $result_paginas = "SELECT id FROM paginas WHERE endereco='" . $dados_validos['endereco'] . "' AND id <> '".$dados['id']."' LIMIT 1";
        $resultado_paginas = mysqli_query($conn, $result_paginas);
        if (($resultado_paginas) AND ( $resultado_paginas->num_rows != 0)) {
            $erro = true;
            $_SESSION['msg'] = "<div class='alert alert-danger'>Este endereço já está cadastrado!</div>";
        }
    }
        
    //Houve erro em algum campo será redirecionado para o formulário, não há erro no formulário tenta editar no banco
    if ($erro) {
        $_SESSION['dados'] = $dados;
        $url_destino = pg . "/editar/edit_pagina?id=".$dados['id'];
        header("Location: $url_destino");
    } else {
        $result_paginas = "UPDATE paginas SET
                endereco='" . $dados_validos['endereco'] . "', 
                nome_pagina='" . $dados_validos['nome_pagina'] . "',
                obs='" . $dados_validos['obs'] . "',
                modified=NOW()
                WHERE id='".$dados_validos['id']."'";
        $resultado_paginas = mysqli_query($conn, $result_paginas);
        if(mysqli_affected_rows($conn)){
            unset($_SESSION['dados']);
                        
            $_SESSION['msg'] = "<div class='alert alert-success'>Página editada com sucesso</div>";
            $url_destino = pg . "/visualizar/ver_pagina?id=".$dados['id'];
            header("Location: $url_destino");
        }else{
            $_SESSION['msg'] = "<div class='alert alert-danger'>Erro ao editar a página</div>";
            $url_destino = pg . "/editar/edit_niv_acessos?id=".$dados['id'];
            header("Location: $url_destino");
        }
    }
}else {
    $_SESSION['msg'] = "<div class='alert alert-danger'>Erro ao carregar a página</div>";
    $url_destino = pg . "/listar/list_pagina";
    header("Location: $url_destino");
}
