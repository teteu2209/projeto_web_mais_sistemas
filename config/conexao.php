<?php
if(!isset($seguranca)){exit;}
$servidor = "localhost";
$usuario = "u831942291_prod";
$senha = "123456";
$dbname = "u831942291_prod";

//Criar conexao
$conn = mysqli_connect($servidor, $usuario, $senha, $dbname);
if(!$conn){
    die("Falha na conexao: " . mysqli_connect_error());
}else{
    //echo "Conexao realizada com sucesso";
}
