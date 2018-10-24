<?php
if (!isset($seguranca)) {
    exit;
}

$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
if (!empty($id)) {
    if ($_SESSION['niveis_acesso_id'] == 1) {
        $result_usuario = "SELECT user.*,
        niv.nome_nivel_acesso,
        sit.nome_situacao
            FROM usuarios user
            INNER JOIN niveis_acessos niv on niv.id=user.niveis_acesso_id 
            INNER JOIN situacoes_usuarios sit on sit.id=user.situacoes_usuario_id 
            WHERE user.id='$id'
            LIMIT 1";
    } else {
        $result_usuario = "SELECT user.*,
        niv.nome_nivel_acesso,
        sit.nome_situacao
            FROM usuarios user
            INNER JOIN niveis_acessos niv on niv.id=user.niveis_acesso_id 
            INNER JOIN situacoes_usuarios sit on sit.id=user.situacoes_usuario_id 
            WHERE niv.ordem > '".$_SESSION['ordem']."' AND user.id='$id'
            LIMIT 1";
    }
    $resultado_usuario = mysqli_query($conn, $result_usuario);
    //Verificar se encontrou algum usuarios
    if (($resultado_usuario) AND ( $resultado_usuario->num_rows != 0)) {
        $row_usuario = mysqli_fetch_assoc($resultado_usuario);
        ?>
        <div class="well conteudo">
            <div class="pull-right">
                <?php
                /* Verificar o botão */
                $botao_editar = carregar_botao('editar/edit_usuarios', $conn);
                $botao_listar = carregar_botao('listar/list_usuarios', $conn);
                $botao_apagar = carregar_botao('processa/proc_apagar_usuarios', $conn);
                if ($botao_listar) {
                    echo "<a href='" . pg . "/listar/list_usuarios'><button type='button' class='btn btn-xs btn-roxo'>Listar</button></a> ";
                }
                if ($botao_editar) {
                    echo "<a href='" . pg . "/editar/edit_usuarios?id=" . $row_usuario['id'] . "'><button type='button' class='btn btn-xs btn-warning'>Editar</button></a> ";
                }
                if ($botao_apagar) {
                    echo "<a href='" . pg . "/processa/proc_apagar_usuarios?id=" . $row_usuario['id'] . "' onclick=\"return confirm('Deseja mesmo apagar?');\"><button type='button' class='btn btn-xs btn-danger'>Apagar</button></a> ";
                }
                ?>
            </div>

            <div class="page-header">
                <h1>Detalhes do Usuários</h1>
            </div>
            <dl class="dl-horizontal">
                <dt>ID</dt>
                <dd><?php echo $row_usuario['id']; ?></dd>
                
                <dt>Nome</dt>
                <dd><?php echo $row_usuario['nome']; ?></dd>
                
                <dt>E-mail</dt>
                <dd><?php echo $row_usuario['email']; ?></dd>
                
                <dt>Usuário</dt>
                <dd><?php echo $row_usuario['usuario']; ?></dd>
                
                <dt>Link Rec. Senha</dt>
                <dd><?php echo $row_usuario['recuperar_senha']; ?></dd>
                
                <dt>Foto</dt>
                <dd><?php //echo $row_usuario['foto']; 
                if(!empty($row_usuario['foto'])){
                    echo "<img src='".pg."/assets/imagens/usuario/".$row_usuario['id']."/".$row_usuario['foto']."' width='200' height='200'>";
                }
                ?></dd>
                
                <dt>Nivel de Acesso</dt>
                <dd><?php echo $row_usuario['nome_nivel_acesso']; ?></dd>
                
                <dt>Situação</dt>
                <dd><?php echo $row_usuario['nome_situacao']; ?></dd>
                
                <dt>Inserido</dt>
                <dd><?php echo date('d/m/Y H:i:s', strtotime($row_usuario['created'])); ?></dd>
                
                <dt>Alterado</dt>
                <dd><?php 
                if(!empty($row_usuario['modified'])){
                    echo date('d/m/Y H:i:s', strtotime($row_usuario['modified']));
                }
                 ?></dd>
            </dl>
        </div>
        <?php
    } else {
        $_SESSION['msg'] = "<div class='alert alert-danger'>Usuário não encontrado!</div>";
        $url_destino = pg . "/listar/list_usuarios";
        header("Location: $url_destino");
    }
} else {
    $_SESSION['msg'] = "<div class='alert alert-danger'>Usuário não encontrado!</div>";
    $url_destino = pg . "/listar/list_usuarios";
    header("Location: $url_destino");
}

