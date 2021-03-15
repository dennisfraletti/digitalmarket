DROP DATABASE IF EXISTS digitalmarket;
CREATE DATABASE digitalmarket;
USE digitalmarket;

-- latin1_swedish_ci retornará registros sem distinguir acentos ou caixa alta
ALTER DATABASE digitalmarket CHARSET = Latin1 COLLATE = latin1_swedish_ci;


-- tabela pessoa armazenara dados do usuario, proprietario e entregador
CREATE TABLE pessoa (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(50),
    email VARCHAR(70),
    senha CHAR(40),
    cpf CHAR(14),
    rg CHAR(12)

)Engine=InnoDB;



-- tabela endereco armazenara enderecos do usuario e do mercado
CREATE TABLE endereco (
    idendereco INT PRIMARY KEY AUTO_INCREMENT,
    cep VARCHAR(9),
    rua VARCHAR(50),
    numero VARCHAR(8),
    bairro VARCHAR(50),
    cidade VARCHAR(50),
    estado VARCHAR(20),
    complemento VARCHAR(80)

)Engine=InnoDB;



-- armazenara dados do cliente, mercado e entregador. sendo os dois ultimos obrigatorios
CREATE TABLE dados_cartao (
    iddcartao INT PRIMARY KEY AUTO_INCREMENT,
    ncartao VARCHAR(19),
    titular VARCHAR(50),
    validade DATE,
    cvv CHAR(3)

)Engine=InnoDB;


CREATE TABLE dados_conta (
    iddconta INT PRIMARY KEY AUTO_INCREMENT,
    beneficiario VARCHAR(70),
    
    -- cpf ou cnpj
    documento VARCHAR(18),
    
    banco VARCHAR(50),
    agencia VARCHAR(7),
    conta VARCHAR(10),
    digito VARCHAR(1)

)Engine=InnoDB;



CREATE TABLE cliente (
    idcliente INT,
    FOREIGN KEY (idcliente) REFERENCES pessoa (id),

    fkendereco INT,
    FOREIGN KEY (fkendereco) REFERENCES endereco (idendereco),

    fkdcartao INT,
    FOREIGN KEY (fkdcartao) REFERENCES dados_cartao (iddcartao)
    
)Engine=InnoDB;


-- tanto clientes como proprietarios poderão ter mais de um endereco
CREATE TABLE pessoa_endereco (
    fkpessoa INT,
    FOREIGN KEY (fkpessoa) REFERENCES pessoa (id),

    fkendereco INT,
    FOREIGN KEY (fkendereco) REFERENCES endereco (idendereco)

)Engine=InnoDB;



CREATE TABLE mercado (
    -- fkpessoa para ter os dados do próprietario, email e senha da loja, etc
    idmercado INT,
    FOREIGN KEY (idmercado) REFERENCES pessoa (id),
    
    razaosocial VARCHAR(50),
    nomefantasia VARCHAR(50),
    cnpj VARCHAR(18),
    logo VARCHAR(270),
    bio VARCHAR(255),

    fkendereco INT,
    FOREIGN KEY (fkendereco) REFERENCES endereco (idendereco),

    -- 0 caso a pessoa já possua um entregador, 1 caso ela use um entregador do digitalmarket
    -- plano CHAR(1), 

    fkdconta INT,
    FOREIGN KEY (fkdconta) REFERENCES dados_conta (iddconta),

    cadastro DATE

)Engine=InnoDB;


CREATE TABLE celular (
    idcelular INT PRIMARY KEY AUTO_INCREMENT,

    fkpessoa INT,
    FOREIGN KEY (fkpessoa) REFERENCES pessoa (id),

    celular VARCHAR(15)

)Engine=InnoDB;


-- CREATE TABLE veiculo (
--     idveiculo INT PRIMARY KEY AUTO_INCREMENT,
--     veiculo VARCHAR(8)

-- )Engine=InnoDB;

-- INSERT INTO veiculo VALUES
-- (0, "Moto"),
-- (0, "Carro"),
-- (0, "Caminhão");


-- contatos emergenciais do entregador
-- CREATE TABLE contato_entregador (
--     idcontato INT PRIMARY KEY AUTO_INCREMENT,
--     contato VARCHAR(50),
--     celular VARCHAR(15),
--     parentesco VARCHAR(20)

-- )Engine=InnoDB;


-- CREATE TABLE entregador (
--     identregador INT,
--     FOREIGN KEY (identregador) REFERENCES pessoa (id),

--     foto VARCHAR(270),

--     fkcontato INT,
--     FOREIGN KEY (fkcontato) REFERENCES contato_entregador (idcontato), 

--     cnh VARCHAR(270),

--     fkveiculo INT,
--     FOREIGN KEY (fkveiculo) REFERENCES veiculo (idveiculo),


--     fkdconta INT,
--     FOREIGN KEY (fkdconta) REFERENCES dados_conta (iddconta)

-- )Engine=InnoDB; 



CREATE TABLE categoria (
    idcategoria INT PRIMARY KEY AUTO_INCREMENT,
    categoria VARCHAR(30)

)Engine=InnoDB;

INSERT INTO categoria VALUES
(0, "Alimentos"),
(0, "Bebidas"),
(0, "Carnes"),
(0, "Laticínios"),
(0, "Limpeza"),
(0, "Padaria"),
(0, "Perfumaria"),
(0, "Outros");

-- (0, "Alimentos"),
-- (0, "Bebidas"),
-- (0, "Bebidas alcóolicas"),
-- (0, "Bebês e crianças"),
-- (0, "Beleza e perfumaria"),
-- (0, "Brinquedos e jogos"),
-- (0, "Cama, mesa e banho"),
-- (0, "Cuidados pessoais"),
-- (0, "Eventos e festas"),
-- (0, "Limpeza"),
-- (0, "Papelaria"),
-- (0, "Petshop"),
-- (0, "Utensílos e descartáveis");


CREATE TABLE categoria_imagem (
    idimagem INT PRIMARY KEY AUTO_INCREMENT,
    fkcategoria INT,
    FOREIGN KEY (fkcategoria) REFERENCES categoria (idcategoria),

    imagem VARCHAR(270)

)Engine=InnoDB;

INSERT INTO categoria_imagem VALUES
(0, 1, "alimentos-1.jpg"),
(0, 1, "alimentos-2.jpg"),
(0, 1, "alimentos-3.jpg"),
(0, 1, "alimentos-4.jpg"),
(0, 1, "alimentos-5.jpg"),
(0, 1, "alimentos-6.jpg"),
(0, 1, "alimentos-7.jpg"),
(0, 2, "bebidas-1.jpg"),
(0, 2, "bebidas-2.jpg"),
(0, 2, "bebidas-3.jpg"),
(0, 2, "bebidas-4.jpg"),
(0, 2, "bebidas-5.jpg"),
(0, 2, "bebidas-6.jpg"),
(0, 3, "carnes-1.jpg"),
(0, 3, "carnes-2.jpg"),
(0, 3, "carnes-3.jpg"),
(0, 3, "carnes-4.jpg"),
(0, 3, "carnes-5.jpg"),
(0, 4, "laticinios-1.jpg"),
(0, 4, "laticinios-2.jpg"),
(0, 4, "laticinios-3.jpg"),
(0, 4, "laticinios-4.jpg"),
(0, 4, "laticinios-5.jpg"),
(0, 4, "laticinios-6.jpg"),
(0, 5, "limpeza-1.jpg"),
(0, 5, "limpeza-2.jpg"),
(0, 5, "limpeza-3.jpg"),
(0, 5, "limpeza-4.jpg"),
(0, 5, "limpeza-5.jpg"),
(0, 6, "padaria-1.jpg"),
(0, 6, "padaria-2.jpg"),
(0, 6, "padaria-3.jpg"),
(0, 6, "padaria-4.jpg"),
(0, 6, "padaria-5.jpg"),
(0, 6, "padaria-6.jpg"),
(0, 7, "perfumaria-1.jpg"),
(0, 7, "perfumaria-2.jpg"),
(0, 7, "perfumaria-3.jpg"),
(0, 7, "perfumaria-4.jpg"),
(0, 7, "perfumaria-5.jpg"),
(0, 8, "outros-1.jpg"),
(0, 8, "outros-2.jpg"),
(0, 8, "outros-3.jpg"),
(0, 8, "outros-4.jpg"),
(0, 8, "outros-5.jpg");


CREATE TABLE produto (
    idproduto INT PRIMARY KEY AUTO_INCREMENT,
    titulo VARCHAR(60),
    descricao VARCHAR(150),

    preco DECIMAL(12,2),
    precodesconto DECIMAL(12,2),
    

    fkcategoria INT,
    FOREIGN KEY (fkcategoria) REFERENCES categoria (idcategoria),

    estoque INT,

    fkmercado INT, 
    FOREIGN KEY (fkmercado) REFERENCES mercado (idmercado),

    cadastro DATE

)Engine=InnoDB;


CREATE TABLE produto_imagem (
    idimagem INT PRIMARY KEY AUTO_INCREMENT,
    fkproduto INT, 
    FOREIGN KEY (fkproduto) REFERENCES produto (idproduto),
    imagem VARCHAR(270)

)Engine=InnoDB;

-- INSERT INTO `produto`(`idproduto`, `descricao`, `preco`, `desconto`, `tipodesc`, `fkcategoria`, `estoque`, `imgproduto`, `fkmercado`) VALUES (0, "Red Dead Redemption II - Xbox One", 200, 30, 0, 9, 100, "rd2.jpg", 1);

-- CREATE TABLE comentario (
--     idcomentario INT PRIMARY KEY AUTO_INCREMENT,
    
--     fkcliente INT,
--     FOREIGN KEY (fkcliente) REFERENCES cliente (idcliente),

--     fkproduto INT,
--     FOREIGN KEY (fkproduto) REFERENCES produto (idproduto),

--     avaliacao INT,
--     comentario VARCHAR(100)

-- )Engine=InnoDB;



-- a tabela sacola servira somente para listar os itens ao usuário temporariamente
CREATE TABLE carrinho (
    idcarrinho INT PRIMARY KEY AUTO_INCREMENT,

    fkproduto INT,
    FOREIGN KEY (fkproduto) REFERENCES produto (idproduto),

    fkcliente INT,
    FOREIGN KEY (fkcliente) REFERENCES cliente (idcliente),

    qtd INT

)Engine=InnoDB;



CREATE TABLE pedido (
    idpedido INT PRIMARY KEY AUTO_INCREMENT,

    fkcliente INT,
    FOREIGN KEY (fkcliente) REFERENCES cliente (idcliente),

    troco DECIMAL(12,2),

    -- 0 para cartao e 1 para dinheiro
    pagamento CHAR(1),

    precototal DECIMAL(12,2),

    -- 1 para quando estiver todos produtos entregues

    data_pedido DATETIME

)Engine=InnoDB;


CREATE TABLE pedido_detalhes (
    iddetalhe INT PRIMARY KEY AUTO_INCREMENT,

    fkpedido INT,
    FOREIGN KEY (fkpedido) REFERENCES pedido (idpedido),

    fkproduto INT,
    FOREIGN KEY (fkproduto) REFERENCES produto (idproduto),

    -- 0 para não entregue e 1 para entregue
    entregue CHAR(1),

    qtd INT

)Engine=InnoDB;


CREATE TABLE visitas (
    idvisita INT AUTO_INCREMENT PRIMARY KEY,
    fkcliente INT,
    FOREIGN KEY (fkcliente) REFERENCES cliente (idcliente),

    fkproduto INT,
    FOREIGN KEY (fkproduto) REFERENCES produto (idproduto)

)Engine=InnoDB;


CREATE TABLE pesquisas (
    idpesquisa INT AUTO_INCREMENT PRIMARY KEY,

    pesquisa VARCHAR(150),

    fkcliente INT,
    FOREIGN KEY (fkcliente) REFERENCES cliente (idcliente)

)Engine=InnoDB;


DELIMITER $$

DROP PROCEDURE IF EXISTS selecionar_tipo_usuario$$ 
CREATE PROCEDURE selecionar_tipo_usuario (id INT)
BEGIN
    IF ((SELECT COUNT(*) FROM cliente WHERE idcliente = id) > 0) THEN
        SELECT 0 AS "tipo";

    ELSEIF ((SELECT COUNT(*) FROM mercado WHERE idmercado = id) > 0) THEN
        SELECT 1 AS "tipo";
        
    END IF;
END $$


-- CREATE TRIGGER atualizar_pedido AFTER UPDATE
-- ON pedido_detalhes
-- FOR EACH ROW
-- BEGIN
--     DECLARE qtd_produtos INT;
--     DECLARE qtd_produtos_encaminhados INT;
--     DECLARE qtd_produtos_entregue INT;

--     SET qtd_produtos = (SELECT COUNT(*) FROM pedido_detalhes WHERE fkpedido = OLD.fkpedido);

--     SET qtd_produtos_encaminhados = (SELECT COUNT(*) FROM pedido_detalhes WHERE fkpedido = OLD.fkpedido AND entregue = 1);

--     SET qtd_produtos_entregue = (SELECT COUNT(*) FROM pedido_detalhes WHERE fkpedido = OLD.fkpedido AND entregue = 2);

--     IF (qtd_produtos = qtd_produtos_encaminhados) THEN
--         UPDATE pedido SET concluido = 1 WHERE idpedido = OLD.fkpedido;
    
--     ELSEIF (qtd_produtos = qtd_produtos_entregue) THEN
--         UPDATE pedido SET concluido = 2 WHERE idpedido = OLD.fkpedido;
    
--     END IF;
-- END $$


CREATE TRIGGER atualizar_qtd_produto BEFORE INSERT
ON pedido_detalhes
FOR EACH ROW
BEGIN
    UPDATE produto SET estoque = estoque - NEW.qtd WHERE idproduto = NEW.fkproduto;
END $$

DELIMITER ;



