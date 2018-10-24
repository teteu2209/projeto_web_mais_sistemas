<?php

if (!isset($seguranca)) {
    exit;
}
$SendCadPagina = filter_input(INPUT_POST, 'SendCadPagina', FILTER_SANITIZE_STRING);
if ($SendCadPagina) {
    $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

    //validar nenhum campo vazio
    $erro = false;
    $dados_validos = vazio($dados);
    if (!$dados_validos) {
        $erro = true;
        $_SESSION['msg'] = "<div class='alert alert-danger'>Necessário preencher todos os campos para cadastrar a página!</div>";
    }
    
    else {
        //Proibir cadastro de página duplicado
        $result_paginas = "SELECT id FROM paginas WHERE endereco='" . $dados_validos['endereco'] . "' LIMIT 1";
        $resultado_paginas = mysqli_query($conn, $result_paginas);
        if (($resultado_paginas) AND ( $resultado_paginas->num_rows != 0)) {
            $erro = true;
            $_SESSION['msg'] = "<div class='alert alert-danger'>Este endereço já está cadastrado!</div>";
        }
    }

    if ($erro) {
        $_SESSION['dados'] = $dados;
        $url_destino = pg . "/cadastrar/cad_pagina";
        header("Location: $url_destino");
    } else {
        $result_cad_pagina = "INSERT INTO paginas (endereco, nome_pagina, obs, created) VALUES (
                '" . $dados_validos['endereco'] . "',
                '" . $dados_validos['nome_pagina'] . "',
                '" . $dados_validos['obs'] . "',
                NOW())";
        $resultado_cad_pagina = mysqli_query($conn, $result_cad_pagina);
        if (mysqli_insert_id($conn)) {
            unset($_SESSION['dados']);
            
            //Inicio inserir na tabela niveis_acessos_paginas
            $pagina_id = mysqli_insert_id($conn);
            //Ler os niveis de acessos
            $result_niv_acesso = "SELECT * FROM niveis_acessos";
            $resultado_niv_acesso = mysqli_query($conn, $result_niv_acesso);
            while($row_niv_acesso = mysqli_fetch_array($resultado_niv_acesso)){
                //Determinar 1 na permissao caso seja superadministrador e para outros niveis 2: 1 = Liberado, 2 - Bloqueado
                if($row_niv_acesso['id'] == 1){
                    $permissao = 1;
                }else{
                    $permissao = 2;
                }
                
                //Pesquisar o maior número da ordem na tabela niveis_acessos_paginas para o nível em execução
                $result_maior_ordem = "SELECT ordem FROM niveis_acessos_paginas
                        WHERE niveis_acesso_id='".$row_niv_acesso['id']."' ORDER BY id DESC LIMIT 1";
                $resultado_maior_ordem = mysqli_query($conn, $result_maior_ordem);
                $row_maior_ordem = mysqli_fetch_assoc($resultado_maior_ordem);
                $ordem = $row_maior_ordem['ordem'] + 1;
                
                $result_cad_pagina = "INSERT INTO niveis_acessos_paginas (niveis_acesso_id, pagina_id, permissao, ordem, created) VALUES (
                    '" . $row_niv_acesso['id'] . "',
                    '$pagina_id',
                    '$permissao',
                    '$ordem',
                    NOW())";
                    $resultado_cad_pagina = mysqli_query($conn, $result_cad_pagina);
            }
            //Fim inserir na tabela niveis_acessos_paginas
            $_SESSION['msg'] = "<div class='alert alert-success'>Cadastrado com sucesso a página</div>";
            $url_destino = pg . "/listar/list_pagina";
            header("Location: $url_destino");
        } else {
            $_SESSION['msg'] = "<div class='alert alert-danger'>Erro ao cadastrar a página</div>";
            $url_destino = pg . "/cadastrar/cad_pagina";
            header("Location: $url_destino");
        }
    }
} else {
    $_SESSION['msg'] = "<div class='alert alert-danger'>Erro ao carregar a página</div>";
    $url_destino = pg . "/listar/list_pagina";
    header("Location: $url_destino");
}

