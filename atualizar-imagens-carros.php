<?php
/**
 * atualizar-imagens-carros.php
 *
 * Script de USO ÚNICO: atualiza o campo `imagem` dos 3 veículos de
 * teste (criados pelo seed-carros-teste.sql), sem precisar editar
 * nada pelo phpMyAdmin.
 *
 * COMO USAR:
 * 1. Coloque as 3 fotos dentro da pasta uploads/ do projeto.
 * 2. Se quiser, troque os nomes de arquivo abaixo (variável $imagens)
 *    pelos nomes exatos dos arquivos que você colocou.
 * 3. Acesse http://localhost/FerroVelhoAG/atualizar-imagens-carros.php
 *    no navegador UMA vez.
 * 4. Confira a mensagem de confirmação na tela.
 * 5. Apague este arquivo depois de usar (por segurança/organização).
 */

require_once __DIR__ . '/config/conexao.php';

// Troque os nomes dos arquivos abaixo pelos que você colocou em uploads/
$imagens = [
    'Uno'   => 'uno-sucata.jpg',
    'Gol'   => 'gol-sucata.jpg',
    'Corsa' => 'corsa-sucata.jpg',
];

$pdo = conectar();
$resultado = [];

foreach ($imagens as $modelo => $nomeArquivo) {
    $stmt = $pdo->prepare('UPDATE veiculos SET imagem = :imagem WHERE modelo = :modelo');
    $stmt->bindParam(':imagem', $nomeArquivo);
    $stmt->bindParam(':modelo', $modelo);
    $stmt->execute();

    $resultado[] = "{$modelo} -> {$nomeArquivo} (linhas afetadas: {$stmt->rowCount()})";
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Atualizar imagens dos carros de teste</title>
</head>
<body style="font-family: Arial, sans-serif; padding: 30px;">
    <h2>Atualização de imagens concluída</h2>
    <ul>
        <?php foreach ($resultado as $linha): ?>
            <li><?= htmlspecialchars($linha) ?></li>
        <?php endforeach; ?>
    </ul>
    <p>
        Se "linhas afetadas" estiver como <strong>0</strong> em algum item, é porque
        não encontrou um veículo com esse modelo (confira se rodou o
        <code>seed-carros-teste.sql</code> antes).
    </p>
    <p><strong>Agora apague este arquivo (atualizar-imagens-carros.php) da pasta do projeto.</strong></p>
    <p><a href="visualizarCarro.php">Ir para a listagem de veículos</a></p>
</body>
</html>
