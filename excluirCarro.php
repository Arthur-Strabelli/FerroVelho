<?php
require_once __DIR__ . '/config/sessao.php';
exigirLogin();
require_once __DIR__ . '/include/CarroBD.php';

$idVeiculo = (int) ($_GET['id'] ?? 0);

if ($idVeiculo > 0) {
    $carroBD = new CarroBD();

    try {
        $carroBD->excluir($idVeiculo);
        header('Location: visualizarCarro.php?msg=veiculo_excluido');
        exit;
    } catch (PDOException $e) {
        header('Location: visualizarCarro.php?msg=veiculo_erro');
        exit;
    }
}

header('Location: visualizarCarro.php');
exit;
