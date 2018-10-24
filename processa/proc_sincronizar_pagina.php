<?php

if (!isset($seguranca)) {
    exit;
}
//Pesquisar os niveis de acesso
$result_niv_acesso = "SELECT id, nome_nivel_acesso FROM niveis_acessos";
$resultado_niv_acesso = mysqli_query($conn, $result_niv_acesso);
while ($row_niv_acesso = mysqli_fetch_array($resultado_niv_acesso)) {
    echo "Nivel de acesso: " . $row_niv_acesso['nome_nivel_acesso'] . "<br>";
    //pesquisar as páginas
    $result_paginas = "SELECT id, endereco FROM paginas";
    $resultado_paginas = mysqli_query($conn, $result_paginas);
    while ($row_paginas = mysqli_fetch_array($resultado_paginas)) {
        echo "Endereço: " . $row_paginas['endereco'] . "<br>";
        //Pesquisar se o nivel de acesso possui a inscrição na página na tabela niveis_acessos_paginas
        $result_niv_ac_pg = "SELECT id FROM niveis_acessos_paginas
            WHERE niveis_acesso_id='" . $row_niv_acesso['id'] . "' AND pagina_id='" . $row_paginas['id'] . "' ORDER BY id ASC LIMIT 1";
        $resultado_niv_ac_pg = mysqli_query($conn, $result_niv_ac_pg);

        //Verificar se não encontrou a página cadastrada para o nível de acesso em questão
        if ($resultado_niv_ac_pg->num_rows == 0) {
            echo "Necessário cadastrar<br>";
            //Determinar 1 na permissao caso seja superadministrador e para outros niveis 2: 1 = Liberado, 2 - Bloqueado
            if ($row_niv_acesso['id'] == 1) {
                $permissao = 1;
            } else {
                $permissao = 2;
            }

            //Pesquisar o maior número da ordem na tabela niveis_acessos_paginas para o nível em execução
            $result_maior_ordem = "SELECT ordem FROM niveis_acessos_paginas
                        WHERE niveis_acesso_id='" . $row_niv_acesso['id'] . "' ORDER BY id DESC LIMIT 1";
            $resultado_maior_ordem = mysqli_query($conn, $result_maior_ordem);
            $row_maior_ordem = mysqli_fetch_assoc($resultado_maior_ordem);
            $ordem = $row_maior_ordem['ordem'] + 1;

            $result_cad_pagina = "INSERT INTO niveis_acessos_paginas (niveis_acesso_id, pagina_id, permissao, ordem, created) VALUES (
                    '" . $row_niv_acesso['id'] . "',
                    '" . $row_paginas['id'] . "',
                    '$permissao',
                    '$ordem',
                    NOW())";
            $resultado_cad_pagina = mysqli_query($conn, $result_cad_pagina);
        }
    }
}
$_SESSION['msg'] = "<div class='alert alert-success'>Páginas sincronizadas com sucesso</div>";
$url_destino = pg . "/listar/list_pagina";
header("Location: $url_destino");





