<?php

require_once __DIR__ . '/../config/conexao.php';

class ClienteBD
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = conectar();
    }

    public function cadastrar(string $cpf, string $nome, string $telefone, string $cidade): int
    {
        $sql = 'INSERT INTO clientes (cpf, nome, telefone, cidade) VALUES (:cpf, :nome, :telefone, :cidade)';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':cpf', $cpf);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':telefone', $telefone);
        $stmt->bindParam(':cidade', $cidade);
        $stmt->execute();

        return (int) $this->pdo->lastInsertId();
    }

    public function editar(int $idCliente, string $cpf, string $nome, string $telefone, string $cidade): bool
    {
        $sql = 'UPDATE clientes SET cpf = :cpf, nome = :nome, telefone = :telefone, cidade = :cidade
                WHERE id_cliente = :id';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':cpf', $cpf);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':telefone', $telefone);
        $stmt->bindParam(':cidade', $cidade);
        $stmt->bindParam(':id', $idCliente, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function excluir(int $idCliente): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM clientes WHERE id_cliente = :id');
        $stmt->bindParam(':id', $idCliente, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function buscarPorId(int $idCliente)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM clientes WHERE id_cliente = :id');
        $stmt->bindParam(':id', $idCliente, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch();
    }

    public function buscarPorCpf(string $cpf)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM clientes WHERE cpf = :cpf');
        $stmt->bindParam(':cpf', $cpf);
        $stmt->execute();

        return $stmt->fetch();
    }

    public function listarTodos(string $pesquisa = ''): array
    {
        if ($pesquisa !== '') {
            $sql = 'SELECT * FROM clientes
                    WHERE nome LIKE :nome OR cpf LIKE :cpf OR cidade LIKE :cidade
                    ORDER BY nome ASC';
            $stmt = $this->pdo->prepare($sql);
            $termo = '%' . $pesquisa . '%';
            $stmt->bindParam(':nome', $termo);
            $stmt->bindParam(':cpf', $termo);
            $stmt->bindParam(':cidade', $termo);
        } else {
            $sql = 'SELECT * FROM clientes ORDER BY nome ASC';
            $stmt = $this->pdo->prepare($sql);
        }

        $stmt->execute();

        return $stmt->fetchAll();
    }
}
