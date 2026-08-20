<?php
$paginaAtual = basename($_SERVER['PHP_SELF']);

function ativo($pagina, $atual)
{
    return $pagina === $atual ? 'active' : '';
}
?>
<nav class="navbar navbar-fixed-top navbar-fva">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navFerroVelho">
                <span class="sr-only">Menu</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index.php">
                <img src="imagens/FerroVelhoAG.png" alt="Logo">
                Ferro-Velho AG
            </a>
        </div>
        <div class="collapse navbar-collapse" id="navFerroVelho">
            <ul class="nav navbar-nav">
                <li class="<?= ativo('index.php', $paginaAtual) ?>"><a href="index.php">Início</a></li>
                <li class="<?= ativo('visualizarCliente.php', $paginaAtual) ?>"><a href="visualizarCliente.php">Clientes</a></li>
                <li class="<?= ativo('visualizarCarro.php', $paginaAtual) ?>"><a href="visualizarCarro.php">Veículos</a></li>
                <li class="<?= ativo('sobre.php', $paginaAtual) ?>"><a href="sobre.php">Sobre</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="login.php?acao=sair"><i class="fa fa-sign-out"></i> Sair</a></li>
            </ul>
        </div>
    </div>
</nav>
<div class="navbar-espacador"></div>
