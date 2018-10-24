<?php
if (!isset($seguranca)) {
    exit;
}
?>
<div class="well conteudo">
    <?php
    $botao_cad = carregar_botao('cadastrar/cad_usuarios', $conn);
    if ($botao_cad) {
        ?>
        <div class="pull-right">
            <a href="<?php echo pg . '/cadastrar/cad_usuarios' ?>"><button type="button" class="btn btn-xs btn-success">Cadastrar</button></a>
        </div><?php
}
    ?>
    <div class="page-header">
        <h1>Listar Usuários</h1>
    </div>
    <?php
    if (isset($_SESSION['msg'])) {
        echo $_SESSION['msg'];
        unset($_SESSION['msg']);
    }

    /* Verificar o botão */
    $botao_editar = carregar_botao('editar/edit_usuarios', $conn);
    $botao_ver = carregar_botao('visualizar/ver_usuarios', $conn);
    $botao_apagar = carregar_botao('processa/proc_apagar_usuarios', $conn);

    /* Selecionar no banco de dados os usuário */
    $pagina_atual = filter_input(INPUT_GET, 'pagina', FILTER_SANITIZE_NUMBER_INT);
    $pagina = (!empty($pagina_atual)) ? $pagina_atual : 1;

    //Setar a quantidade de itens por pagina
    $qnt_result_pg = 50;

    //calcular o inicio visualização
    $inicio = ($qnt_result_pg * $pagina) - $qnt_result_pg;

    if ($_SESSION['niveis_acesso_id'] == 1) {
        $result_usuario = "SELECT user.id, user.nome, user.email, user.niveis_acesso_id,
        niv.nome_nivel_acesso
            FROM usuarios user
            INNER JOIN niveis_acessos niv on niv.id=user.niveis_acesso_id 
            ORDER BY id DESC
            LIMIT $inicio, $qnt_result_pg";
    } else {
        $result_usuario = "SELECT user.id, user.nome, user.email, user.niveis_acesso_id,
        niv.nome_nivel_acesso
            FROM usuarios user
            INNER JOIN niveis_acessos niv on niv.id=user.niveis_acesso_id
            WHERE ordem > '".$_SESSION['ordem']."'
            ORDER BY id DESC
            LIMIT $inicio, $qnt_result_pg";
    }

    $resultado_usuario = mysqli_query($conn, $result_usuario);
    ?>
    <div class="row">
        <div class="col-md-12">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>E-mail</th>
                        <th>Nivel de Acesso</th>
                        <th class="text-right">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($row_usuario = mysqli_fetch_array($resultado_usuario)) {
                        //echo $row_usuario['nome'] . "<br>";
                        ?>
                        <tr>
                            <td><?php echo $row_usuario['id']; ?></td>
                            <td><?php echo $row_usuario['nome']; ?></td>
                            <td><?php echo $row_usuario['email']; ?></td>
                            <td><?php echo $row_usuario['nome_nivel_acesso']; ?></td>
                            <td class="text-right">
                                <?php
                                if ($botao_ver) {
                                    echo "<a href='" . pg . "/visualizar/ver_usuarios?id=".$row_usuario['id']."'><button type='button' class='btn btn-xs btn-primary'>Visualizar</button></a> ";
                                }
                                if ($botao_editar) {
                                    echo "<a href='" . pg . "/editar/edit_usuarios?id=" . $row_usuario['id'] . "'><button type='button' class='btn btn-xs btn-warning'>Editar</button></a> ";
                                }
                                if ($botao_apagar) {
                                    echo "<a href='" . pg . "/processa/proc_apagar_usuarios?id=".$row_usuario['id']."' onclick=\"return confirm('Deseja mesmo apagar?');\"><button type='button' class='btn btn-xs btn-danger'>Apagar</button></a> ";
                                }
                                ?>                                
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
            <?php
            //Paginção - Somar a quantidade de usuários
            if ($_SESSION['niveis_acesso_id'] == 1) {
                $result_pg = "SELECT COUNT(id) AS num_result FROM usuarios";
            }else{
                $result_pg = "SELECT COUNT(user.id) AS num_result
                    FROM usuarios user
                    INNER JOIN niveis_acessos niv on niv.id=user.niveis_acesso_id
                    WHERE ordem > '".$_SESSION['ordem']."'";
            }
            
            $resultado_pg = mysqli_query($conn, $result_pg);
            $row_pg = mysqli_fetch_assoc($resultado_pg);
            //echo $row_pg['num_result'];
            //Quantidade de pagina 
            $quantidade_pg = ceil($row_pg['num_result'] / $qnt_result_pg);

            //Limitar os link antes depois
            $MaxLinks = 2;
            echo "<nav class='text-center'>";
            echo "<ul class='pagination'>";
            echo "<li><a href='" . pg . "/listar/list_usuarios?pagina=1'  aria-label='Previous'><span aria-hidden='true'>Primeira</span> </a></li>";

            for ($iPag = $pagina - $MaxLinks; $iPag <= $pagina - 1; $iPag++) {
                if ($iPag >= 1) {
                    echo "<li><a href='" . pg . "/listar/list_usuarios?pagina=$iPag'>$iPag </a></li>";
                }
            }

            echo "<li class='active'><a href='#'> $pagina <span class='sr-only'></span></a></li>";

            for ($dPag = $pagina + 1; $dPag <= $pagina + $MaxLinks; $dPag++) {
                if ($dPag <= $quantidade_pg) {
                    echo "<li><a href='" . pg . "/listar/list_usuarios?pagina=$dPag'>$dPag </a></li>";
                }
            }

            echo "<li><a href='" . pg . "/listar/list_usuarios?pagina=$quantidade_pg'  aria-label='Previous'><span aria-hidden='true'>Última</span> </a></li>";
            ?>
        </div>
    </div>

</div>