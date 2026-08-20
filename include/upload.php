<?php

function fazerUploadImagem(array $arquivo)
{
    if (empty($arquivo['name'])) {
        return null;
    }

    $erro = isset($arquivo['error']) ? $arquivo['error'] : UPLOAD_ERR_OK;

    if ($erro === UPLOAD_ERR_INI_SIZE || $erro === UPLOAD_ERR_FORM_SIZE) {
        return 'ERRO_TAMANHO';
    }

    if ($erro !== UPLOAD_ERR_OK) {
        return null;
    }

    $extensoesPermitidas = ['jpg', 'jpeg', 'png'];
    $extensao = strtolower(pathinfo($arquivo['name'], PATHINFO_EXTENSION));

    if (!in_array($extensao, $extensoesPermitidas, true)) {
        return 'ERRO_FORMATO';
    }

    if (function_exists('getimagesize')) {
        $imagem = @getimagesize($arquivo['tmp_name']);
        $tiposPermitidos = [IMAGETYPE_JPEG, IMAGETYPE_PNG];

        if (!$imagem || !in_array($imagem[2], $tiposPermitidos, true)) {
            return 'ERRO_FORMATO';
        }
    }

    $pasta = __DIR__ . '/../uploads';

    if (!is_dir($pasta)) {
        mkdir($pasta, 0777, true);
    }

    $nomeArquivo = uniqid('veiculo_', true) . '.' . $extensao;
    $caminhoDestino = $pasta . '/' . $nomeArquivo;

    if (!move_uploaded_file($arquivo['tmp_name'], $caminhoDestino)) {
        return null;
    }

    return $nomeArquivo;
}
