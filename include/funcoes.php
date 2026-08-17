<?php

function converterPreco(string $valor): float
{
    $valor = preg_replace('/[^\d,.]/', '', $valor);

    if (strpos($valor, ',') !== false) {
        $valor = str_replace('.', '', $valor);
        $valor = str_replace(',', '.', $valor);
    }

    return (float) $valor;
}

function imagemVeiculo(?string $imagem): string
{
    if (!empty($imagem) && is_file(__DIR__ . '/../uploads/' . $imagem)) {
        return 'uploads/' . $imagem;
    }

    return 'imagens/sem-imagem.png';
}
