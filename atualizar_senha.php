<?php
session_start();
ob_start();
$seguranca = true;

//Incluir a conexao BD
include_once("config/conexao.php");
include_once("config/config.php");

$btnAtualizarSenha = filter_input(INPUT_POST, 'btnAtualizarSenha', FILTER_SANITIZE_STRING);
$chave_rc = filter_input(INPUT_GET, 'chave', FILTER_SANITIZE_STRING);

//Verificar se vem dados do botao
if ($btnAtualizarSenha) {
    //Receber os dodos do formulário
    $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
    $_SESSION['dados'] = $dados;
    
    include_once("lib/lib_valida.php");
    $dados_validos = vazio($dados);
    if($dados_validos){
        //validar o tamanho da senha
        if((strlen($dados_validos['senha'])) < 6){
            $_SESSION['msg'] = "<div class='alert alert-danger'>A senha deve ter no mínimo 6 caracteres!</div>";
        }elseif (stristr($dados_validos['senha'], "'")) {
            $_SESSION['msg'] = "<div class='alert alert-danger'>Caracter ( ' ) utilizado na senha é inválido!</div>";
        }else{
            //Inserir a nova senha no banco de dados 
            //Criptografar a senha
            $dados_validos['senha'] = password_hash($dados_validos['senha'], PASSWORD_DEFAULT);
            
            $result_usuario = "UPDATE usuarios SET
                    senha='".$dados_validos['senha']."',
                    recuperar_senha=NULL,
                    modified=NOW()
                    WHERE id='".$dados_validos['id']."'";
            $resultado_usuario = mysqli_query($conn, $result_usuario);
            if(mysqli_affected_rows($conn)){
                unset($_SESSION['dados']);
                $_SESSION['msg_rec'] = "<div class='alert alert-success'>Senha alterar com sucesso!</div>";
                $url_destino = pg . "/login.php";
                header("Location: $url_destino");
            }else{
                $_SESSION['msg'] = "<div class='alert alert-danger'>Erro ao alterar a senha!</div>";
            }
        }
    }else{
        $_SESSION['msg'] = "<div class='alert alert-danger'>Necessário digitar a nova senha!</div>";
    }
    
} elseif ($chave_rc) {
    //Limpar a chave
    $chave_st = strip_tags($chave_rc);
    $chave = trim($chave_st);
    //Pesquisar o usuario
    $result_usuario = "SELECT id FROM usuarios WHERE recuperar_senha='$chave' LIMIT 1";
    $resultado_usuario = mysqli_query($conn, $result_usuario);
    if (($resultado_usuario) AND ( $resultado_usuario->num_rows != 0)) {
        $row_usuario = mysqli_fetch_assoc($resultado_usuario);
        //echo $row_usuario['id'];
    } else {
        $_SESSION['msg_rec'] = "<div class='alert alert-danger'>Link inválido - tente recuperar novamente a senha!</div>";
        $url_destino = pg . "/recuperar_senha.php";
        header("Location: $url_destino");
    }
} else {
    $_SESSION['msg_rec'] = "<div class='alert alert-danger'>Link inválido - tente recuperar novamente a senha!</div>";
    $url_destino = pg . "/recuperar_senha.php";
    header("Location: $url_destino");
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
                <h2 class="form-signin-heading text-center">Atualizar a Senha</h2>
                <?php
                if (isset($_SESSION['msg'])) {
                    echo $_SESSION['msg'];
                    unset($_SESSION['msg']);
                }
                ?>
                <input type="hidden" name="id" value="<?php 
                    if(isset($row_usuario['id'])){
                        echo $row_usuario['id'];
                    }elseif(isset($_SESSION['dados']['id'])){
                        echo $_SESSION['dados']['id'];
                    }
                ?>">
                
                <div class="form-group">
                    <!--<label for="exampleInputEmail1">E-mail:</label>-->
                    <input type="password" name="senha" placeholder="Digite a nova senha com 6 caracteres" class="form-control">
                </div>

                <input type="submit" name="btnAtualizarSenha" value="Atualizar" class="btn btn-verde btn-block">   
                <div class="row text-center" style="margin-top: 20px;">
                    Lembrou? <a href="login.php">Clique aqui </a>para logar.
                </div>
            </form>
        </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script src="assets/js/bootstrap.min.js"></script>
    </body>
</html>