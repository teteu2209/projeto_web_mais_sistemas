<?php
session_start();
ob_start();
$btnCadUsuario = filter_input(INPUT_POST, 'btnCadUsuario', FILTER_SANITIZE_STRING);
if ($btnCadUsuario) {
    //echo "cadastrar usuario";
    $seguranca = true;
    include_once './config/conexao.php';
    include_once './config/config.php';
    $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
    //var_dump($dados);
    $_SESSION['dados'] = $dados;
    include_once './lib/lib_valida.php';
    $dados_validos = vazio($dados);
    if ($dados_validos) {
        $erro = false;
        //validar senha
        if ((strlen($dados_validos['senha'])) < 6) {
            $erro = true;
            $_SESSION['msg'] = "<div class='alert alert-danger'>A senha deve ter no mínimo 6 caracteres!</div>";
        } elseif (stristr($dados_validos['senha'], "'")) {
            $erro = true;
            $_SESSION['msg'] = "<div class='alert alert-danger'>Caracter ( ' ) utilizado na senha inválido!</div>";
        } else {
            //Proibir cadastro de usuário duplicado
            $result_usuario = "SELECT id FROM usuarios WHERE usuario='" . $dados_validos['email'] . "' LIMIT 1";
            $resultado_usuario = mysqli_query($conn, $result_usuario);
            if (($resultado_usuario) AND ( $resultado_usuario->num_rows != 0)) {
                $erro = true;
                $_SESSION['msg'] = "<div class='alert alert-danger'>Este e-mail já está cadastrado!</div>";
            }
            //Proibir cadastro de email duplicado
            $result_usuario_email = "SELECT id FROM usuarios WHERE email='" . $dados_validos['email'] . "' LIMIT 1";
            $resultado_usuario_email = mysqli_query($conn, $result_usuario_email);
            if (($resultado_usuario_email) AND ( $resultado_usuario_email->num_rows != 0)) {
                $erro = true;
                $_SESSION['msg'] = "<div class='alert alert-danger'>Este e-mail já está cadastrado!</div>";
            }
        }


        if (!$erro) {
            //echo "cadastrar";
            //Criptografar a senha
            $dados_validos['senha'] = password_hash($dados_validos['senha'], PASSWORD_DEFAULT);
            $result_usuario = "INSERT INTO usuarios (nome, email, usuario, senha, niveis_acesso_id, situacoes_usuario_id, created) 
                VALUES(
                '" . $dados_validos['nome'] . "', 
                '" . $dados_validos['email'] . "', 
                '" . $dados_validos['email'] . "', 
                '" . $dados_validos['senha'] . "',
                '4',
                '2',
                 NOW())";
            $resultado_usuario = mysqli_query($conn, $result_usuario);
            if (mysqli_insert_id($conn)) {
                unset($_SESSION['dados']);
                $_SESSION['msg_rec'] = "<div class='alert alert-success'>Usuário cadastrado com sucesso</div>";
                $url_destino = pg . "/login.php";
                header("Location: $url_destino");
            } else {
                $_SESSION['msg'] = "<div class='alert alert-danger'>Erro ao cadastrar o usuário</div>";
            }
        }
    } else {
        $_SESSION['msg'] = "<div class='alert alert-danger'>Necessário preencher todos os campos!</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="utf-8">
        <title>Matheus - Cadastrar</title>
        <link href="assets/css/bootstrap.css" rel="stylesheet">
        <link href="assets/css/signin.css" rel="stylesheet">        
        <link href="assets/css/personalizado_login.css" rel="stylesheet">
    </head>
    <body>
        <div class="container">
            <div  class="form-signin">
                <form method="POST" action="">
                    <h2 class="form-signin-heading text-center">Novo Cadastro</h2>
                    <?php
                    if (isset($_SESSION['msg'])) {
                        echo $_SESSION['msg'];
                        unset($_SESSION['msg']);
                    }
                    ?>
                    <div class="form-group">
                        <!--<label for="exampleInputEmail1">Nome:</label>-->
                        <input type="text" name="nome" placeholder="Digite o nome e o sobrenome" class="form-control" value="<?php
                    if (isset($_SESSION['dados']['nome'])) {
                        echo $_SESSION['dados']['nome'];
                    }
                    ?>">
                    </div>

                    <div class="form-group">
                        <!--<label for="exampleInputEmail1">Nome:</label>-->
                        <input type="text" name="email" placeholder="Digite o seu e-mail" class="form-control" value="<?php
                        if (isset($_SESSION['dados']['email'])) {
                            echo $_SESSION['dados']['email'];
                        }
                    ?>">
                    </div>

                    <div class="form-group">
                        <!--<label for="exampleInputEmail1">Senha:</label>-->
                        <input type="password" name="senha" placeholder="Digite a sua senha" class="form-control">
                    </div>

                    <input type="submit" name="btnCadUsuario" value="Cadastrar" class="btn btn-verde btn-block">                   
                </form>
                <div class="row text-center" style="margin-top: 20px;">
                    Lembrou? <a href="login.php">Clique aqui </a>para logar.
                </div>
            </div>
        </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script src="assets/js/bootstrap.min.js"></script>
    </body>
</html>