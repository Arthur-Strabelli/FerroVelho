<?php
require_once __DIR__ . '/config/sessao.php';
exigirLogin();

$tituloPagina = 'Sobre - Ferro-Velho AG';
require_once __DIR__ . '/include/header.php';
require_once __DIR__ . '/include/navbar.php';
?>

<div class="container-fluid pagina-sobre">
    <div class="row">
        <div class="col-sm-8 col-sm-offset-2">
            <h2>Sobre o Sistema</h2>
            <p>
                O <strong>Ferro-Velho AG</strong> é um sistema de gerenciamento de ferro-velho com
                aparência inspirada em marketplaces, desenvolvido em PHP, Bootstrap 3 e MySQL.
            </p>
            <p>
                Permite o cadastro de clientes e veículos, upload de imagens, avaliações,
                histórico de comentários e gerenciamento completo dos anúncios.
            </p>
            <p>Desenvolvido por Gustavo Augusto Bianchi Gomes &mdash; 2026.</p>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/include/footer.php'; ?>
