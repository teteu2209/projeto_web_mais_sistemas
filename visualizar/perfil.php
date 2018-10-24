<?php
if (!isset($seguranca)) {
    exit;
}



$result_usuario = "SELECT * FROM usuarios WHERE id='".$_SESSION['id']."' LIMIT 1";

$resultado_usuario = mysqli_query($conn, $result_usuario);
//Verificar se encontrou algum usuarios
if (($resultado_usuario) AND ( $resultado_usuario->num_rows != 0)) {
    $row_usuario = mysqli_fetch_assoc($resultado_usuario);
    ?>
    <div class="well conteudo">
        <div class="pull-right">
            <?php
            /* Verificar o botão */
            $botao_edit_perfil = carregar_botao('editar/edit_perfil', $conn);
            $botao_edit_senha = carregar_botao('editar/edit_senha', $conn);
            
            if ($botao_edit_perfil) {
                echo "<a href='" . pg . "/editar/edit_perfil'><button type='button' class='btn btn-xs btn-warning'>Editar o Perfil</button></a> ";
            }
            if ($botao_edit_senha) {
                echo "<a href='" . pg . "/editar/edit_senha'><button type='button' class='btn btn-xs btn-danger'>Editar a senha</button></a> ";
            }
            ?>
        </div>

        <div class="page-header">
            <h1>Meu Perfil</h1>
        </div>
        <?php
        if(isset($_SESSION['msg'])){
            echo $_SESSION['msg'];
            unset($_SESSION['msg']);
        }
        ?>
        <dl class="dl-horizontal">

            <dt>Nome</dt>
            <dd><?php echo $row_usuario['nome']; ?></dd>

            <dt>E-mail</dt>
            <dd><?php echo $row_usuario['email']; ?></dd>

            <dt>Usuário</dt>
            <dd><?php echo $row_usuario['usuario']; ?></dd>

            <dt>Foto</dt>
            <dd><?php
                //echo $row_usuario['foto']; 
                if (!empty($row_usuario['foto'])) {
                    echo "<img src='" . pg . "/assets/imagens/usuario/" . $row_usuario['id'] . "/" . $row_usuario['foto'] . "' width='200' height='200'>";
                }
                ?>
            </dd>
        </dl>
    </div>
    <?php
} else {
    $_SESSION['msg'] = "<div class='alert alert-danger'>Usuário não encontrado!</div>";
    $url_destino = pg . "/visualizar/home";
    header("Location: $url_destino");
}


