<?php
/**
 * include/CarroBD.php
 * Camada de acesso a dados (DAO) para as tabelas veiculos e comentarios.
 * Todas as consultas usam PDO com prepare() e bindParam()/bindValue().
 */

require_once __DIR__ . '/../config/conexao.php';

class CarroBD
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = conectar();
    }

    public function cadastrar(array $dados): int
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

    public function editar(int $idVeiculo, array $dados): bool
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

    public function excluir(int $idVeiculo): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM veiculos WHERE id_veiculo = :id');
        $stmt->bindParam(':id', $idVeiculo, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function buscarPorId(int $idVeiculo): array|false
    {
        $stmt = $this->pdo->prepare('SELECT * FROM veiculos WHERE id_veiculo = :id');
        $stmt->bindParam(':id', $idVeiculo, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch();
    }

    public function listarTodos(string $pesquisa = ''): array
    {
        if ($pesquisa !== '') {
            $sql = 'SELECT * FROM veiculos
                    WHERE modelo LIKE :pesquisa OR marca LIKE :pesquisa OR ano LIKE :pesquisa
                    ORDER BY criado_em DESC';
            $stmt = $this->pdo->prepare($sql);
            $termo = '%' . $pesquisa . '%';
            $stmt->bindParam(':pesquisa', $termo);
        } else {
            $sql = 'SELECT * FROM veiculos ORDER BY criado_em DESC';
            $stmt = $this->pdo->prepare($sql);
        }

        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function listarUltimos(int $limite = 6): array
    {
        $sql = 'SELECT * FROM veiculos ORDER BY criado_em DESC LIMIT :limite';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    // ---------- Comentários ----------

    public function adicionarComentario(int $idVeiculo, string $autor, string $comentario): bool
    {
        $sql = 'INSERT INTO comentarios (id_veiculo, autor, comentario, data_comentario, hora_comentario)
                VALUES (:id_veiculo, :autor, :comentario, CURDATE(), CURTIME())';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id_veiculo', $idVeiculo, PDO::PARAM_INT);
        $stmt->bindParam(':autor', $autor);
        $stmt->bindParam(':comentario', $comentario);

        return $stmt->execute();
    }

    public function listarComentarios(int $idVeiculo): array
    {
        $sql = 'SELECT * FROM comentarios WHERE id_veiculo = :id ORDER BY criado_em ASC';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $idVeiculo, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}
