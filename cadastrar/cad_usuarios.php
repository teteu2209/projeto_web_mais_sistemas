<?php
if (!isset($seguranca)) {
    exit;
}
?>
<div class="well conteudo">
    <div class="pull-right">
        <a href="<?php echo pg . '/listar/list_usuarios' ?>"><button type="button" class="btn btn-xs btn-roxo">Listar</button></a>
    </div>

    <div class="page-header">
        <h1>Cadastrar Usuário</h1>
    </div>
    <?php
    if (isset($_SESSION['msg'])) {
        echo $_SESSION['msg'];
        unset($_SESSION['msg']);
    }
    ?>

    <form action="<?php echo pg; ?>/processa/proc_cad_usuarios" method="POST" class="form-horizontal" enctype="multipart/form-data">

        <div class="form-group">
            <label class="col-sm-2 control-label">Nome: </label>
            <div class="col-sm-10">
                <input type="text" name="nome" class="form-control" placeholder="Nome completo" value="<?php if (isset($_SESSION['dados']['nome'])) {
        echo $_SESSION['dados']['nome'];
    } ?>">
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label">E-mail: </label>
            <div class="col-sm-10">
                <input type="email" name="email" class="form-control" placeholder="E-mail" value="<?php if (isset($_SESSION['dados']['email'])) {
        echo $_SESSION['dados']['email'];
    } ?>">
            </div>
        </div>        

        <div class="form-group">
            <label class="col-sm-2 control-label">Usuário: </label>
            <div class="col-sm-10">
                <input type="text" name="usuario" class="form-control" placeholder="Usuário para logar nio sistema" value="<?php if (isset($_SESSION['dados']['usuario'])) {
        echo $_SESSION['dados']['usuario'];
    } ?>">
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label">Senha: </label>
            <div class="col-sm-10">
                <input type="password" name="senha" class="form-control" placeholder="A senha alfanúmerica com mínimo 6 caracteres" value="<?php if (isset($_SESSION['dados']['senha'])) {
        echo $_SESSION['dados']['senha'];
    } ?>">
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label">Foto: </label>
            <div class="col-sm-10">
                <input type="file" name="foto">
            </div>
        </div>

        
        <?php
        if($_SESSION['niveis_acesso_id'] == 1){
           $result_niv_acesso = "SELECT * FROM niveis_acessos"; 
        }else{
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
                    while($row_niv_acesso = mysqli_fetch_array($resultado_niv_acesso)){
                        if (isset($_SESSION['dados']['niveis_acesso_id']) AND ($_SESSION['dados']['niveis_acesso_id'] == $row_niv_acesso['id'])){
                            echo "<option value='".$row_niv_acesso['id']."' selected>".$row_niv_acesso['nome_nivel_acesso']."</option>";
                        }else{
                            echo "<option value='".$row_niv_acesso['id']."'>".$row_niv_acesso['nome_nivel_acesso']."</option>";
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
                    while($row_sit_usuario = mysqli_fetch_array($resultado_sit_usuario)){
                        if (isset($_SESSION['dados']['situacoes_usuario_id']) AND ($_SESSION['dados']['niveis_acesso_id'] == $row_sit_usuario['id'])){
                            echo "<option value='".$row_sit_usuario['id']."' selected>".$row_sit_usuario['nome_situacao']."</option>";
                        }else{
                            echo "<option value='".$row_sit_usuario['id']."'>".$row_sit_usuario['nome_situacao']."</option>";
                        }
                        
                    }
                    ?>
                </select>
            </div>
        </div>


        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <input type="submit" class="btn btn-success" value="Cadastrar" name="SendCadUsuario">
            </div>
        </div>  
    </form>
</div>
<?php
unset($_SESSION['dados']);