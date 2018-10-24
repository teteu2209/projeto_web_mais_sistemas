<nav class="navbar navbar-inverse visible-xs">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">Matheus</a>
        </div>
        <?php
        $result_menu = "SELECT nivpg.*,
                    pg.endereco, pg.nome_pagina
                    FROM niveis_acessos_paginas nivpg
                    INNER JOIN paginas pg on pg.id=nivpg.pagina_id
                    WHERE niveis_acesso_id=" . $_SESSION['niveis_acesso_id'] . " AND permissao=1 AND menu=1 ORDER BY ordem ASC";
        $resultado_menu = mysqli_query($conn, $result_menu);
        ?>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <?php
                while ($row_menu = mysqli_fetch_array($resultado_menu)) {
                    echo "<li><a href='" . pg . "/" . $row_menu['endereco'] . "'>" . $row_menu['nome_pagina'] . "</a></li>";
                }
                ?>
                <li><a href="<?php echo pg; ?>/sair.php">Sair</a></li>  
            </ul>
        </div><!--/.nav-collapse -->
    </div>
</nav>

<div class="container-fluid">
    <div class="row content">
        <div class="col-sm-2 sidenav hidden-xs">
            <h2 class="text-center" style="color: #fff;">Matheus</h2>
            <ul class="nav nav-pills nav-stacked">
                <?php
                $resultado_menu = mysqli_query($conn, $result_menu);
                while ($row_menu = mysqli_fetch_array($resultado_menu)) {
                    echo "<li><a href='" . pg . "/" . $row_menu['endereco'] . "'>" . $row_menu['nome_pagina'] . "</a></li>";
                }
                ?>
                <li><a href="<?php echo pg; ?>/sair.php">Sair</a></li>
            </ul>
        </div>
