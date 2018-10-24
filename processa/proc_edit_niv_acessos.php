<?php
if(!isset($seguranca)){exit;}
//Recuperar o valor do botao
$SendEditNivAcessos = filter_input(INPUT_POST, 'SendEditNivAcessos', FILTER_SANITIZE_STRING);
//Botão vazio redireciona para o listar
if($SendEditNivAcessos){
    $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        
    //validar nenhum campo vazio
    $erro = false;
    $dados_validos = vazio($dados);
    if (!$dados_validos) {
        $erro = true;
        $_SESSION['msg'] = "<div class='alert alert-danger'>Necesários preencher todos os campos para cadastrar o usuário!</div>";
    }
        
    else {
        //Proibir cadastro de nivel de acesso duplicado
        $result_niv_acesso = "SELECT id FROM niveis_acessos WHERE nome_nivel_acesso='" . $dados_validos['nome_nivel_acesso'] . "' AND id <> '".$dados['id']."' LIMIT 1";
        $resultado_niv_acesso = mysqli_query($conn, $result_niv_acesso);
        if (($resultado_niv_acesso) AND ( $resultado_niv_acesso->num_rows != 0)) {
            $erro = true;
            $_SESSION['msg'] = "<div class='alert alert-danger'>Este nível de acesso já está cadastrado!</div>";
        }
    }
        
    //Houve erro em algum campo será redirecionado para o formulário, não há erro no formulário tenta editar no banco
    if ($erro) {
        $_SESSION['dados'] = $dados;
        $url_destino = pg . "/editar/edit_niv_acessos?id=".$dados['id'];
        header("Location: $url_destino");
    } else {
        $result_niv_acesso = "UPDATE niveis_acessos SET
                nome_nivel_acesso='" . $dados_validos['nome_nivel_acesso'] . "', 
                modified=NOW()
                WHERE id='".$dados_validos['id']."'";
        $resultado_niv_acesso = mysqli_query($conn, $result_niv_acesso);
        if(mysqli_affected_rows($conn)){
            unset($_SESSION['dados']);
                        
            $_SESSION['msg'] = "<div class='alert alert-success'>Nível de Acesso editado com sucesso</div>";
            $url_destino = pg . "/visualizar/ver_niv_acessos?id=".$dados['id'];
            header("Location: $url_destino");
        }else{
            $_SESSION['msg'] = "<div class='alert alert-danger'>Erro ao editar o nível de acesso</div>";
            $url_destino = pg . "/editar/edit_niv_acessos?id=".$dados['id'];
            header("Location: $url_destino");
        }
    }
}else {
    $_SESSION['msg'] = "<div class='alert alert-danger'>Erro ao carregar a página</div>";
    $url_destino = pg . "/listar/list_niv_acessos";
    header("Location: $url_destino");
}
