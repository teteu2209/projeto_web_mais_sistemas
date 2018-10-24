<?php
if (!isset($seguranca)) {
    exit;
}
?>
<div class="well conteudo">
    <div class="pull-right">
        <a href="<?php echo pg . '/listar/list_niv_acessos' ?>"><button type="button" class="btn btn-xs btn-roxo">Listar</button></a>
    </div>

    <div class="page-header">
        <h1>Cadastrar Nível de Acesso</h1>
    </div>
    <?php
    if (isset($_SESSION['msg'])) {
        echo $_SESSION['msg'];
        unset($_SESSION['msg']);
    }
    ?>

    <form action="<?php echo pg; ?>/processa/proc_cad_niv_acessos" method="POST" class="form-horizontal" enctype="multipart/form-data">

        <div class="form-group">
            <label class="col-sm-2 control-label">Nome: </label>
            <div class="col-sm-10">
                <input type="text" name="nome_nivel_acesso" class="form-control" placeholder="Nome do Nível de Acesso" value="<?php if (isset($_SESSION['dados']['nome_nivel_acesso'])) {
        echo $_SESSION['dados']['nome_nivel_acesso'];
    } ?>">
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <input type="submit" class="btn btn-success" value="Cadastrar" name="SendCadNivAcesso">
            </div>
        </div>  
    </form>
</div>
<?php
unset($_SESSION['dados']);