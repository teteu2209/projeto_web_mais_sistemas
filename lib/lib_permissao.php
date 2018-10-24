<?php
if(!isset($seguranca)){exit;}
function carregar_botao($endereco, $conn){
    $result_botao = "SELECT nivacpg.id
            FROM niveis_acessos_paginas nivacpg
            INNER JOIN paginas pg on pg.id=nivacpg.pagina_id
            WHERE pg.endereco='$endereco' AND nivacpg.permissao=1 AND nivacpg.niveis_acesso_id=".$_SESSION['niveis_acesso_id'];
    $resultado_botao = mysqli_query($conn, $result_botao);
    if (($resultado_botao) AND ( $resultado_botao->num_rows != 0)) {
        return true;
    }else{
        return false;
    }
}
