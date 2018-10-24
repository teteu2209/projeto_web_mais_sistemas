<?php
session_start();
unset($_SESSION['id'], $_SESSION['nome'], $_SESSION['email'], $_SESSION['niveis_acesso_id']);

$_SESSION['msg'] = "<div class='alert alert-success'>Deslogado com sucesso</div>";
header("Location: login.php");

