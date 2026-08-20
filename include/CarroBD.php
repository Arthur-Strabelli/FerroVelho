<?php

require_once __DIR__ . '/../config/conexao.php';

class CarroBD
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = conectar();
    }

    public function cadastrar(array $dados)
    {
        $sql = 'INSERT INTO veiculos
                    (id_cliente, modelo, marca, ano, preco, quilometragem, cor, combustivel,
                     situacao, imagem, descricao, avaliacao)
                VALUES
                    (:id_cliente, :modelo, :marca, :ano, :preco, :km, :cor, :combustivel,
                     :situacao, :imagem, :descricao, :avaliacao)';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id_cliente', $dados['id_cliente'] ?: null, PDO::PARAM_INT);
        $stmt->bindParam(':modelo', $dados['modelo']);
        $stmt->bindParam(':marca', $dados['marca']);
        $stmt->bindParam(':ano', $dados['ano'], PDO::PARAM_INT);
        $stmt->bindParam(':preco', $dados['preco']);
        $stmt->bindParam(':km', $dados['quilometragem'], PDO::PARAM_INT);
        $stmt->bindParam(':cor', $dados['cor']);
        $stmt->bindParam(':combustivel', $dados['combustivel']);
        $stmt->bindParam(':situacao', $dados['situacao']);
        $stmt->bindParam(':imagem', $dados['imagem']);
        $stmt->bindParam(':descricao', $dados['descricao']);
        $stmt->bindParam(':avaliacao', $dados['avaliacao'], PDO::PARAM_INT);
        $stmt->execute();

        return (int) $this->pdo->lastInsertId();
    }

    public function editar($idVeiculo, array $dados)
    {
        $sql = 'UPDATE veiculos SET
                    modelo = :modelo, marca = :marca, ano = :ano, preco = :preco,
                    quilometragem = :km, cor = :cor, combustivel = :combustivel,
                    situacao = :situacao, descricao = :descricao, avaliacao = :avaliacao';

        if (!empty($dados['imagem'])) {
            $sql .= ', imagem = :imagem';
        }

        $sql .= ' WHERE id_veiculo = :id';

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':modelo', $dados['modelo']);
        $stmt->bindParam(':marca', $dados['marca']);
        $stmt->bindParam(':ano', $dados['ano'], PDO::PARAM_INT);
        $stmt->bindParam(':preco', $dados['preco']);
        $stmt->bindParam(':km', $dados['quilometragem'], PDO::PARAM_INT);
        $stmt->bindParam(':cor', $dados['cor']);
        $stmt->bindParam(':combustivel', $dados['combustivel']);
        $stmt->bindParam(':situacao', $dados['situacao']);
        $stmt->bindParam(':descricao', $dados['descricao']);
        $stmt->bindParam(':avaliacao', $dados['avaliacao'], PDO::PARAM_INT);
        $stmt->bindParam(':id', $idVeiculo, PDO::PARAM_INT);

        if (!empty($dados['imagem'])) {
            $stmt->bindParam(':imagem', $dados['imagem']);
        }

        return $stmt->execute();
    }

    public function excluir($idVeiculo)
    {
        $stmt = $this->pdo->prepare('DELETE FROM veiculos WHERE id_veiculo = :id');
        $stmt->bindParam(':id', $idVeiculo, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function buscarPorId($idVeiculo)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM veiculos WHERE id_veiculo = :id');
        $stmt->bindParam(':id', $idVeiculo, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch();
    }

    public function listarTodos($pesquisa = '')
    {
        if ($pesquisa !== '') {
            $sql = 'SELECT * FROM veiculos
                    WHERE modelo LIKE :modelo OR marca LIKE :marca OR ano LIKE :ano
                    ORDER BY criado_em DESC';
            $stmt = $this->pdo->prepare($sql);
            $termo = '%' . $pesquisa . '%';
            $stmt->bindParam(':modelo', $termo);
            $stmt->bindParam(':marca', $termo);
            $stmt->bindParam(':ano', $termo);
        } else {
            $sql = 'SELECT * FROM veiculos ORDER BY criado_em DESC';
            $stmt = $this->pdo->prepare($sql);
        }

        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function listarUltimos($limite = 6)
    {
        $sql = 'SELECT * FROM veiculos ORDER BY criado_em DESC LIMIT :limite';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function adicionarComentario($idVeiculo, $autor, $comentario)
    {
        $sql = 'INSERT INTO comentarios (id_veiculo, autor, comentario, data_comentario, hora_comentario)
                VALUES (:id_veiculo, :autor, :comentario, CURDATE(), CURTIME())';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id_veiculo', $idVeiculo, PDO::PARAM_INT);
        $stmt->bindParam(':autor', $autor);
        $stmt->bindParam(':comentario', $comentario);

        return $stmt->execute();
    }

    public function listarComentarios($idVeiculo)
    {
        $sql = 'SELECT * FROM comentarios WHERE id_veiculo = :id ORDER BY criado_em ASC';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $idVeiculo, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}
