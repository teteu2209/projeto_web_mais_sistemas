<?php

session_start();

$btnLogin = filter_input(INPUT_POST, 'btnLogin', FILTER_SANITIZE_STRING);
if ($btnLogin) {
    $seguranca = true;
    include_once("config/conexao.php");
    include_once("lib/lib_valida.php");
    
    $usuario_rc = filter_input(INPUT_POST, 'usuario', FILTER_SANITIZE_STRING);
    $senha_rc = filter_input(INPUT_POST, 'senha', FILTER_SANITIZE_STRING);
    echo $senha_rc . "<br>";
    $usuario = limparSenha($usuario_rc);
    $senha = limparSenha($senha_rc);
    echo "$usuario - $senha <br>";
    if ((!empty($usuario)) AND ( !empty($senha))) {
        //echo password_hash($senha, PASSWORD_DEFAULT);
        $result_usuario = "SELECT id, nome, email, senha, niveis_acesso_id FROM usuarios WHERE usuario='$usuario' LIMIT 1";
        $resultado_usuario = mysqli_query($conn, $result_usuario);
        if ($resultado_usuario) {
            $row_usuario = mysqli_fetch_assoc($resultado_usuario);
            if (password_verify($senha, $row_usuario['senha'])) {
                //Pesquisar a ordem do nível de acesso
                $result_niv_acesso = "SELECT ordem FROM niveis_acessos WHERE id='".$row_usuario['niveis_acesso_id']."' LIMIT 1";
                $resultado_niv_acesso = mysqli_query($conn, $result_niv_acesso);
                $row_niv_acesso = mysqli_fetch_assoc($resultado_niv_acesso);
                
                $_SESSION['id'] = $row_usuario['id'];
                $_SESSION['nome'] = $row_usuario['nome'];
                $_SESSION['email'] = $row_usuario['email'];
                $_SESSION['niveis_acesso_id'] = $row_usuario['niveis_acesso_id'];
                $_SESSION['ordem'] = $row_niv_acesso['ordem'];
                header("Location: index.php");
            } else {
                $_SESSION['msg'] = "<div class='alert alert-danger'>Login ou senha incorreto</div>";
                header("Location: login.php");
            }
        }
    } else {
        $_SESSION['msg'] = "<div class='alert alert-danger'>Login ou senha incorreto</div>";
        header("Location: login.php");
    }
} else {
    $_SESSION['msg'] = "<div class='alert alert-danger'>Página não encontrada</div>";
    header("Location: login.php");
}
