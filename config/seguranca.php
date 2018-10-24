<?php
if(!isset($seguranca)){exit;}
function seguranca(){
    if((isset($_SESSION['email'])) AND (isset($_SESSION['niveis_acesso_id']))){
        //echo "permanecer logado";
    }else{
        include_once("config/config.php");
        $_SESSION['msg'] = "<div class='alert alert-danger'>Para acessar a página necessário realizar o login!</div>";
        $url_destino = pg."/login.php";
        header("Location: $url_destino");
    }
}
