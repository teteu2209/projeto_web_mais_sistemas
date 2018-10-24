<?php

if (!isset($seguranca)) {
    exit;
}

$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
if (!empty($id)) {
    //Verificar se há usuarios cadastro no nível de acesso
    $result_usuario = "SELECT id FROM usuarios WHERE niveis_acesso_id = '$id' LIMIT 5";
    $resultado_usuario = mysqli_query($conn, $result_usuario);
    if (($resultado_usuario) AND ( $resultado_usuario->num_rows != 0)) {
        $_SESSION['msg'] = "<div class='alert alert-danger'>O nível de acesso não pode ser apagado, há usuários cadastrados neste nível!</div>";
        $url_destino = pg . "/listar/list_niv_acessos";
        header("Location: $url_destino");
    } else {//Não há nenhum usuário cadastro nesse nível
        //Verificar se o nível de acesso do usuário permite excluir esse nível
        $apagar = true;
        if ($_SESSION['niveis_acesso_id'] != 1) {
            //OBS - O SELECT abaixo não é necessário altera a '".$_SESSION['ordem']."' pois é recuperado a ordem do nivel a ser apagado
            $result_niv_acesso = "SELECT id FROM niveis_acessos  
            WHERE ordem > (SELECT ordem FROM niveis_acessos WHERE id = '" . $_SESSION['niveis_acesso_id'] . "')
            LIMIT 1";
            $resultado_niv_acesso = mysqli_query($conn, $result_niv_acesso);
            var_dump($resultado_niv_acesso);
            //O nível de acesso do usuário permite exclui o nível de acesso
            if ($resultado_niv_acesso->num_rows == 0) {
                $apagar = false;
                $_SESSION['msg'] = "<div class='alert alert-danger'>Nível de Acesso não pode ser apagado!</div>";
                $url_destino = pg . "/listar/list_niv_acessos";
                header("Location: $url_destino");
            }
        }

        //Apagar o nível de acesso
        if ($apagar) {
            //Pesquisar no banco de dados se há nível com ordem acima do qual será pagado
            $result_niv_acesso = "SELECT id, ordem FROM niveis_acessos  
                    WHERE ordem > (SELECT ordem FROM niveis_acessos WHERE id='$id') ORDER BY ordem ASC";
            $resultado_niv_acesso = mysqli_query($conn, $result_niv_acesso);
            
            //Tentado apagar
            $result_niv_acesso_del = "DELETE FROM niveis_acessos WHERE id='$id'";
            $resultado_niv_acesso_del = mysqli_query($conn, $result_niv_acesso_del);
            if (mysqli_affected_rows($conn)) {
                //Alterar a sequencia da ordem para não deixar nenhum número da ordem vazio
                if(($resultado_niv_acesso) AND ($resultado_niv_acesso->num_rows != 0)){
                    while($row_niv_acesso = mysqli_fetch_array($resultado_niv_acesso)){
                        $row_niv_acesso['ordem'] = $row_niv_acesso['ordem'] - 1; 
                        $result_niv_or = "UPDATE niveis_acessos SET
                            ordem='" . $row_niv_acesso['ordem'] . "' WHERE id='" . $row_niv_acesso['id'] . "'";
                        $resultado_niv_or = mysqli_query($conn, $result_niv_or);
                    }
                }
                
                $_SESSION['msg'] = "<div class='alert alert-success'>Nível de acesso apagado com sucesso!</div>";
                $url_destino = pg . "/listar/list_niv_acessos";
                header("Location: $url_destino");
            } else {
                $_SESSION['msg'] = "<div class='alert alert-danger'>Erro: O nível de acesso não foi apagado!</div>";
                $url_destino = pg . "/listar/list_niv_acessos";
                header("Location: $url_destino");
            }
        }
    }
} else {
    $_SESSION['msg'] = "<div class='alert alert-danger'>Nível de Acesso não encontrado!</div>";
    $url_destino = pg . "/listar/list_niv_acessos";
    header("Location: $url_destino");
}

