<?php
session_start();
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
            <div  class="form-signin">
                <form method="POST" action="valida.php">
                    <h2 class="form-signin-heading text-center">Área Restrita</h2>
                    <?php
                    if (isset($_SESSION['msg'])) {
                        echo $_SESSION['msg'];
                        unset($_SESSION['msg']);
                    }if (isset($_SESSION['msg_rec'])) {
                        echo $_SESSION['msg_rec'];
                        unset($_SESSION['msg_rec']);
                    }
                    ?>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Usuário:</label>
                        <input type="text" name="usuario" required placeholder="Digite o seu usuário" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="exampleInputEmail1">Senha:</label>
                        <input type="password" name="senha" required placeholder="Digite a sua senha" class="form-control">
                    </div>

                    <input type="submit" name="btnLogin" value="Acessar" class="btn btn-verde btn-block">                   
                </form>
                <div class="row text-center" style="margin-top: 20px;">
                    <a href="recuperar_senha.php">Esqueceu sua senha? </a>
                </div>
                <div class="well text-center" style="margin-top: 20px;">
                    <h6>Você ainda não possui uma conta?</h6>
                    <a href="cadastrar.php">Crie grátis! </a>
                </div>
                <div class="row text-center" style="margin-top: 20px;">
                    Administrador<br>
                    Usuário: teteu2209@hotmail.com <br>
                    Senha: 123456
                </div>
            </div>
        </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script src="assets/js/bootstrap.min.js"></script>
    </body>
</html>