<?php
if(!isset($seguranca)){exit;}
//Recuperar o valor do botao
$SendEditUsuario = filter_input(INPUT_POST, 'SendEditUsuario', FILTER_SANITIZE_STRING);
//Botão vazio redireciona para o listar
if($SendEditUsuario){
    $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        
    $dados['senha'] = str_replace(" ", "", $dados['senha']);
    
    //validar nenhum campo vazio
    $erro = false;
    $dados_validos = vazio($dados);
    if (!$dados_validos) {
        $erro = true;
        $_SESSION['msg'] = "<div class='alert alert-danger'>Necesários preencher todos os campos para cadastrar o usuário!</div>";
    }

    //validar senha
    elseif ((strlen($dados_validos['senha'])) < 6) {
        $erro = true;
        $_SESSION['msg'] = "<div class='alert alert-danger'>A senha deve ter no mínimo 6 caracteres!</div>";
    } elseif (stristr($dados_validos['senha'], "'")) {
        $erro = true;
        $_SESSION['msg'] = "<div class='alert alert-danger'>Caracter ( ' ) utilizado na senha inválido!</div>";
    }
    
    //Houve erro em algum campo será redirecionado para o formulário, não há erro no formulário tenta editar no banco
    if ($erro) {
        $_SESSION['dados'] = $dados;
        $url_destino = pg . "/editar/edit_senha";
        header("Location: $url_destino");
    } else {
       //Criptografar a senha
        $dados_validos['senha'] = password_hash($dados_validos['senha'], PASSWORD_DEFAULT);
        $result_usuario = "UPDATE usuarios SET
                senha='" . $dados_validos['senha'] . "',                
                modified=NOW()
                WHERE id='".$_SESSION['id']."'";
        $resultado_usuario = mysqli_query($conn, $result_usuario);
        if(mysqli_affected_rows($conn)){
            unset($_SESSION['dados']);
            
            $_SESSION['msg'] = "<div class='alert alert-success'>Senha editada com sucesso</div>";
            $url_destino = pg . "/visualizar/perfil";
            header("Location: $url_destino");
        }else{
            $_SESSION['msg'] = "<div class='alert alert-danger'>Erro ao editar a senha</div>";
            $url_destino = pg . "/editar/edit_senha";
            header("Location: $url_destino");
        }
    }
}else {
    $_SESSION['msg'] = "<div class='alert alert-danger'>Erro ao carregar a página</div>";
    $url_destino = pg . "/visualizar/home";
    header("Location: $url_destino");
}
