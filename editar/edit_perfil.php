<?php
if (!isset($seguranca)) {
    exit;
}

$result_usuario = "SELECT * FROM usuarios WHERE id='" . $_SESSION['id'] . "' LIMIT 1";
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
            <h1>Editar Meu Perfil</h1>
        </div>
        <?php
        if (isset($_SESSION['msg'])) {
            echo $_SESSION['msg'];
            unset($_SESSION['msg']);
        }
        ?>

        <form action="<?php echo pg; ?>/processa/proc_edit_perfil" method="POST" class="form-horizontal" enctype="multipart/form-data">

            <div class="form-group">
                <label class="col-sm-2 control-label">Nome: </label>
                <div class="col-sm-10">
                    <input type="text" name="nome" class="form-control" placeholder="Nome completo" value="<?php
                    if (isset($_SESSION['dados']['nome'])) {
                        echo $_SESSION['dados']['nome'];
                    } elseif (isset($row_usuario['nome'])) {
                        echo $row_usuario['nome'];
                    }
                    ?>">
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">E-mail: </label>
                <div class="col-sm-10">
                    <input type="email" name="email" class="form-control" placeholder="E-mail" value="<?php
                    if (isset($_SESSION['dados']['email'])) {
                        echo $_SESSION['dados']['email'];
                    } elseif (isset($row_usuario['email'])) {
                        echo $row_usuario['email'];
                    }
                    ?>">
                </div>
            </div>        

            <div class="form-group">
                <label class="col-sm-2 control-label">Usuário: </label>
                <div class="col-sm-10">
                    <input type="text" name="usuario" class="form-control" placeholder="Usuário para logar nio sistema" value="<?php
                    if (isset($_SESSION['dados']['usuario'])) {
                        echo $_SESSION['dados']['usuario'];
                    } elseif (isset($row_usuario['usuario'])) {
                        echo $row_usuario['usuario'];
                    }
                    ?>">
                </div>
            </div>

            <input type="hidden" name="foto_antiga" value="<?php echo $row_usuario['foto']; ?>">

            <div class="form-group">
                <label class="col-sm-2 control-label">Foto: </label>
                <div class="col-sm-10">
                    <input type="file" name="foto">
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
    $url_destino = pg . "/listar/list_usuarios";
    header("Location: $url_destino");
}
