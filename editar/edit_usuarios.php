<?php
if (!isset($seguranca)) {
    exit;
}
$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
//verificar a existencia do id na URL
if (!empty($id)) {
    if($_SESSION['niveis_acesso_id'] == 1){
        $result_usuario = "SELECT * FROM usuarios WHERE id='$id'";
    }else{
        $result_usuario = "SELECT user.*,
        niv.nome_nivel_acesso
            FROM usuarios user
            INNER JOIN niveis_acessos niv on niv.id=user.niveis_acesso_id 
            WHERE niv.ordem > '".$_SESSION['ordem']."' AND user.id='$id'
            LIMIT 1";
    }
    
    $resultado_usuario = mysqli_query($conn, $result_usuario);

    //Verificar se encontrou o usuário no banco de dados
    if (($resultado_usuario) AND ( $resultado_usuario->num_rows != 0)) {
        $row_usuario = mysqli_fetch_assoc($resultado_usuario);
        ?>
        <div class = "well conteudo">
            <div class = "pull-right">
                <a href = "<?php echo pg . '/listar/list_usuarios' ?>"><button type = "button" class = "btn btn-xs btn-roxo">Listar</button></a>
            </div>

            <div class = "page-header">
                <h1>Editar Usuário</h1>
            </div>
            <?php
            if (isset($_SESSION['msg'])) {
                echo $_SESSION['msg'];
                unset($_SESSION['msg']);
            }
            ?>

            <form action="<?php echo pg; ?>/processa/proc_edit_usuarios" method="POST" class="form-horizontal" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo $row_usuario['id']; ?>">
                
                <div class="form-group">
                    <label class="col-sm-2 control-label">Nome: </label>
                    <div class="col-sm-10">
                        <input type="text" name="nome" class="form-control" placeholder="Nome completo" value="<?php
                        if (isset($_SESSION['dados']['nome'])) {
                            echo $_SESSION['dados']['nome'];
                        }elseif(isset($row_usuario['nome'])){
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
                        }elseif(isset($row_usuario['email'])){
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
                        }elseif(isset($row_usuario['usuario'])){
                            echo $row_usuario['usuario'];
                        }
                        ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">Senha: </label>
                    <div class="col-sm-10">
                        <input type="password" name="senha" class="form-control" placeholder="A senha alfanúmerica com mínimo 6 caracteres" value="<?php
                        if (isset($_SESSION['dados']['senha'])) {
                            echo $_SESSION['dados']['senha'];
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


                <?php
                if ($_SESSION['niveis_acesso_id'] == 1) {
                    $result_niv_acesso = "SELECT * FROM niveis_acessos";
                } else {
                    $result_niv_acesso = "SELECT * FROM niveis_acessos WHERE ordem > '".$_SESSION['ordem']."'";
                }

                $resultado_niv_acesso = mysqli_query($conn, $result_niv_acesso);
                ?>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Nivel de Acesso: </label>
                    <div class="col-sm-10">
                        <select class="form-control" name="niveis_acesso_id">
                            <option value="">Selecione</option>
                            <?php
                            while ($row_niv_acesso = mysqli_fetch_array($resultado_niv_acesso)) {
                                if (isset($_SESSION['dados']['niveis_acesso_id']) AND ( $_SESSION['dados']['niveis_acesso_id'] == $row_niv_acesso['id'])) {
                                    echo "<option value='" . $row_niv_acesso['id'] . "' selected>" . $row_niv_acesso['nome_nivel_acesso'] . "</option>";
                                } 
                                //Preencher com informações do banco de dados caso não tenha nenhum valor salvo na sessão $_SESSION['dados']
                                elseif (!isset($_SESSION['dados']['niveis_acesso_id']) AND isset ($row_usuario['niveis_acesso_id']) AND ( $row_usuario['niveis_acesso_id'] == $row_niv_acesso['id'])) {
                                    echo "<option value='" . $row_niv_acesso['id'] . "' selected>" . $row_niv_acesso['nome_nivel_acesso'] . "</option>";
                                } else {
                                    echo "<option value='" . $row_niv_acesso['id'] . "'>" . $row_niv_acesso['nome_nivel_acesso'] . "</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <?php
                $result_sit_usuario = "SELECT * FROM situacoes_usuarios";
                $resultado_sit_usuario = mysqli_query($conn, $result_sit_usuario);
                ?>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Situação do Usuário: </label>
                    <div class="col-sm-10">
                        <select class="form-control" name="situacoes_usuario_id">
                            <option value="">Selecione</option>
                            <?php
                            while ($row_sit_usuario = mysqli_fetch_array($resultado_sit_usuario)) {
                                //Preencher com as informações que estão salva nasessão
                                if (isset($_SESSION['dados']['situacoes_usuario_id']) AND ( $_SESSION['dados']['niveis_acesso_id'] == $row_sit_usuario['id'])) {
                                    echo "<option value='" . $row_sit_usuario['id'] . "' selected>" . $row_sit_usuario['nome_situacao'] . "</option>";
                                }
                                
                                //Preencher com informações do banco de dados caso não tenha nenhum valor salvo na sessão $_SESSION['dados']
                                elseif (!isset($_SESSION['dados']['situacoes_usuario_id']) AND isset ($row_usuario['situacoes_usuario_id']) AND ( $row_usuario['situacoes_usuario_id'] == $row_sit_usuario['id'])) {
                                    echo "<option value='" . $row_sit_usuario['id'] . "' selected>" . $row_sit_usuario['nome_situacao'] . "</option>";
                                }
                                
                                else {
                                    echo "<option value='" . $row_sit_usuario['id'] . "'>" . $row_sit_usuario['nome_situacao'] . "</option>";
                                }
                            }
                            ?>
                        </select>
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
} else {
    $_SESSION['msg'] = "<div class='alert alert-danger'>Nenhum usuário encontrado</div>";
    $url_destino = pg . "/listar/list_usuarios";
    header("Location: $url_destino");
}