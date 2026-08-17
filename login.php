<?php
require_once __DIR__ . '/config/sessao.php';
require_once __DIR__ . '/config/conexao.php';

if (isset($_GET['acao']) && $_GET['acao'] === 'sair') {
    session_unset();
    session_destroy();
    header('Location: login.php?msg=login_sair');
    exit;
}

if (usuarioLogado()) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cpf  = trim($_POST['cpf'] ?? '');
    $nome = trim($_POST['nome'] ?? '');
    $senha = trim($_POST['senha'] ?? '');

    if ($cpf === '' || $nome === '' || $senha === '') {
        header('Location: login.php?msg=campos_obrigatorios');
        exit;
    }

    $pdo = conectar();

    $stmt = $pdo->prepare('SELECT * FROM usuarios WHERE cpf = :cpf');
    $stmt->bindParam(':cpf', $cpf);
    $stmt->execute();
    $usuario = $stmt->fetch();

    if ($usuario) {
        $senhaValida = password_verify($senha, $usuario['senha'] ?? '');

        if (!$senhaValida && $senha === $usuario['senha']) {
            $senhaValida = true;
            $stmtSenha = $pdo->prepare('UPDATE usuarios SET senha = :senha WHERE cpf = :cpf');
            $senhaNova = password_hash($senha, PASSWORD_DEFAULT);
            $stmtSenha->bindParam(':senha', $senhaNova);
            $stmtSenha->bindParam(':cpf', $cpf);
            $stmtSenha->execute();
        }

        if (!$senhaValida) {
            header('Location: login.php?msg=login_erro');
            exit;
        }

        $_SESSION['cpf'] = $usuario['cpf'];
        $_SESSION['nome_usuario'] = $usuario['nome'];
    } else {
        $stmtInsert = $pdo->prepare(
            'INSERT INTO usuarios (cpf, nome, senha) VALUES (:cpf, :nome, :senha)'
        );
        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
        $stmtInsert->bindParam(':cpf', $cpf);
        $stmtInsert->bindParam(':nome', $nome);
        $stmtInsert->bindParam(':senha', $senhaHash);
        $stmtInsert->execute();

        $_SESSION['cpf'] = $cpf;
        $_SESSION['nome_usuario'] = $nome;
    }

    header('Location: index.php');
    exit;
}

$tituloPagina = 'Login - Ferro-Velho AG';
require_once __DIR__ . '/include/header.php';
?>

<div class="tela-login">
    <div class="container">
        <div class="row">
            <div class="col-sm-6 col-sm-offset-3 col-xs-12">

                <div class="logo-login text-center">
                    <img src="imagens/FerroVelhoAG.png" alt="Ferro-Velho AG">
                    <h1>Ferro-Velho AG</h1>
                </div>

                <div class="painel-login">
                    <?php require_once __DIR__ . '/include/exibirMensagem.php'; ?>

                    <form method="POST" action="login.php">
                        <div class="form-group">
                            <label for="cpf">CPF</label>
                            <input type="text" class="form-control" id="cpf" name="cpf" maxlength="14"
                                   placeholder="000.000.000-00" required autofocus>
                        </div>
                        <div class="form-group">
                            <label for="nome">Nome</label>
                            <input type="text" class="form-control" id="nome" name="nome" required>
                        </div>
                        <div class="form-group">
                            <label for="senha">Senha</label>
                            <input type="password" class="form-control" id="senha" name="senha" required>
                        </div>
                        <button type="submit" class="btn btn-fva-primary btn-block">Entrar</button>
                    </form>

                    <p class="text-muted texto-ajuda-login">
                        Primeiro acesso? Basta preencher os campos - seu CPF será cadastrado automaticamente.
                    </p>
                </div>

            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/include/footer.php'; ?>
