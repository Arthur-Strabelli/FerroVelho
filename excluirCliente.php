<?php
require_once __DIR__ . '/config/sessao.php';
exigirLogin();
require_once __DIR__ . '/include/ClienteBD.php';

$idCliente = (int) (isset($_GET['id']) ? $_GET['id'] : 0);

if ($idCliente > 0) {
    $clienteBD = new ClienteBD();

    try {
        $clienteBD->excluir($idCliente);
        header('Location: visualizarCliente.php?msg=cliente_excluido');
        exit;
    } catch (PDOException $e) {
        header('Location: visualizarCliente.php?msg=cliente_erro');
        exit;
    }
}

header('Location: visualizarCliente.php');
exit;
