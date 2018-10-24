<?php

if (!isset($seguranca)) {
    exit;
}

function limparurl($conteudo) {
    $formato_a = '"!@#$%*()-+{[}];:,\\\'<>°ºª';
    $formato_b = '_____________________________';
    $conteudo_ct = strtr($conteudo, $formato_a, $formato_b);

    $conteudo_br = str_ireplace(" ", "", $conteudo_ct);

    $conteudo_st = strip_tags($conteudo_br);

    $conteudo_lp = trim($conteudo_st);
    //1' OR '1=1
    //1OR11
    return $conteudo_lp;
}

function limparSenha($conteudo) {
    $formato_a = '"#&*()-+={[}]/?;:,\\\'<>°ºª';
    $formato_b = '                          ';
    $conteudo_ct = strtr($conteudo, $formato_a, $formato_b);

    $conteudo_br = str_ireplace(" ", "", $conteudo_ct);

    $conteudo_st = strip_tags($conteudo_br);

    $conteudo_lp = trim($conteudo_st);
    //1' OR '1=1
    //1OR11
    return $conteudo_lp;
}

function vazio($dados) {
    $dados_st = array_map('strip_tags', $dados);
    $dados_tr = array_map('trim', $dados_st);
    if (in_array('', $dados_tr)) {
        return false;
    } else {
        return $dados_tr;
    }
}

function validarEmail($email) {
    $condicao = '/[a-z0-9_\.\-]+@[a-z0-9_\.\-]*[a-z0-9_\.\-]+\.[a-z]{2,4}$/';
    if (preg_match($condicao, $email)) {
        return true;
    } else {
        return false;
    }
}

//validar exetensão da imagem
function validarExtesao($foto){
    switch ($foto):
        case 'image/png';
        case 'image/x-png';
            return true;
            break;
        case 'image/jpeg';
        case 'image/pjpeg';
            return true;
            break; 
        default:
            return false;
    endswitch;
}

//Retirar caracter especial
function caracterEspecial($nome_imagem){
    //Substituir os caracteres especiais
    $original = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜüÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿRr"!@#$%&*()_-+={[}]/?;:,\\\'<>°ºª';
    $substituir = 'aaaaaaaceeeeiiiidnoooooouuuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr                                ';
    
    $nome_imagem_es = strtr(utf8_decode($nome_imagem), utf8_decode($original), $substituir);
     
    //Substituir o espaco em branco pelo traco
    $nome_imagem_br = str_replace(' ', '-', $nome_imagem_es);
    
    $nome_imagem_tr = str_replace(array('----','---','--'), '-', $nome_imagem_br);
    
    //Converter para minusculo
    $nome_imagem_mi = strtolower($nome_imagem_tr);
    
    return $nome_imagem_mi;
}

//Upload de foto
function upload($foto, $destino, $largura, $altura){
    mkdir($destino, 0755);
    switch ($foto['type']){
        case 'image/png';
        case 'image/x-png';
            $imagem_temporaria = imagecreatefrompng($foto['tmp_name']);
            
            $imagem_redimensionada = redimensionarImagem($imagem_temporaria, $largura, $altura);
            
            imagepng($imagem_redimensionada, $destino . $foto['name']);
            break;
        case 'image/jpeg';
        case 'image/pjpeg';
            $imagem_temporaria = imagecreatefromjpeg($foto['tmp_name']);
            
            $imagem_redimensionada = redimensionarImagem($imagem_temporaria, $largura, $altura);
            
            imagejpeg($imagem_redimensionada, $destino . $foto['name']);
            break; 
    }
}

//Redimensionar imagem
function redimensionarImagem($imagem_temporaria, $largura, $altura){
    $largura_original = imagesx($imagem_temporaria);    
    $altura_original = imagesy($imagem_temporaria);
    
    $nova_largura = $largura ? $largura : floor(($largura_original / $altura_original) * $altura);
    
    $nova_altura = $altura ? $altura : floor(($altura_original / $largura_original) * $largura);
    
    $imagem_redimensionada = imagecreatetruecolor($nova_largura, $nova_altura);
    
    imagecopyresampled($imagem_redimensionada, $imagem_temporaria, 0, 0, 0, 0, $nova_largura, $nova_altura, $largura_original, $altura_original);
    
    return $imagem_redimensionada;
    
}

//Apagar foto
function apagarFoto($foto){
    if(file_exists($foto)){
        unlink($foto);
    }
}
