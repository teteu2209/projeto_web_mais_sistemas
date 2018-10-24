<div class="header-admin">
    <div class="pull-right" style="margin-right: 20px; padding-top: 2px;">
        <?php
        $result_usuario = "SELECT id, nome, foto FROM usuarios WHERE id='".$_SESSION['id']."' LIMIT 1";
        $resultado_usuario = mysqli_query($conn, $result_usuario);
        $row_usuario = mysqli_fetch_assoc($resultado_usuario);   
        //Somente pegar o primeiro nome
        $nome = explode(" ", $row_usuario['nome']);
        $primeiro_nome = $nome[0];
        
        if(!empty($row_usuario['foto'])){
            echo "<img src='". pg ."/assets/imagens/usuario/".$_SESSION['id']."/".$row_usuario['foto']."' class='img-circle' height='45' width='45' alt='".$primeiro_nome ."'>";
        }
        ?>
        
        


        <!-- Single button -->
        <div class="btn-group">
            <button type="button" class="btn btn-verde-perfil"><?php echo $primeiro_nome; ?>
            </button>
            <button type="button" class="btn btn-verde-perfil dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu dropdown-menu-right">
                <li><a href="<?php echo pg; ?>/visualizar/perfil">Meu Perfil</a></li>
                <li><a href="<?php echo pg; ?>/editar/edit_perfil">Editar Perfil</a></li>
                <li><a href="<?php echo pg; ?>/sair.php">Sair</a></li>
            </ul>
        </div>
    </div>    
</div>