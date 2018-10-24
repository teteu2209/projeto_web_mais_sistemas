<?php
if (!isset($seguranca)) {
    exit;
}
$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
//verificar a existencia do id na URL
if (!empty($id)) {
    if ($_SESSION['niveis_acesso_id'] == 1) {
        $result_paginas = "SELECT * FROM paginas WHERE id='$id' LIMIT 1";
    } else {
        $result_paginas = "SELECT * FROM paginas WHERE id='$id' LIMIT 1";
    }

    $resultado_paginas = mysqli_query($conn, $result_paginas);

    //Verificar se encontrou o usuário no banco de dados
    if (($resultado_paginas) AND ( $resultado_paginas->num_rows != 0)) {
        $row_paginas = mysqli_fetch_assoc($resultado_paginas);
        ?>
        <div class = "well conteudo">
            <div class = "pull-right">
                <a href = "<?php echo pg . '/listar/list_pagina' ?>"><button type = "button" class = "btn btn-xs btn-roxo">Listar</button></a>
            </div>

            <div class = "page-header">
                <h1>Editar Página</h1>
            </div>
            <?php
            if (isset($_SESSION['msg'])) {
                echo $_SESSION['msg'];
                unset($_SESSION['msg']);
            }
            ?>

            <form action="<?php echo pg; ?>/processa/proc_edit_pagina" method="POST" class="form-horizontal" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo $row_paginas['id']; ?>">

                <div class="form-group">
                    <label class="col-sm-2 control-label">Endereço: </label>
                    <div class="col-sm-10">
                        <input type="text" name="endereco" class="form-control" placeholder="Primeiro o diretório em seguida o nome da página: lista/list_usuarios" value="<?php
                        if (isset($_SESSION['dados']['endereco'])) {
                            echo $_SESSION['dados']['endereco'];
                        } elseif (isset($row_paginas['endereco'])) {
                            echo $row_paginas['endereco'];
                        }
                        ?>">
                    </div>
                </div>  

                <div class="form-group">
                    <label class="col-sm-2 control-label">Nome: </label>
                    <div class="col-sm-10">
                        <input type="text" name="nome_pagina" class="form-control" placeholder="Nome para ser apresentado no menu" value="<?php
                        if (isset($_SESSION['dados']['nome_pagina'])) {
                            echo $_SESSION['dados']['nome_pagina'];
                        } elseif (isset($row_paginas['nome_pagina'])) {
                            echo $row_paginas['nome_pagina'];
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
                            }elseif (isset($row_paginas['obs'])) {
                            echo $row_paginas['obs'];
                        }
                            ?>
                        </textarea>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <input type="submit" class="btn btn-success" value="Editar" name="SendEditPagina">
                    </div>
                </div>  
            </form>
        </div>
        <?php
        unset($_SESSION['dados']);
    } else {
        $_SESSION['msg'] = "<div class='alert alert-danger'>Nenhuma página encontrada</div>";
        $url_destino = pg . "/listar/list_pagina";
        header("Location: $url_destino");
    }
} else {
    $_SESSION['msg'] = "<div class='alert alert-danger'>Nenhuma página encontrado</div>";
    $url_destino = pg . "/listar/list_pagina";
    header("Location: $url_destino");
}