<?php
if (!isset($seguranca)) {
    exit;
}
$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
//verificar a existencia do id na URL
if (!empty($id)) {
    if($_SESSION['niveis_acesso_id'] == 1){
        $result_niv_acesso = "SELECT * FROM niveis_acessos WHERE id='$id'";
    }else{
        $result_niv_acesso = "SELECT * FROM niveis_acessos
            WHERE ordem > '".$_SESSION['ordem']."' AND id='$id'
            LIMIT 1";
    }
    
    $resultado_niv_acesso = mysqli_query($conn, $result_niv_acesso);

    //Verificar se encontrou o usuário no banco de dados
    if (($resultado_niv_acesso) AND ( $resultado_niv_acesso->num_rows != 0)) {
        $row_niv_acesso = mysqli_fetch_assoc($resultado_niv_acesso);
        ?>
        <div class = "well conteudo">
            <div class = "pull-right">
                <a href = "<?php echo pg . '/listar/list_niv_acessos' ?>"><button type = "button" class = "btn btn-xs btn-roxo">Listar</button></a>
            </div>

            <div class = "page-header">
                <h1>Editar Nível de Acesso</h1>
            </div>
            <?php
            if (isset($_SESSION['msg'])) {
                echo $_SESSION['msg'];
                unset($_SESSION['msg']);
            }
            ?>

            <form action="<?php echo pg; ?>/processa/proc_edit_niv_acessos" method="POST" class="form-horizontal" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo $row_niv_acesso['id']; ?>">
                
                <div class="form-group">
                    <label class="col-sm-2 control-label">Nome: </label>
                    <div class="col-sm-10">
                        <input type="text" name="nome_nivel_acesso" class="form-control" placeholder="Nome do nível de acesso" value="<?php
                        if (isset($_SESSION['dados']['nome_nivel_acesso'])) {
                            echo $_SESSION['dados']['nome_nivel_acesso'];
                        }elseif(isset($row_niv_acesso['nome_nivel_acesso'])){
                            echo $row_niv_acesso['nome_nivel_acesso'];
                        }
                        ?>">
                    </div>
                </div>                

                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <input type="submit" class="btn btn-success" value="Editar" name="SendEditNivAcessos">
                    </div>
                </div>  
            </form>
        </div>
        <?php
        unset($_SESSION['dados']);
    } else {
        $_SESSION['msg'] = "<div class='alert alert-danger'>Nenhum nível de acesso encontrado</div>";
        $url_destino = pg . "/listar/list_niv_acessos";
        header("Location: $url_destino");
    }
} else {
    $_SESSION['msg'] = "<div class='alert alert-danger'>Nenhum nível de acesso encontrado</div>";
    $url_destino = pg . "/listar/list_niv_acessos";
    header("Location: $url_destino");
}