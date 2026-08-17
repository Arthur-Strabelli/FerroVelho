<?php
require_once __DIR__ . '/config/sessao.php';
exigirLogin();
require_once __DIR__ . '/include/CarroBD.php';
require_once __DIR__ . '/include/upload.php';

$situacoes = ['Disponível', 'Reservado', 'Vendido', 'Em desmontagem', 'Sucata'];
$combustiveis = ['Flex', 'Gasolina', 'Etanol', 'Diesel', 'Elétrico', 'Híbrido'];

$carroBD = new CarroBD();
$idVeiculo = (int) ($_GET['id'] ?? $_POST['id_veiculo'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dados = [
        'modelo'        => trim($_POST['modelo'] ?? ''),
        'marca'         => trim($_POST['marca'] ?? ''),
        'ano'           => (int) ($_POST['ano'] ?? 0),
        'preco'         => str_replace(',', '.', preg_replace('/[^\d,.]/', '', $_POST['preco'] ?? '0')),
        'quilometragem' => (int) ($_POST['quilometragem'] ?? 0),
        'cor'           => trim($_POST['cor'] ?? ''),
        'combustivel'   => trim($_POST['combustivel'] ?? ''),
        'situacao'      => $_POST['situacao'] ?? 'Disponível',
        'descricao'     => trim($_POST['descricao'] ?? ''),
        'avaliacao'     => max(0, min(10, (int) ($_POST['avaliacao'] ?? 0))),
        'imagem'        => null,
    ];

    if ($dados['modelo'] === '' || $dados['marca'] === '' || $dados['ano'] === 0) {
        header('Location: editarCarro.php?id=' . $idVeiculo . '&msg=campos_obrigatorios');
        exit;
    }

    if (!empty($_FILES['imagem']['name'])) {
        $resultadoUpload = fazerUploadImagem($_FILES['imagem']);
        if ($resultadoUpload === 'ERRO_FORMATO') {
            header('Location: editarCarro.php?id=' . $idVeiculo . '&msg=imagem_invalida');
            exit;
        }
        $dados['imagem'] = $resultadoUpload;
    }

    try {
        $carroBD->editar($idVeiculo, $dados);
        header('Location: visualizarCarro.php?msg=veiculo_editado#veiculo-' . $idVeiculo);
        exit;
    } catch (PDOException $e) {
        header('Location: editarCarro.php?id=' . $idVeiculo . '&msg=veiculo_erro');
        exit;
    }
}

$veiculo = $carroBD->buscarPorId($idVeiculo);

if (!$veiculo) {
    header('Location: visualizarCarro.php?msg=veiculo_erro');
    exit;
}

$tituloPagina = 'Editar Veículo - Ferro-Velho AG';
require_once __DIR__ . '/include/header.php';
require_once __DIR__ . '/include/navbar.php';
?>

<div class="container-fluid pagina-formulario">
    <div class="row">
        <div class="col-sm-8 col-sm-offset-2">

            <h2>Editar Veículo</h2>

            <?php require_once __DIR__ . '/include/exibirMensagem.php'; ?>

            <form method="POST" action="editarCarro.php" enctype="multipart/form-data" class="form-fva">
                <input type="hidden" name="id_veiculo" value="<?= (int) $veiculo['id_veiculo'] ?>">

                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="modelo">Modelo *</label>
                            <input type="text" class="form-control" id="modelo" name="modelo"
                                   value="<?= htmlspecialchars($veiculo['modelo']) ?>" required>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="marca">Marca *</label>
                            <input type="text" class="form-control" id="marca" name="marca"
                                   value="<?= htmlspecialchars($veiculo['marca']) ?>" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="ano">Ano *</label>
                            <input type="number" class="form-control" id="ano" name="ano"
                                   value="<?= (int) $veiculo['ano'] ?>" min="1900" max="2100" required>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="preco">Preço (R$) *</label>
                            <input type="text" class="form-control" id="preco" name="preco"
                                   value="<?= number_format($veiculo['preco'], 2, ',', '.') ?>" required>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="quilometragem">Quilometragem *</label>
                            <input type="number" class="form-control" id="quilometragem" name="quilometragem"
                                   value="<?= (int) $veiculo['quilometragem'] ?>" min="0" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="cor">Cor *</label>
                            <input type="text" class="form-control" id="cor" name="cor"
                                   value="<?= htmlspecialchars($veiculo['cor']) ?>" required>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="combustivel">Combustível *</label>
                            <select class="form-control" id="combustivel" name="combustivel" required>
                                <?php foreach ($combustiveis as $comb): ?>
                                    <option value="<?= htmlspecialchars($comb) ?>"
                                        <?= $veiculo['combustivel'] === $comb ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($comb) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="situacao">Situação *</label>
                            <select class="form-control" id="situacao" name="situacao" required>
                                <?php foreach ($situacoes as $situacao): ?>
                                    <option value="<?= htmlspecialchars($situacao) ?>"
                                        <?= $veiculo['situacao'] === $situacao ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($situacao) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="avaliacao">Avaliação (0 a 10) *</label>
                    <input type="number" class="form-control" id="avaliacao" name="avaliacao"
                           value="<?= (int) $veiculo['avaliacao'] ?>" min="0" max="10" required>
                </div>

                <div class="form-group">
                    <label for="descricao">Descrição</label>
                    <textarea class="form-control" id="descricao" name="descricao" rows="4"><?= htmlspecialchars($veiculo['descricao']) ?></textarea>
                </div>

                <div class="form-group">
                    <label>Imagem atual</label><br>
                    <img src="uploads/<?= htmlspecialchars($veiculo['imagem'] ?: 'sem-imagem.png') ?>"
                         alt="Imagem atual" class="preview-imagem" onerror="this.src='imagens/sem-imagem.png'">
                </div>

                <div class="form-group">
                    <label for="imagem">Substituir imagem (opcional - JPG, JPEG ou PNG)</label>
                    <input type="file" id="imagem" name="imagem" accept=".jpg,.jpeg,.png">
                </div>

                <button type="submit" class="btn btn-fva-primary">Salvar Alterações</button>
                <a href="visualizarCarro.php" class="btn btn-default">Cancelar</a>
            </form>

        </div>
    </div>
</div>

<?php require_once __DIR__ . '/include/footer.php'; ?>
