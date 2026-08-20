<?php
require_once __DIR__ . '/config/sessao.php';
exigirLogin();
require_once __DIR__ . '/include/ClienteBD.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cpf      = trim(isset($_POST['cpf']) ? $_POST['cpf'] : '');
    $nome     = trim(isset($_POST['nome']) ? $_POST['nome'] : '');
    $telefone = trim(isset($_POST['telefone']) ? $_POST['telefone'] : '');
    $cidade   = trim(isset($_POST['cidade']) ? $_POST['cidade'] : '');

    if ($cpf === '' || $nome === '' || $telefone === '' || $cidade === '') {
        header('Location: cadastrarCliente.php?msg=campos_obrigatorios');
        exit;
    }

    $clienteBD = new ClienteBD();

    if ($clienteBD->buscarPorCpf($cpf)) {
        header('Location: cadastrarCliente.php?msg=cliente_cpf_duplicado');
        exit;
    }

    try {
        $idCliente = $clienteBD->cadastrar($cpf, $nome, $telefone, $cidade);
        header('Location: cadastrarCarro.php?id_cliente=' . $idCliente . '&msg=cliente_cadastrado');
        exit;
    } catch (PDOException $e) {
        header('Location: cadastrarCliente.php?msg=cliente_erro');
        exit;
    }
}

$tituloPagina = 'Cadastrar Cliente - Ferro-Velho AG';
require_once __DIR__ . '/include/header.php';
require_once __DIR__ . '/include/navbar.php';
?>

<div class="container-fluid pagina-formulario">
    <div class="row">
        <div class="col-sm-8 col-sm-offset-2">

            <h2>Cadastro do Cliente</h2>

            <?php require_once __DIR__ . '/include/exibirMensagem.php'; ?>

            <form method="POST" action="cadastrarCliente.php" class="form-fva">
                <div class="form-group">
                    <label for="cpf">CPF *</label>
                    <input type="text" class="form-control" id="cpf" name="cpf" maxlength="14"
                           placeholder="000.000.000-00" required>
                </div>

                <div class="form-group">
                    <label for="nome">Nome *</label>
                    <input type="text" class="form-control" id="nome" name="nome" required>
                </div>

                <div class="form-group">
                    <label for="telefone">Telefone *</label>
                    <input type="text" class="form-control" id="telefone" name="telefone"
                           placeholder="(00) 00000-0000" required>
                </div>

                <div class="form-group">
                    <label for="cidade">Cidade *</label>
                    <input type="text" class="form-control" id="cidade" name="cidade" required>
                </div>

                <button type="submit" class="btn btn-fva-primary">Cadastrar Cliente</button>
                <a href="visualizarCliente.php" class="btn btn-default">Cancelar</a>
            </form>

        </div>
    </div>
</div>

<?php require_once __DIR__ . '/include/footer.php'; ?>
