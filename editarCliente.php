<?php
require_once __DIR__ . '/config/sessao.php';
exigirLogin();
require_once __DIR__ . '/include/ClienteBD.php';

$clienteBD = new ClienteBD();
$idCliente = (int) (isset($_GET['id']) ? $_GET['id'] : (isset($_POST['id_cliente']) ? $_POST['id_cliente'] : 0));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cpf      = trim(isset($_POST['cpf']) ? $_POST['cpf'] : '');
    $nome     = trim(isset($_POST['nome']) ? $_POST['nome'] : '');
    $telefone = trim(isset($_POST['telefone']) ? $_POST['telefone'] : '');
    $cidade   = trim(isset($_POST['cidade']) ? $_POST['cidade'] : '');

    if ($cpf === '' || $nome === '' || $telefone === '' || $cidade === '') {
        header('Location: editarCliente.php?id=' . $idCliente . '&msg=campos_obrigatorios');
        exit;
    }

    $clienteExistente = $clienteBD->buscarPorCpf($cpf);

    if ($clienteExistente && (int) $clienteExistente['id_cliente'] !== $idCliente) {
        header('Location: editarCliente.php?id=' . $idCliente . '&msg=cliente_cpf_duplicado');
        exit;
    }

    try {
        $clienteBD->editar($idCliente, $cpf, $nome, $telefone, $cidade);
        header('Location: visualizarCliente.php?msg=cliente_editado');
        exit;
    } catch (PDOException $e) {
        header('Location: editarCliente.php?id=' . $idCliente . '&msg=cliente_erro');
        exit;
    }
}

$cliente = $clienteBD->buscarPorId($idCliente);

if (!$cliente) {
    header('Location: visualizarCliente.php?msg=cliente_erro');
    exit;
}

$tituloPagina = 'Editar Cliente - Ferro-Velho AG';
require_once __DIR__ . '/include/header.php';
require_once __DIR__ . '/include/navbar.php';
?>

<div class="container-fluid pagina-formulario">
    <div class="row">
        <div class="col-sm-8 col-sm-offset-2">

            <h2>Editar Cliente</h2>

            <?php require_once __DIR__ . '/include/exibirMensagem.php'; ?>

            <form method="POST" action="editarCliente.php" class="form-fva">
                <input type="hidden" name="id_cliente" value="<?= (int) $cliente['id_cliente'] ?>">

                <div class="form-group">
                    <label for="cpf">CPF *</label>
                    <input type="text" class="form-control" id="cpf" name="cpf" maxlength="14"
                           value="<?= htmlspecialchars($cliente['cpf']) ?>" required>
                </div>

                <div class="form-group">
                    <label for="nome">Nome *</label>
                    <input type="text" class="form-control" id="nome" name="nome"
                           value="<?= htmlspecialchars($cliente['nome']) ?>" required>
                </div>

                <div class="form-group">
                    <label for="telefone">Telefone *</label>
                    <input type="text" class="form-control" id="telefone" name="telefone"
                           value="<?= htmlspecialchars($cliente['telefone']) ?>" required>
                </div>

                <div class="form-group">
                    <label for="cidade">Cidade *</label>
                    <input type="text" class="form-control" id="cidade" name="cidade"
                           value="<?= htmlspecialchars($cliente['cidade']) ?>" required>
                </div>

                <button type="submit" class="btn btn-fva-primary">Salvar Alterações</button>
                <a href="visualizarCliente.php" class="btn btn-default">Cancelar</a>
            </form>

        </div>
    </div>
</div>

<?php require_once __DIR__ . '/include/footer.php'; ?>
