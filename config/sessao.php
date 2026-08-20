<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function usuarioLogado()
{
    return isset($_SESSION['cpf']);
}

function exigirLogin()
{
    if (!usuarioLogado()) {
        header('Location: login.php?msg=acesso_negado');
        exit;
    }
}
