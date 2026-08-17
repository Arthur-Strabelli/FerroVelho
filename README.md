# Ferro-Velho AG

Sistema de gerenciamento de ferro-velho (clientes, veículos, fotos e comentários) feito em PHP com PDO/MySQL, Bootstrap 3 e jQuery.

## Instalação no USBWebserver

1. Copie a pasta do projeto para dentro de `usbwebserver/root/` (ex.: `root/ferrovelho`).
2. Abra o USBWebserver e clique em **Start** para subir o Apache e o MySQL.
3. Abra o phpMyAdmin (botão **PHPMyAdmin** do painel), vá em **Importar** e importe o `database.sql`.
4. Confira em `config/conexao.php` a porta, o usuário e a senha do MySQL (estão como `3008`, `root` e `aluno`, que são os que eu uso no meu USBWebserver). A porta e a senha aparecem no painel do USBWebserver, em **Settings**.
5. Acesse `http://localhost:8080/ferrovelho/login.php`.

Se quiser alguns veículos de exemplo, importe o `seed-carros-teste.sql` depois do `database.sql`.

Para rodar sem o USBWebserver:

```bash
php -S localhost:8000
```

## Login

Não existe usuário fixo. No primeiro acesso, informe CPF, nome e senha: o CPF é cadastrado na tabela `usuarios` e a senha é salva com `password_hash()`. Nos acessos seguintes, o CPF e a senha são conferidos com `password_verify()`.

## Estrutura

```
ferrovelho/
├── config/         conexão PDO e controle de sessão
├── include/        header, navbar, footer, DAOs, upload, funções e scripts
├── imagens/        logo e imagem padrão dos veículos
├── uploads/        fotos enviadas pelo formulário
├── css/style.css   estilos do sistema
├── database.sql    criação do banco
├── tipoMensagem.php  textos das mensagens de retorno
├── login.php / index.php / sobre.php
├── cadastrarCliente.php / visualizarCliente.php / editarCliente.php / excluirCliente.php
└── cadastrarCarro.php / visualizarCarro.php / editarCarro.php / excluirCarro.php
```

## Funcionalidades

- Login com sessão PHP e senha com hash
- CRUD de clientes (CPF, nome, telefone, cidade)
- CRUD de veículos (modelo, marca, ano, preço, km, cor, combustível, situação, avaliação, descrição e foto)
- Após cadastrar um cliente, o cadastro de veículo já abre vinculado a ele
- Listagem de veículos em cards, com "Ler mais +" mostrando descrição, detalhes técnicos e comentários
- Comentários por veículo, com data e hora
- Upload de foto restrito a JPG, JPEG e PNG, com verificação do MIME real
- Pesquisa de veículos por modelo, marca ou ano e de clientes por nome, CPF ou cidade
- Consultas com PDO e `prepare()`, saídas escapadas com `htmlspecialchars()`
