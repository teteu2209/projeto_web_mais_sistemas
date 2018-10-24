<?php
if (!isset($seguranca)) {
    exit;
}
?>
<div class="well conteudo">
    <div class="pull-right">
        <?php
        $botao_listar = carregar_botao('listar/list_cat_prod', $conn);
        if($botao_listar){
            echo "<a href='".pg."/listar/list_cat_prod'<button type='button' class='btn btn-xs btn-roxo'>Listar</button></a> ";
        }
        ?>
    </div>
    <div class="page-header">
        <h1>Cadastrar Categoria de Produto</h1>
    </div>
    
    <?php
    if (isset($_SESSION['msg'])) {
        echo $_SESSION['msg'];
        unset($_SESSION['msg']);
    }
    ?>
    <form action="<?php echo pg; ?>/processa/proc_cad_cat_prod" method="POST" class="form-horizontal" enctype="multipart/form-data">
        
        <div class="form-group">
            <label class="col-sm-2 control-label">Nome: </label>
            <div class="col-sm-10">
                <input type="text" name="nome" class="form-control" placeholder="Nome da categoria de produto" value="<?php if (isset($_SESSION['dados']['nome'])) {
        echo $_SESSION['dados']['nome'];
    } ?>">
            </div>
        </div>
        
        <?php
        $result_sit = "SELECT * FROM situacaos";
        $resultado_sit = mysqli_query($conn, $result_sit);
        
        ?>
        <div class="form-group">
            <label class="col-sm-2 control-label">Situação da Categoria: </label>
            <div class="col-sm-10">
                <select class="form-control" name="situacao_id">
                    <option value="">Selecione</option>
                    <?php
                    while($row_sit = mysqli_fetch_array($resultado_sit)){
                        if (isset($_SESSION['dados']['situacao_id']) AND ($_SESSION['dados']['situacao_id'] == $row_sit['id'])){
                            echo "<option value='".$row_sit['id']."' selected>".$row_sit['nome_situacao']."</option>";
                        }else{
                            echo "<option value='".$row_sit['id']."'>".$row_sit['nome_situacao']."</option>";
                        }
                        
                    }
                    ?>
                </select>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <input type="submit" class="btn btn-success" value="Cadastrar" name="SendCadCatProd">
            </div>
        </div> 
        
    </form>
</div>
<?php
unset($_SESSION['dados']);