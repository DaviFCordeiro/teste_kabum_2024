CREATE SCHEMA `teste_kabum_2024` ;

USE teste_kabum_2024;

CREATE TABLE IF NOT EXISTS auth (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL
);

INSERT INTO auth (username, password) VALUES
    ('admin', '12345');

CREATE TABLE clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255),
    data_nascimento DATE,
    cpf CHAR(11),
    rg VARCHAR(20),
    telefone VARCHAR(20)
);

CREATE TABLE enderecos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT,
    endereco VARCHAR(255),
    numero_complemento VARCHAR(100),
    cep CHAR(8),
    bairro VARCHAR(100),    
    cidade VARCHAR(100),
    estado CHAR(2),
    FOREIGN KEY (cliente_id) REFERENCES clientes(id)
);

INSERT INTO clientes (nome, data_nascimento, cpf, rg, telefone)
VALUES    
    ('Joao Coelho', '1985-08-25', '00132331063', '320259407', '888888888'),
    ('Marcos dos Anjos', '1985-08-25', '57738840030', '451166875', '888888888'),
    ('Aurelio da Silva', '1985-08-25', '77099195026', '261424701', '888888888'),
    ('Rodolfo Mendes', '1985-08-25', '53430501075', '445410425', '888888888'),
    ('Luiza Almeida', '1985-08-25', '68727503089', '505329918', '888888888'),
    ('Pablo Martins', '1985-08-25', '21774820030', '453361195', '888888888'),
    ('Maria Almeida', '1985-08-25', '88807034042', '352912716', '888888888'),
    ('Carlos Vinicius', '1985-08-25', '61339879018', '201756985', '888888888'),
    ('Leandro Cruz', '1985-08-25', '53569401065', '463532247', '888888888'),
    ('Jefferson Pinto', '1985-08-25', '47659822039', '168452418', '888888888'),
    ('Amanda Matias', '1985-08-25', '47229831008', '218369013', '888888888'),
    ('Rodolfo Miguel', '1985-08-25', '58638251033', '137663997', '888888888'),
    ('Lucas Vinicius', '1985-08-25', '13917746042', '163550682', '888888888'),
    ('Jose Francisco', '1990-05-15', '32541729065', '419957121', '999999999'),
    ('Ana Carolina', '1985-08-25', '26766028025', '124558343', '888888888');

INSERT INTO enderecos (cliente_id, endereco, numero_complemento, cep, bairro, cidade, estado)
VALUES
    (1, 'Rua Ivaí', '139', '09560570', 'Santa Maria', 'São Caetano do Sul', 'SP'),
    (1, 'Rua Castro Alves', '100', '09560570', 'Cerâmica', 'São Caetano do Sul', 'SP'),
    (2, 'Rua Professor Paulo Augusto Bueno Wolf', '95', '11030395', 'Ponta da Praia', 'Santos', 'SP'),
    (3, 'Rua Castro Alves', '100', '09560570', 'Cerâmica', 'São Caetano do Sul', 'SP'),
    (4, 'Rua Castro Alves', '100', '09560570', 'Cerâmica', 'São Caetano do Sul', 'SP'),
    (5, 'Rua Castro Alves', '100', '09560570', 'Cerâmica', 'São Caetano do Sul', 'SP'),
    (6, 'Rua Castro Alves', '100', '09560570', 'Cerâmica', 'São Caetano do Sul', 'SP'),
    (7, 'Rua Castro Alves', '100', '09560570', 'Cerâmica', 'São Caetano do Sul', 'SP'),
    (8, 'Rua Castro Alves', '100', '09560570', 'Cerâmica', 'São Caetano do Sul', 'SP'),
    (9, 'Rua Castro Alves', '100', '09560570', 'Cerâmica', 'São Caetano do Sul', 'SP'),
    (10, 'Rua Castro Alves', '100', '09560570', 'Cerâmica', 'São Caetano do Sul', 'SP'),
    (11, 'Rua Castro Alves', '100', '09560570', 'Cerâmica', 'São Caetano do Sul', 'SP'),
    (12, 'Rua Castro Alves', '100', '09560570', 'Cerâmica', 'São Caetano do Sul', 'SP'),
    (13, 'Rua Castro Alves', '100', '09560570', 'Cerâmica', 'São Caetano do Sul', 'SP'),
    (14, 'Rua Castro Alves', '100', '09560570', 'Cerâmica', 'São Caetano do Sul', 'SP'),
    (15, 'Rua Castro Alves', '100', '09560570', 'Cerâmica', 'São Caetano do Sul', 'SP');