<?php
if (!isset($seguranca)) {
    exit;
}
?>
<div class="well conteudo">
    <div class="pull-right">
        <a href="<?php echo pg . '/listar/list_pagina' ?>"><button type="button" class="btn btn-xs btn-roxo">Listar</button></a>
    </div>

    <div class="page-header">
        <h1>Cadastrar Página</h1>
    </div>
    <?php
    if (isset($_SESSION['msg'])) {
        echo $_SESSION['msg'];
        unset($_SESSION['msg']);
    }
    ?>

    <form action="<?php echo pg; ?>/processa/proc_cad_pagina" method="POST" class="form-horizontal" enctype="multipart/form-data">

        <div class="form-group">
            <label class="col-sm-2 control-label">Endereço: </label>
            <div class="col-sm-10">
                <input type="text" name="endereco" class="form-control" placeholder="Primeiro o diretório em seguida o nome da página: lista/list_usuarios" value="<?php
                if (isset($_SESSION['dados']['endereco'])) {
                    echo $_SESSION['dados']['endereco'];
                }
                ?>">
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label">Nome da Página: </label>
            <div class="col-sm-10">
                <input type="text" name="nome_pagina" class="form-control" placeholder="Nome para ser apresentado no menu" value="<?php
                if (isset($_SESSION['dados']['nome_pagina'])) {
                    echo $_SESSION['dados']['nome_pagina'];
                }
                ?>">
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label">Observação: </label>
            <div class="col-sm-10">
                <textarea name="obs" class="form-control"><?php
                    if (isset($_SESSION['dados']['obs'])) {
                        echo $_SESSION['dados']['obs'];
                    }
                    ?>
                </textarea>
            </div>
        </div>


        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <input type="submit" class="btn btn-success" value="Cadastrar" name="SendCadPagina">
            </div>
        </div>  
    </form>
</div>
<?php
unset($_SESSION['dados']);
