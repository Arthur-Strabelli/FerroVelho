<?php
/**
 * config/sessao.php
 * Inicializa a sessão e fornece funções de apoio para autenticação.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function usuarioLogado(): bool
{
    return isset($_SESSION['cpf']);
}

function exigirLogin(): void
{
    if (!usuarioLogado()) {
        header('Location: login.php');
        exit;
    }
}
