-- =========================================================
-- Ferro-Velho AG - Dados de teste
-- 3 veículos genéricos em estado "preocupante", cada um com
-- um comentário exagerado do vendedor.
--
-- Execute este script (via phpMyAdmin > Importar, ou colando
-- na aba SQL) DEPOIS de ter rodado o database.sql.
--
-- OBS: o campo `imagem` fica NULL de propósito - eu não
-- consigo gerar fotos reais de carros sucateados. O sistema
-- mostrará a imagem padrão (imagens/sem-imagem.png) até você
-- colocar arquivos reais em uploads/ e atualizar o campo.
-- =========================================================

USE ferro_velho_ag;

INSERT INTO veiculos
    (modelo, marca, ano, preco, quilometragem, cor, combustivel, situacao, imagem, descricao, avaliacao)
VALUES
    (
        'Uno',
        'Fiat',
        1998,
        500.00,
        312000,
        'Branco (o que sobrou da pintura)',
        'Gasolina',
        'Sucata',
        NULL,
        'Uno todo enferrujado, sem motor, movido a empurrão e fé. Bancos comidos por traça, painel incompleto e cheiro de mofo de dar orgulho.',
        1
    ),
    (
        'Gol',
        'Volkswagen',
        2003,
        800.00,
        287000,
        'Prata (embaixo da ferrugem)',
        'Flex',
        'Em desmontagem',
        NULL,
        'Gol com o banco do carona substituído por uma cadeira de plástico de jardim. Vidro traseiro é um saco de lixo bem esticado.',
        2
    ),
    (
        'Corsa',
        'Chevrolet',
        2000,
        350.00,
        401500,
        'Sem cor definida',
        'Gasolina',
        'Sucata',
        NULL,
        'Corsa sem vidros, sem pneus, e com um ninho de pombos no porta-malas. Motor está lá de enfeite, mais nada.',
        0
    );

-- Recupera os IDs recém-inseridos para vincular os comentários
SET @id_uno   = (SELECT id_veiculo FROM veiculos WHERE modelo = 'Uno' AND marca = 'Fiat' ORDER BY id_veiculo DESC LIMIT 1);
SET @id_gol   = (SELECT id_veiculo FROM veiculos WHERE modelo = 'Gol' AND marca = 'Volkswagen' ORDER BY id_veiculo DESC LIMIT 1);
SET @id_corsa = (SELECT id_veiculo FROM veiculos WHERE modelo = 'Corsa' AND marca = 'Chevrolet' ORDER BY id_veiculo DESC LIMIT 1);

INSERT INTO comentarios (id_veiculo, autor, comentario, data_comentario, hora_comentario)
VALUES
    (@id_uno,   'Vendedor', 'Quando eu nasci, este carro já não ligava há uns 5 anos.', CURDATE(), CURTIME()),
    (@id_gol,   'Vendedor', 'Já foi roubado duas vezes e devolvido nas duas - ninguém quis ficar com ele.', CURDATE(), CURTIME()),
    (@id_corsa, 'Vendedor', 'Testei a buzina outro dia e foi a única coisa que ainda funciona nele.', CURDATE(), CURTIME());
