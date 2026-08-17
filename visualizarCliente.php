<?php
require_once __DIR__ . '/config/sessao.php';
exigirLogin();
require_once __DIR__ . '/include/ClienteBD.php';

$pesquisa = trim($_GET['pesquisa'] ?? '');
$clienteBD = new ClienteBD();
$clientes = $clienteBD->listarTodos($pesquisa);

$tituloPagina = 'Clientes - Ferro-Velho AG';
require_once __DIR__ . '/include/header.php';
require_once __DIR__ . '/include/navbar.php';
?>

<div class="container-fluid">

    <div class="row">
        <div class="col-xs-12">
            <h2>Clientes</h2>
            <?php require_once __DIR__ . '/include/exibirMensagem.php'; ?>
        </div>
    </div>

    <div class="row secao-pesquisa-lista">
        <div class="col-sm-6">
            <form method="GET" action="visualizarCliente.php" class="form-inline">
                <div class="input-group">
                    <input type="text" name="pesquisa" class="form-control"
                           placeholder="Pesquisar por nome, CPF ou cidade..."
                           value="<?= htmlspecialchars($pesquisa) ?>">
                    <span class="input-group-btn">
                        <button class="btn btn-fva-primary" type="submit"><i class="fa fa-search"></i></button>
                    </span>
                </div>
            </form>
        </div>
        <div class="col-sm-6 text-right">
            <a href="cadastrarCliente.php" class="btn btn-fva-primary">
                <i class="fa fa-plus"></i> Novo Cliente
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <table class="table table-striped tabela-fva">
                <thead>
                    <tr>
                        <th>CPF</th>
                        <th>Nome</th>
                        <th>Telefone</th>
                        <th>Cidade</th>
                        <th class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($clientes)): ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted">Nenhum cliente encontrado.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($clientes as $cliente): ?>
                            <tr>
                                <td><?= htmlspecialchars($cliente['cpf']) ?></td>
                                <td><?= htmlspecialchars($cliente['nome']) ?></td>
                                <td><?= htmlspecialchars($cliente['telefone']) ?></td>
                                <td><?= htmlspecialchars($cliente['cidade']) ?></td>
                                <td class="text-center">
                                    <a href="editarCliente.php?id=<?= (int) $cliente['id_cliente'] ?>"
                                       class="btn btn-fva-secundario btn-xs">
                                        <i class="fa fa-pencil"></i> Editar
                                    </a>
                                    <a href="excluirCliente.php?id=<?= (int) $cliente['id_cliente'] ?>"
                                       class="btn btn-danger btn-xs btn-excluir">
                                        <i class="fa fa-trash"></i> Excluir
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<?php require_once __DIR__ . '/include/footer.php'; ?>
