create database if not exists sistema_denuncias;
use sistema_denuncias;

CREATE TABLE denuncias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    protocolo VARCHAR(50) NOT NULL,
    descricao TEXT NOT NULL,
    categoria VARCHAR(50),
    imagem VARCHAR(255),
    latitude VARCHAR(50),
    longitude VARCHAR(50),
    endereco VARCHAR(255),
    status VARCHAR(50) DEFAULT 'Recebida',
    is_anonimo TINYINT(1) DEFAULT 1,
    nome VARCHAR(100),
    sobrenome VARCHAR(100),
    telefone VARCHAR(20),
    email VARCHAR(100),
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);