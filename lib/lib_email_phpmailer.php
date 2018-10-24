<?php

if (!isset($seguranca)) {
    exit;
}

function email_phpmailer($assunto, $mensagem, $mensagem_texo, $nome_destino, $email_destino) {
    require 'vendor/phpmailer/phpmailer/PHPMailerAutoload.php';

    $mail = new PHPMailer;

//$mail->SMTPDebug = 3;                               // Enable verbose debug output

    //**********************  OBS ********************/
    
    //Necessário preencher o Host, Username, Password, setFrom para enviar e-mail
    
    //************************************************/
    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = 'mx1.hostinger.com.br';                    // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = 'contato@listacomprasbeta.ga';                 // SMTP username
    $mail->Password = '123456';                           // SMTP password
    $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 587;                                    // TCP port to connect to

    $mail->setFrom('contato@listacomprasbeta.ga', 'Matheus Costa');
    $mail->addAddress($email_destino, $nome_destino);     // Add a recipient
    //$mail->addAddress('ellen@example.com');               // Name is optional
    //$mail->addReplyTo('info@example.com', 'Information');
    //$mail->addCC('cc@example.com');
    //$mail->addBCC('bcc@example.com');

    //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
    $mail->isHTML(true);                                  // Set email format to HTML

    $mail->Subject = $assunto;
    $mail->Body = $mensagem;
    $mail->AltBody = $mensagem_texo;

    if (!$mail->send()) {
        //echo 'Message could not be sent.';
        //echo 'Mailer Error: ' . $mail->ErrorInfo;
        return false;
    } else {
        //echo 'Message has been sent';
        return true;
    }
}
