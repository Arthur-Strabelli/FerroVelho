<?php

function converterPreco($valor)
{
    $valor = preg_replace('/[^\d,.]/', '', $valor);

    if (strpos($valor, ',') !== false) {
        $valor = str_replace('.', '', $valor);
        $valor = str_replace(',', '.', $valor);
    }

    return (float) $valor;
}

function resumirTexto($texto, $limite)
{
    if (function_exists('mb_strimwidth')) {
        return mb_strimwidth($texto, 0, $limite, '...');
    }

    if (strlen($texto) <= $limite) {
        return $texto;
    }

    return substr($texto, 0, $limite - 3) . '...';
}

function gerarSenha($senha)
{
    if (function_exists('password_hash')) {
        return password_hash($senha, PASSWORD_DEFAULT);
    }

    return crypt($senha, '$2y$10$' . substr(md5(uniqid('', true)), 0, 22));
}

function conferirSenha($senha, $hash)
{
    if ($hash === '' || $hash === null) {
        return false;
    }

    if (function_exists('password_verify')) {
        return password_verify($senha, $hash);
    }

    return crypt($senha, $hash) === $hash;
}

function imagemVeiculo($imagem)
{
    if (!empty($imagem) && is_file(__DIR__ . '/../uploads/' . $imagem)) {
        return 'uploads/' . $imagem;
    }

    return 'imagens/sem-imagem.png';
}
