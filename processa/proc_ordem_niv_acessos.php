<?php

if (!isset($seguranca)) {
    exit;
}

$ordem = filter_input(INPUT_GET, 'ordem', FILTER_SANITIZE_NUMBER_INT);
if (!empty($ordem)) {
    //echo "$ordem";
    //Verificar se a ordem é maior em relação a ordem do usuário logado
    if ($ordem > $_SESSION['ordem'] + 1) {
        //Pesquisar o ID do nível de acesso atual a ser movido para cima
        $result_niv_atual = "SELECT id, ordem FROM niveis_acessos
                WHERE ordem='$ordem' LIMIT 1";
        $resultado_niv_atual = mysqli_query($conn, $result_niv_atual);
        $row_niv_atual = mysqli_fetch_assoc($resultado_niv_atual);
        //var_dump($row_niv_atual);
        //Pesquisar o ID do nível de acesso a ser movido para baixo
        $ordem_super = $ordem - 1;
        $result_niv_super = "SELECT id, ordem FROM niveis_acessos WHERE ordem='$ordem_super' LIMIT 1";
        $resultado_niv_super = mysqli_query($conn, $result_niv_super);
        $row_niv_super = mysqli_fetch_assoc($resultado_niv_super);
        //var_dump($row_niv_super);

        //Alterar a ordem para o número ser maior
        $result_niv_mv_baixo = "UPDATE niveis_acessos SET
                ordem='$ordem',
                modified=NOW()
                WHERE id='" . $row_niv_super['id'] . "'";
        $resultado_niv_mv_baixo = mysqli_query($conn, $result_niv_mv_baixo);

        //Alterar a ordem para o número ser maior
        $result_niv_mv_super = "UPDATE niveis_acessos SET
                ordem='$ordem_super',
                modified=NOW()
                WHERE id='" . $row_niv_atual['id'] . "'";
        $resultado_niv_mv_super = mysqli_query($conn, $result_niv_mv_super);

        //Redirecionar conforme a situação do alterar: sucesso ou erro
        if (mysqli_affected_rows($conn)) {
            $_SESSION['msg'] = "<div class='alert alert-success'>Ordem do Nível de Acesso editado com sucesso!</div>";
            $url_destino = pg . "/listar/list_niv_acessos";
            header("Location: $url_destino");
        } else {
            $_SESSION['msg'] = "<div class='alert alert-danger'>Erro ao editar a ordem do Nível de Acesso!</div>";
            $url_destino = pg . "/listar/list_niv_acessos";
            header("Location: $url_destino");
        }
    } else {
        $_SESSION['msg'] = "<div class='alert alert-danger'>Nível de Acesso não encontrado!</div>";
        $url_destino = pg . "/listar/list_niv_acessos";
        header("Location: $url_destino");
    }
} else {
    $_SESSION['msg'] = "<div class='alert alert-danger'>Nível de Acesso não encontrado!</div>";
    $url_destino = pg . "/listar/list_niv_acessos";
    header("Location: $url_destino");
}

