<?php

require_once __DIR__ . '/../config/conexao.php';

class ClienteBD
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = conectar();
    }

    public function cadastrar($cpf, $nome, $telefone, $cidade)
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

    public function editar($idCliente, $cpf, $nome, $telefone, $cidade)
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

    public function excluir($idCliente)
    {
        $stmt = $this->pdo->prepare('DELETE FROM clientes WHERE id_cliente = :id');
        $stmt->bindParam(':id', $idCliente, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function buscarPorId($idCliente)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM clientes WHERE id_cliente = :id');
        $stmt->bindParam(':id', $idCliente, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch();
    }

    public function buscarPorCpf($cpf)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM clientes WHERE cpf = :cpf');
        $stmt->bindParam(':cpf', $cpf);
        $stmt->execute();

        return $stmt->fetch();
    }

    public function listarTodos($pesquisa = '')
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
