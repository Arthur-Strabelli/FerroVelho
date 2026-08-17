<?php header('Content-Type: application/javascript; charset=UTF-8'); ?>
/**
 * include/script.php
 * Scripts jQuery globais do sistema Ferro-Velho AG.
 */

$(document).ready(function () {

    // Fecha alertas automaticamente após 5 segundos
    setTimeout(function () {
        $('.alert').fadeOut('slow');
    }, 5000);

    // Confirmação antes de excluir cliente ou veículo
    $('.btn-excluir').on('click', function (e) {
        var confirmar = confirm('Tem certeza que deseja excluir este registro? Esta ação não pode ser desfeita.');
        if (!confirmar) {
            e.preventDefault();
        }
    });

    // Expande/recolhe a descrição, histórico de comentários e formulário de novo comentário
    $('.btn-ler-mais').on('click', function () {
        var idVeiculo = $(this).data('id');
        var painel = $('#detalhes-' + idVeiculo);
        painel.slideToggle();

        var textoAtual = $(this).text().trim();
        $(this).text(textoAtual.indexOf('Ler mais') !== -1 ? 'Ler menos -' : 'Ler mais +');
    });

    // Máscara simples de CPF
    $('#cpf').on('input', function () {
        var v = $(this).val().replace(/\D/g, '').slice(0, 11);
        v = v.replace(/(\d{3})(\d)/, '$1.$2');
        v = v.replace(/(\d{3})(\d)/, '$1.$2');
        v = v.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
        $(this).val(v);
    });

    // Máscara simples de telefone
    $('#telefone').on('input', function () {
        var v = $(this).val().replace(/\D/g, '').slice(0, 11);
        if (v.length > 10) {
            v = v.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
        } else {
            v = v.replace(/(\d{2})(\d{4})(\d{0,4})/, '($1) $2-$3');
        }
        $(this).val(v);
    });

    // Preview de imagem antes do upload
    $('#imagem').on('change', function () {
        var arquivo = this.files[0];
        if (arquivo) {
            var leitor = new FileReader();
            leitor.onload = function (e) {
                $('#preview-imagem').attr('src', e.target.result).show();
            };
            leitor.readAsDataURL(arquivo);
        }
    });

    // Filtro de pesquisa da página inicial e da listagem de veículos
    $('#formPesquisar').on('submit', function () {
        var termo = $('#pesquisar').val().trim();
        if (termo === '') {
            return false;
        }
    });
});
