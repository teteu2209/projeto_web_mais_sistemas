<?php
session_start();
$btnRecuperarSenha = filter_input(INPUT_POST, 'btnRecuperarSenha', FILTER_SANITIZE_STRING);
//Verificar se vem dados do botão
if ($btnRecuperarSenha) {
    //Receber os dados co campor email e limpar
    $email_rc = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $email_st = strip_tags($email_rc);
    $email = trim($email_st);
    //Verificar se a variável email possui valor
    if ($email != "") {
        //Incluir o validar e-mail e validar o mesmo
        $seguranca = true;
        include_once("lib/lib_valida.php");
        if (validarEmail($email)) {
            //Incluir a conexao BD, pesquisar o usuário
            include_once("config/conexao.php");
            $result_usuario = "SELECT id, nome, email, usuario FROM usuarios WHERE email='$email' LIMIT 1";
            $resultado_usuario = mysqli_query($conn, $result_usuario);
            if (($resultado_usuario) AND ($resultado_usuario->num_rows != 0)) {
                //$_SESSION['msg'] = "<div class='alert alert-success'>Usuário encontrado com esse e-mail!</div>";
                $row_usuario = mysqli_fetch_assoc($resultado_usuario);
                //Criar a chave recuperar a senha e salvar no BD
                $recuperar_senha = md5($row_usuario['id'] . $row_usuario['email'] . date("Y-m-d H:i:s"));
                //echo $recuperar_senha;
                $result_up_usuario = "UPDATE usuarios SET
                        recuperar_senha='$recuperar_senha',
                        modified=NOW()
                        WHERE id='" . $row_usuario['id'] . "'";
                $resultado_up_usuario = mysqli_query($conn, $result_up_usuario);
                //Criar e enviar o e-mail para o usuário recuperar senha                
                $assunto = "Recuperar senha";

                $nome_destino = current(str_word_count($row_usuario['nome'], 2));
                include_once('config/config.php');
                $url = pg . "/atualizar_senha.php?chave=" . $recuperar_senha;

                $mensagem = "Olá " . $nome_destino . "<br><br>";
                $mensagem .= "Você solicitou uma alteração de senha em Matheus.<br>";
                $mensagem .= "Seguindo o link abaixo você poderá alterar sua senha.<br>";

                $mensagem .= "Para continuar o processo de recuperação de sua senha, clique no link abaixo ou cole o endereço abaixo no seu navegador.<br><br>";
                $mensagem .= $url . "<br><br>";
                $mensagem .= "Usuário: {$row_usuario['usuario']}<br><br>";
                $mensagem .= "Se você não solicitou essa alteração, nenhuma ação é necessária. Sua senha permanecerá a mesma até que você ative este código.<br><br>";
                $mensagem .= "Respeitosamente, teteu2209@hotmail.com<br>";
                $mensagem_texo = $mensagem;

                include_once("lib/lib_email_phpmailer.php");
                if (email_phpmailer($assunto, $mensagem, $mensagem_texo, $nome_destino, $row_usuario['email'])) {
                    $_SESSION['msg_rec'] = "<div class='alert alert-success'>Enviado no seu e-mail o link para recuperar a senha!</div>";
                    $url_destino = pg . "/login.php";
                    header("Location: $url_destino");
                } else {
                    $_SESSION['msg'] = "<div class='alert alert-danger'>Erro ao enviar o e-mail - Necessário inserir o servidor, o e-mail de origem ... na página 'lib/lib_email_phpmailer.php'!</div>";
                }
            } else {
                $_SESSION['msg'] = "<div class='alert alert-danger'>Nenhum usuário encontrado com esse e-mail!</div>";
            }
        } else {
            $_SESSION['msg'] = "<div class='alert alert-danger'>E-mail inválido!</div>";
        }
    } else {
        $_SESSION['msg'] = "<div class='alert alert-danger'>O campo e-mail deve ser preechido!</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <title>Matheus - Login</title>
    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <link href="assets/css/signin.css" rel="stylesheet">
    <link href="assets/css/personalizado_login.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <form method="POST" action="" class="form-signin">
        <h2 class="form-signin-heading text-center">Recuperação de Senha</h2>
        <?php
        if (isset($_SESSION['msg'])) {
            echo $_SESSION['msg'];
            unset($_SESSION['msg']);
        }
        if (isset($_SESSION['msg_rec'])) {
            echo $_SESSION['msg_rec'];
            unset($_SESSION['msg_rec']);
        }
        ?>

        <div class="form-group">
            <!--<label for="exampleInputEmail1">E-mail:</label>-->
            <input type="text" name="email" placeholder="Digite o seu e-mail" class="form-control">
        </div>

        <input type="submit" name="btnRecuperarSenha" value="Recuperar" class="btn btn-verde btn-block">
        <div class="row text-center" style="margin-top: 20px;">
            Lembrou? <a href="login.php">Clique aqui </a>para logar.
        </div>
    </form>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
</body>
</html>