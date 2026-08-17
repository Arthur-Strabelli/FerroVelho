<?php
require_once __DIR__ . '/config/sessao.php';
exigirLogin();
require_once __DIR__ . '/include/CarroBD.php';
require_once __DIR__ . '/include/funcoes.php';

$carroBD = new CarroBD();
$ultimosVeiculos = $carroBD->listarUltimos(6);

$tituloPagina = 'Início - Ferro-Velho AG';
require_once __DIR__ . '/include/header.php';
require_once __DIR__ . '/include/navbar.php';
?>

<div class="container-fluid pagina-inicial">

    <?php require_once __DIR__ . '/include/exibirMensagem.php'; ?>

    <div class="row secao-pesquisa">
        <div class="col-sm-8 col-sm-offset-2 text-center">
            <h2>Pesquisar veículo</h2>
            <form id="formPesquisar" method="GET" action="visualizarCarro.php" class="form-pesquisa">
                <div class="input-group input-group-lg">
                    <input type="text" id="pesquisar" name="pesquisa" class="form-control"
                           placeholder="Modelo, marca ou ano...">
                    <span class="input-group-btn">
                        <button class="btn btn-fva-primary" type="submit">
                            <i class="fa fa-search"></i> Pesquisar
                        </button>
                    </span>
                </div>
            </form>
        </div>
    </div>

    <hr>

    <div class="row secao-atalhos text-center">
        <div class="col-sm-3 col-xs-6">
            <a href="cadastrarCliente.php" class="atalho-fva">
                <i class="fa fa-user-plus"></i>
                <span>Cadastrar Cliente</span>
            </a>
        </div>
        <div class="col-sm-3 col-xs-6">
            <a href="cadastrarCarro.php" class="atalho-fva">
                <i class="fa fa-car"></i>
                <span>Cadastrar Veículo</span>
            </a>
        </div>
        <div class="col-sm-3 col-xs-6">
            <a href="visualizarCliente.php" class="atalho-fva">
                <i class="fa fa-users"></i>
                <span>Visualizar Clientes</span>
            </a>
        </div>
        <div class="col-sm-3 col-xs-6">
            <a href="visualizarCarro.php" class="atalho-fva">
                <i class="fa fa-th-large"></i>
                <span>Visualizar Veículos</span>
            </a>
        </div>
    </div>

    <hr>

    <div class="row">
        <div class="col-xs-12">
            <h2>Últimos veículos cadastrados</h2>
        </div>
    </div>

    <div class="row">
        <?php if (empty($ultimosVeiculos)): ?>
            <div class="col-xs-12">
                <p class="text-muted">Nenhum veículo cadastrado ainda.</p>
            </div>
        <?php else: ?>
            <?php foreach ($ultimosVeiculos as $veiculo): ?>
                <div class="col-sm-4 col-xs-12">
                    <div class="card-veiculo">
                        <img src="<?= htmlspecialchars(imagemVeiculo($veiculo['imagem'])) ?>"
                             alt="<?= htmlspecialchars($veiculo['modelo']) ?>">
                        <div class="card-veiculo-corpo">
                            <h4><?= htmlspecialchars($veiculo['marca'] . ' ' . $veiculo['modelo']) ?></h4>
                            <p><?= (int) $veiculo['ano'] ?> &middot; R$ <?= number_format($veiculo['preco'], 2, ',', '.') ?></p>
                            <a href="visualizarCarro.php#veiculo-<?= (int) $veiculo['id_veiculo'] ?>"
                               class="btn btn-fva-secundario btn-sm">Ver detalhes</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

</div>

<?php require_once __DIR__ . '/include/footer.php'; ?>
