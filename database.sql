CREATE DATABASE IF NOT EXISTS ferro_velho_ag
    DEFAULT CHARACTER SET utf8mb4
    DEFAULT COLLATE utf8mb4_unicode_ci;

USE ferro_velho_ag;

CREATE TABLE IF NOT EXISTS usuarios (
    cpf          VARCHAR(14) NOT NULL PRIMARY KEY,
    nome         VARCHAR(150) NOT NULL,
    senha        VARCHAR(255) DEFAULT NULL,
    criado_em    DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS clientes (
    id_cliente   INT AUTO_INCREMENT PRIMARY KEY,
    cpf          VARCHAR(14) NOT NULL UNIQUE,
    nome         VARCHAR(150) NOT NULL,
    telefone     VARCHAR(20)  NOT NULL,
    cidade       VARCHAR(100) NOT NULL,
    criado_em    DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS veiculos (
    id_veiculo     INT AUTO_INCREMENT PRIMARY KEY,
    id_cliente     INT NULL,
    modelo         VARCHAR(100) NOT NULL,
    marca          VARCHAR(100) NOT NULL,
    ano            INT NOT NULL,
    preco          DECIMAL(10,2) NOT NULL,
    quilometragem  INT NOT NULL,
    cor            VARCHAR(50) NOT NULL,
    combustivel    VARCHAR(30) NOT NULL,
    situacao       ENUM('Disponível','Reservado','Vendido','Em desmontagem','Sucata') NOT NULL DEFAULT 'Disponível',
    imagem         VARCHAR(255) DEFAULT NULL,
    descricao      TEXT,
    avaliacao      TINYINT UNSIGNED NOT NULL DEFAULT 0 CHECK (avaliacao BETWEEN 0 AND 10),
    criado_em      DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_veiculo_cliente FOREIGN KEY (id_cliente)
        REFERENCES clientes(id_cliente) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS comentarios (
    id_comentario  INT AUTO_INCREMENT PRIMARY KEY,
    id_veiculo     INT NOT NULL,
    autor          VARCHAR(100) NOT NULL,
    comentario     TEXT NOT NULL,
    data_comentario DATE NOT NULL,
    hora_comentario TIME NOT NULL,
    criado_em      DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_comentario_veiculo FOREIGN KEY (id_veiculo)
        REFERENCES veiculos(id_veiculo) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS galeria_fotos (
    id_foto     INT AUTO_INCREMENT PRIMARY KEY,
    id_veiculo  INT NOT NULL,
    caminho     VARCHAR(255) NOT NULL,
    ordem       INT DEFAULT 0,
    CONSTRAINT fk_foto_veiculo FOREIGN KEY (id_veiculo)
        REFERENCES veiculos(id_veiculo) ON DELETE CASCADE
) ENGINE=InnoDB;
