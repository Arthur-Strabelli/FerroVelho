<?php
require_once __DIR__ . '/config/sessao.php';
exigirLogin();
require_once __DIR__ . '/include/CarroBD.php';
require_once __DIR__ . '/include/ClienteBD.php';
require_once __DIR__ . '/include/upload.php';

$situacoes = ['Disponível', 'Reservado', 'Vendido', 'Em desmontagem', 'Sucata'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dados = [
        'id_cliente'    => (int) ($_POST['id_cliente'] ?? 0),
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
        header('Location: cadastrarCarro.php?msg=campos_obrigatorios');
        exit;
    }

    if (!empty($_FILES['imagem']['name'])) {
        $resultadoUpload = fazerUploadImagem($_FILES['imagem']);
        if ($resultadoUpload === 'ERRO_FORMATO') {
            header('Location: cadastrarCarro.php?msg=imagem_invalida');
            exit;
        }
        $dados['imagem'] = $resultadoUpload;
    }

    $carroBD = new CarroBD();

    try {
        $carroBD->cadastrar($dados);
        header('Location: visualizarCarro.php?msg=veiculo_cadastrado');
        exit;
    } catch (PDOException $e) {
        header('Location: cadastrarCarro.php?msg=veiculo_erro');
        exit;
    }
}

$clienteBD = new ClienteBD();
$clientes = $clienteBD->listarTodos();
$idClientePreSelecionado = (int) ($_GET['id_cliente'] ?? 0);

$tituloPagina = 'Cadastrar Veículo - Ferro-Velho AG';
require_once __DIR__ . '/include/header.php';
require_once __DIR__ . '/include/navbar.php';
?>

<div class="container-fluid pagina-formulario">
    <div class="row">
        <div class="col-sm-8 col-sm-offset-2">

            <h2>Cadastro do Veículo</h2>

            <?php require_once __DIR__ . '/include/exibirMensagem.php'; ?>

            <form method="POST" action="cadastrarCarro.php" enctype="multipart/form-data" class="form-fva">

                <div class="form-group">
                    <label for="id_cliente">Cliente vinculado</label>
                    <select class="form-control" id="id_cliente" name="id_cliente">
                        <option value="">Nenhum</option>
                        <?php foreach ($clientes as $cliente): ?>
                            <option value="<?= (int) $cliente['id_cliente'] ?>"
                                <?= $idClientePreSelecionado === (int) $cliente['id_cliente'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cliente['nome']) ?> - <?= htmlspecialchars($cliente['cpf']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="modelo">Modelo *</label>
                            <input type="text" class="form-control" id="modelo" name="modelo" required>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="marca">Marca *</label>
                            <input type="text" class="form-control" id="marca" name="marca" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="ano">Ano *</label>
                            <input type="number" class="form-control" id="ano" name="ano"
                                   min="1900" max="2100" required>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="preco">Preço (R$) *</label>
                            <input type="text" class="form-control" id="preco" name="preco" required>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="quilometragem">Quilometragem *</label>
                            <input type="number" class="form-control" id="quilometragem" name="quilometragem"
                                   min="0" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="cor">Cor *</label>
                            <input type="text" class="form-control" id="cor" name="cor" required>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="combustivel">Combustível *</label>
                            <select class="form-control" id="combustivel" name="combustivel" required>
                                <option value="Flex">Flex</option>
                                <option value="Gasolina">Gasolina</option>
                                <option value="Etanol">Etanol</option>
                                <option value="Diesel">Diesel</option>
                                <option value="Elétrico">Elétrico</option>
                                <option value="Híbrido">Híbrido</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="situacao">Situação *</label>
                            <select class="form-control" id="situacao" name="situacao" required>
                                <?php foreach ($situacoes as $situacao): ?>
                                    <option value="<?= htmlspecialchars($situacao) ?>"><?= htmlspecialchars($situacao) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="avaliacao">Avaliação (0 a 10) *</label>
                    <input type="number" class="form-control" id="avaliacao" name="avaliacao" min="0" max="10" required>
                </div>

                <div class="form-group">
                    <label for="descricao">Descrição</label>
                    <textarea class="form-control" id="descricao" name="descricao" rows="4"></textarea>
                </div>

                <div class="form-group">
                    <label for="imagem">Imagem (JPG, JPEG ou PNG)</label>
                    <input type="file" id="imagem" name="imagem" accept=".jpg,.jpeg,.png">
                    <img id="preview-imagem" src="" alt="Pré-visualização" class="preview-imagem" style="display:none;">
                </div>

                <button type="submit" class="btn btn-fva-primary">Cadastrar Veículo</button>
                <a href="visualizarCarro.php" class="btn btn-default">Cancelar</a>
            </form>

        </div>
    </div>
</div>

<?php require_once __DIR__ . '/include/footer.php'; ?>
