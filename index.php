<?php
session_start();
ob_start();
//Seguranca do ADM
$seguranca = true;
include_once("config/seguranca.php");
seguranca();

//Biblioteca auxiliares
include_once("config/config.php");
include_once("config/conexao.php");
include_once("lib/lib_valida.php");
include_once("lib/lib_permissao.php");
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="utf-8">
        <title>Matheus - ADM</title>
        <link href="<?php echo pg; ?>/assets/css/bootstrap.css" rel="stylesheet">
        <link href="<?php echo pg; ?>/assets/css/personalizado.css" rel="stylesheet">
        <script src="<?php echo pg; ?>/lib/tinymce/tinymce.min.js"></script>
        <script>tinymce.init({
                selector: 'textarea#editable',
                theme: 'modern',
                plugins: [
                    'advlist autolink lists link image charmap print preview hr anchor pagebreak',
                    'searchreplace wordcount visualblocks visualchars code fullscreen',
                    'insertdatetime media nonbreaking save table contextmenu directionality',
                    'emoticons template paste textcolor colorpicker textpattern imagetools codesample toc help'
                ],
                toolbar1: 'undo redo | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
                toolbar2: 'print preview media | forecolor backcolor emoticons | codesample help',
                image_advtab: true,
                templates: [
                    {title: 'Test template 1', content: 'Test 1'},
                    {title: 'Test template 2', content: 'Test 2'}
                ],
                content_css: [
                    '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
                    '//www.tinymce.com/css/codepen.min.css'
                ]
            });</script>
    </head>
    <body>

        <?php
        include_once("include/header.php");
        include_once("include/menu.php");
        ?>
        <div class="col-sm-10">

            <?php
            $url = filter_input(INPUT_GET, 'url', FILTER_SANITIZE_STRING);
            $arquivo_or = (!empty($url)) ? $url : 'home';
            $arquivo = limparurl($arquivo_or);
            $niveis_acesso_id = $_SESSION['niveis_acesso_id'];

            $result_pagina = "SELECT pg.id, 
            nivpg.id id_nivpg, nivpg.permissao 
            FROM paginas pg
            INNER JOIN niveis_acessos_paginas nivpg on nivpg.pagina_id=pg.id
            WHERE pg.endereco='$arquivo' AND nivpg.pagina_id=pg.id AND nivpg.niveis_acesso_id='$niveis_acesso_id' AND nivpg.permissao=1 LIMIT 1";

            $resultado_pagina = mysqli_query($conn, $result_pagina);
            if (($resultado_pagina) AND ( $resultado_pagina->num_rows != 0)) {
                $row_pagina = mysqli_fetch_assoc($resultado_pagina);
                $file = $arquivo . '.php';
                if (file_exists($file)) {
                    include $file;
                } else {
                    include_once("visualizar/home.php");
                }
            } else {
                //$_SESSION['msg']= "<div class='alert alert-danger'>Seu nivel de acesso não permite acessar essa função!</div>";
                include_once("visualizar/home.php");
            }
            ?>
        </div>
    </div>
    <?php
    include_once("include/rodape.php");
    ?>
</body>
</html>

