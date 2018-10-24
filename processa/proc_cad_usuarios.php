<?php

if (!isset($seguranca)) {
    exit;
}
$SendcadUsuario = filter_input(INPUT_POST, 'SendCadUsuario', FILTER_SANITIZE_STRING);
if ($SendcadUsuario) {
    /* $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
      $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);
      $usuario = filter_input(INPUT_POST, 'usuario', FILTER_SANITIZE_STRING);
      $senha = filter_input(INPUT_POST, 'senha', FILTER_SANITIZE_STRING); */
    $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

    $dados['senha'] = str_replace(" ", "", $dados['senha']);
    $dados['usuario'] = str_replace(" ", "", $dados['usuario']);
    //validar nenhum campo vazio
    $erro = false;
    $dados_validos = vazio($dados);
    if (!$dados_validos) {
        $erro = true;
        $_SESSION['msg'] = "<div class='alert alert-danger'>Necesários preencher todos os campos para cadastrar o usuário!</div>";
    }
    //Validar e-mail
    elseif (!validarEmail($dados_validos['email'])) {
        $erro = true;
        $_SESSION['msg'] = "<div class='alert alert-danger'>E-mail inválido!</div>";
    }

    //validar senha
    elseif ((strlen($dados_validos['senha'])) < 6) {
        $erro = true;
        $_SESSION['msg'] = "<div class='alert alert-danger'>A senha deve ter no mínimo 6 caracteres!</div>";
    } elseif (stristr($dados_validos['senha'], "'")) {
        $erro = true;
        $_SESSION['msg'] = "<div class='alert alert-danger'>Caracter ( ' ) utilizado na senha inválido!</div>";
    }

    //Validar usuário
    elseif (stristr($dados_validos['usuario'], "'")) {
        $erro = true;
        $_SESSION['msg'] = "<div class='alert alert-danger'>Caracter ( ' ) utilizado no usuário inválido!</div>";
    } elseif ((strlen($dados_validos['usuario'])) < 6) {
        $erro = true;
        $_SESSION['msg'] = "<div class='alert alert-danger'>O usuário deve ter no mínimo 6 caracteres!</div>";
    }
    
    //validar extensão da imagem
    elseif(!empty ($_FILES['foto']['name'])){
        $foto = $_FILES['foto'];
        if(!validarExtesao($foto['type'])){
            $erro = true;
            $_SESSION['msg'] = "<div class='alert alert-danger'>Extensão da foto inválida!</div>";
        }else{
            $foto['name'] = caracterEspecial($foto['name']);
            $campo_foto = "foto,";
            $valor_foto = "'".$foto['name']."',";
        }
        
    }
    
    else {
        //Proibir cadastro de usuário duplicado
        $result_usuario = "SELECT id FROM usuarios WHERE usuario='" . $dados_validos['usuario'] . "' LIMIT 1";
        $resultado_usuario = mysqli_query($conn, $result_usuario);
        if (($resultado_usuario) AND ( $resultado_usuario->num_rows != 0)) {
            $erro = true;
            $_SESSION['msg'] = "<div class='alert alert-danger'>Este usuário já está cadastrado!</div>";
        }
        //Proibir cadastro de email duplicado
        $result_usuario_email = "SELECT id FROM usuarios WHERE email='" . $dados_validos['email'] . "' LIMIT 1";
        $resultado_usuario_email = mysqli_query($conn, $result_usuario_email);
        if (($resultado_usuario_email) AND ( $resultado_usuario_email->num_rows != 0)) {
            $erro = true;
            $_SESSION['msg'] = "<div class='alert alert-danger'>Este e-mail já está cadastrado!</div>";
        }
    }
    
    //Criar as variaveis da foto quando a mesma não está sendo cadastrada
    if(empty ($_FILES['foto']['name'])){
        $campo_foto = "";
        $valor_foto = "";
    }
    
    //Houve erro em algum campo será redirecionado para o formulário, não há erro no formulário tenta cadastrar no banco
    if ($erro) {
        $_SESSION['dados'] = $dados;
        $url_destino = pg . "/cadastrar/cad_usuarios";
        header("Location: $url_destino");
    } else {


        //Criptografar a senha
        $dados_validos['senha'] = password_hash($dados_validos['senha'], PASSWORD_DEFAULT);
        $result_usuario = "INSERT INTO usuarios (nome, email, usuario, senha, $campo_foto niveis_acesso_id, situacoes_usuario_id, created) 
                VALUES(
                '" . $dados_validos['nome'] . "', 
                '" . $dados_validos['email'] . "', 
                '" . $dados_validos['usuario'] . "', 
                '" . $dados_validos['senha'] . "', 
                    $valor_foto
                '" . $dados_validos['niveis_acesso_id'] . "',
                '" . $dados_validos['situacoes_usuario_id'] . "',
                 NOW())";
        $resultado_usuario = mysqli_query($conn, $result_usuario);
        if (mysqli_insert_id($conn)) {
            unset($_SESSION['dados']);
            
            //Redimensionar a imagem e fazer upload
            if(!empty($_FILES['foto']['name'])){
                $destino = "assets/imagens/usuario/".mysqli_insert_id($conn)."/";
                upload($foto, $destino, 200, 200);
            }
            
            $_SESSION['msg'] = "<div class='alert alert-success'>Usuário cadastrado com sucesso</div>";
            $url_destino = pg . "/listar/list_usuarios";
            header("Location: $url_destino");
        } else {
            $_SESSION['msg'] = "<div class='alert alert-danger'>Erro ao cadastrar o usuário</div>";
            $url_destino = pg . "/cadastrar/cad_usuarios";
            header("Location: $url_destino");
        }
    }
} else {
    $_SESSION['msg'] = "<div class='alert alert-danger'>Erro ao carregar a página</div>";
    $url_destino = pg . "/listar/list_usuarios";
    header("Location: $url_destino");
}

