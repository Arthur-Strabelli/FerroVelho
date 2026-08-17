<?php
/**
 * include/upload.php
 * Realiza o upload de imagens de veículos, restrito a JPG, JPEG e PNG.
 * Retorna o nome do arquivo salvo, ou null em caso de erro/ausência de arquivo.
 */

function fazerUploadImagem(array $arquivo): ?string
{
    if (empty($arquivo['name'])) {
        return null;
    }

    $extensoesPermitidas = ['jpg', 'jpeg', 'png'];
    $extensao = strtolower(pathinfo($arquivo['name'], PATHINFO_EXTENSION));

    if (!in_array($extensao, $extensoesPermitidas, true)) {
        return 'ERRO_FORMATO';
    }

    $tiposMimePermitidos = ['image/jpeg', 'image/png'];
    $tipoReal = mime_content_type($arquivo['tmp_name']);

    if (!in_array($tipoReal, $tiposMimePermitidos, true)) {
        return 'ERRO_FORMATO';
    }

    $nomeArquivo = uniqid('veiculo_', true) . '.' . $extensao;
    $caminhoDestino = __DIR__ . '/../uploads/' . $nomeArquivo;

    if (!move_uploaded_file($arquivo['tmp_name'], $caminhoDestino)) {
        return null;
    }

    return $nomeArquivo;
}
