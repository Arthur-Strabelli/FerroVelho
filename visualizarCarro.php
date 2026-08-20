<?php
require_once __DIR__ . '/config/sessao.php';
exigirLogin();
require_once __DIR__ . '/include/CarroBD.php';
require_once __DIR__ . '/include/funcoes.php';

$carroBD = new CarroBD();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'comentar') {
    $idVeiculo = (int) (isset($_POST['id_veiculo']) ? $_POST['id_veiculo'] : 0);
    $autor = trim(isset($_POST['autor']) ? $_POST['autor'] : '');
    $comentario = trim(isset($_POST['comentario']) ? $_POST['comentario'] : '');

    if ($idVeiculo > 0 && $autor !== '' && $comentario !== '') {
        $carroBD->adicionarComentario($idVeiculo, $autor, $comentario);
        header('Location: visualizarCarro.php?msg=comentario_adicionado#veiculo-' . $idVeiculo);
        exit;
    }

    header('Location: visualizarCarro.php?msg=comentario_erro#veiculo-' . $idVeiculo);
    exit;
}

$pesquisa = trim(isset($_GET['pesquisa']) ? $_GET['pesquisa'] : '');
$veiculos = $carroBD->listarTodos($pesquisa);

$tituloPagina = 'Veículos - Ferro-Velho AG';
require_once __DIR__ . '/include/header.php';
require_once __DIR__ . '/include/navbar.php';
?>

<div class="container-fluid">

    <div class="row">
        <div class="col-xs-12">
            <h2>Veículos</h2>
            <?php require_once __DIR__ . '/include/exibirMensagem.php'; ?>
        </div>
    </div>

    <div class="row secao-pesquisa-lista">
        <div class="col-sm-6">
            <form method="GET" action="visualizarCarro.php" class="form-inline">
                <div class="input-group">
                    <input type="text" name="pesquisa" class="form-control"
                           placeholder="Pesquisar por modelo, marca ou ano..."
                           value="<?= htmlspecialchars($pesquisa) ?>">
                    <span class="input-group-btn">
                        <button class="btn btn-fva-primary" type="submit"><i class="fa fa-search"></i></button>
                    </span>
                </div>
            </form>
        </div>
        <div class="col-sm-6 text-right">
            <a href="cadastrarCarro.php" class="btn btn-fva-primary">
                <i class="fa fa-plus"></i> Novo Veículo
            </a>
        </div>
    </div>

    <div class="row">
        <?php if (empty($veiculos)): ?>
            <div class="col-xs-12">
                <p class="text-muted">Nenhum veículo encontrado.</p>
            </div>
        <?php else: ?>
            <?php foreach ($veiculos as $veiculo): ?>
                <?php
                    $idVeiculo = (int) $veiculo['id_veiculo'];
                    $comentarios = $carroBD->listarComentarios($idVeiculo);
                    $descricaoResumida = resumirTexto(isset($veiculo['descricao']) ? $veiculo['descricao'] : '', 120);
                ?>
                <div class="col-sm-4 col-xs-12" id="veiculo-<?= $idVeiculo ?>">
                    <div class="card-veiculo">

                        <img src="<?= htmlspecialchars(imagemVeiculo($veiculo['imagem'])) ?>"
                             alt="<?= htmlspecialchars($veiculo['modelo']) ?>">

                        <div class="card-veiculo-corpo">
                            <span class="etiqueta-situacao etiqueta-<?= strtolower(str_replace(' ', '-', $veiculo['situacao'])) ?>">
                                <?= htmlspecialchars($veiculo['situacao']) ?>
                            </span>

                            <h4><?= htmlspecialchars($veiculo['modelo']) ?></h4>
                            <p class="marca-ano"><?= htmlspecialchars($veiculo['marca']) ?> &middot; <?= (int) $veiculo['ano'] ?></p>
                            <p class="preco-veiculo">R$ <?= number_format($veiculo['preco'], 2, ',', '.') ?></p>
                            <p class="avaliacao-veiculo">
                                <i class="fa fa-star"></i> <?= (int) $veiculo['avaliacao'] ?>/10
                            </p>
                            <p class="descricao-resumida"><?= htmlspecialchars($descricaoResumida) ?></p>

                            <button type="button" class="btn btn-link btn-ler-mais" data-id="<?= $idVeiculo ?>">
                                Ler mais +
                            </button>

                            <div class="acoes-card">
                                <a href="editarCarro.php?id=<?= $idVeiculo ?>" class="btn btn-fva-secundario btn-xs">
                                    <i class="fa fa-pencil"></i> Editar
                                </a>
                                <a href="excluirCarro.php?id=<?= $idVeiculo ?>" class="btn btn-danger btn-xs btn-excluir">
                                    <i class="fa fa-trash"></i> Excluir
                                </a>
                            </div>

                            <div id="detalhes-<?= $idVeiculo ?>" class="painel-detalhes" style="display:none;">

                                <h5>Descrição</h5>
                                <p><?= nl2br(htmlspecialchars($veiculo['descricao'] ?: 'Sem descrição.')) ?></p>

                                <h5>Detalhes técnicos</h5>
                                <ul class="lista-detalhes">
                                    <li><strong>Quilometragem:</strong> <?= number_format($veiculo['quilometragem'], 0, ',', '.') ?> km</li>
                                    <li><strong>Cor:</strong> <?= htmlspecialchars($veiculo['cor']) ?></li>
                                    <li><strong>Combustível:</strong> <?= htmlspecialchars($veiculo['combustivel']) ?></li>
                                </ul>

                                <h5>Histórico de comentários</h5>
                                <div class="lista-comentarios">
                                    <?php if (empty($comentarios)): ?>
                                        <p class="text-muted">Nenhum comentário ainda.</p>
                                    <?php else: ?>
                                        <?php foreach ($comentarios as $comentario): ?>
                                            <div class="comentario-item">
                                                <strong><?= htmlspecialchars($comentario['autor']) ?></strong>
                                                <span class="data-comentario">
                                                    <?= date('d/m/Y', strtotime($comentario['data_comentario'])) ?>
                                                    <?= date('H:i', strtotime($comentario['hora_comentario'])) ?>
                                                </span>
                                                <p><?= nl2br(htmlspecialchars($comentario['comentario'])) ?></p>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>

                                <h5>Novo comentário</h5>
                                <form method="POST" action="visualizarCarro.php#veiculo-<?= $idVeiculo ?>" class="form-comentario">
                                    <input type="hidden" name="acao" value="comentar">
                                    <input type="hidden" name="id_veiculo" value="<?= $idVeiculo ?>">
                                    <div class="form-group">
                                        <input type="text" name="autor" class="form-control input-sm"
                                               placeholder="Seu nome" required>
                                    </div>
                                    <div class="form-group">
                                        <textarea name="comentario" class="form-control input-sm" rows="2"
                                                  placeholder="Escreva um comentário..." required></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-fva-primary btn-sm">Comentar</button>
                                </form>

                            </div>

                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

</div>

<?php require_once __DIR__ . '/include/footer.php'; ?>
