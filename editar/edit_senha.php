<?php
if (!isset($seguranca)) {
    exit;
}

$result_usuario = "SELECT senha FROM usuarios WHERE id='" . $_SESSION['id'] . "' LIMIT 1";
$resultado_usuario = mysqli_query($conn, $result_usuario);

//Verificar se encontrou o usuário no banco de dados
if (($resultado_usuario) AND ( $resultado_usuario->num_rows != 0)) {
    $row_usuario = mysqli_fetch_assoc($resultado_usuario);
    ?>
    <div class = "well conteudo">
        <div class = "pull-right">
            <?php
            $botao_ver_perfil = carregar_botao('visualizar/perfil', $conn);

            if ($botao_ver_perfil) {
                echo "<a href='" . pg . "/visualizar/perfil'><button type='button' class='btn btn-xs btn-primary'>Ver Perfil</button></a> ";
            }
            ?>
        </div>

        <div class = "page-header">
            <h1>Editar a Senha</h1>
        </div>
        <?php
        if (isset($_SESSION['msg'])) {
            echo $_SESSION['msg'];
            unset($_SESSION['msg']);
        }
        ?>

        <form action="<?php echo pg; ?>/processa/proc_edit_senha" method="POST" class="form-horizontal" enctype="multipart/form-data">

            <div class="form-group">
                <label class="col-sm-2 control-label">Senha: </label>
                <div class="col-sm-10">
                    <input type="password" name="senha" class="form-control" placeholder="A nova senha" value="<?php
                    if (isset($_SESSION['dados']['senha'])) {
                        echo $_SESSION['dados']['senha'];
                    }
                    ?>">
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <input type="submit" class="btn btn-success" value="Editar" name="SendEditUsuario">
                </div>
            </div>  
        </form>
    </div>
    <?php
    unset($_SESSION['dados']);
} else {
    $_SESSION['msg'] = "<div class='alert alert-danger'>Nenhum usuário encontrado</div>";
    $url_destino = pg . "/visualizar/perfil";
    header("Location: $url_destino");
}
