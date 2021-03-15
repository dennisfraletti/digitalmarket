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

INSERT INTO pessoa VALUES
(1, "Valentim dos Santos Diniz", "mercado1@mercado1.com", "a330bb15595e98c676da4625626a218bf85a9cc5", "111.111.111-11", "11.111.111-1"),
(2, "Dennis Galvão Fraletti", "dennis@hotmail.com", "7c222fb2927d828af22f592134e8932480637c0d", "999.999.999-99", "99.999.999-9"),
(3, "Mijail Fridman", "mercado2@mercado2.com", "a330bb15595e98c676da4625626a218bf85a9cc5", "111.111.111-11", "11.111.111-1");


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

INSERT INTO endereco VALUES
(1, "57400-000", "Bairro da Urca", "S/N", "Morro da Urca", "Pão de Açúcar", "Alagoas", ""),
(2, "18580-000", "Bairro Bela Vista", "S/N", "Bairro Bela Vista", "Pereiras", "São Paulo", ""),
(3, "05203-040", "Rua Jornalista Paul Hateyer", "1000", "Vila Fanton", "São Paulo", "São Paulo", "");



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

INSERT INTO dados_conta VALUES
(1, "Companhia Brasileira de Distribuição", "47.508.411/0001-56", "Banco do Brasil", "0000-00", "0000000000", "0"),
(2, "Distribuidora Internacional de Alimentación, S.A.", "03.476.811/0008-28", "Bradesco", "1111-11", "1111111111", "1");




CREATE TABLE cliente (
    idcliente INT,
    FOREIGN KEY (idcliente) REFERENCES pessoa (id),

    fkendereco INT,
    FOREIGN KEY (fkendereco) REFERENCES endereco (idendereco),

    fkdcartao INT,
    FOREIGN KEY (fkdcartao) REFERENCES dados_cartao (iddcartao)
    
)Engine=InnoDB;

INSERT INTO cliente VALUES
(2, 2, NULL);


-- tanto clientes como proprietarios poderão ter mais de um endereco
CREATE TABLE pessoa_endereco (
    fkpessoa INT,
    FOREIGN KEY (fkpessoa) REFERENCES pessoa (id),

    fkendereco INT,
    FOREIGN KEY (fkendereco) REFERENCES endereco (idendereco)

)Engine=InnoDB;

INSERT INTO pessoa_endereco VALUES
(1, 1),
(2, 2),
(3, 3);


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


INSERT INTO mercado VALUES
(1, "Companhia Brasileira de Distribuição", "Grupo Pão de Açúcar", "47.508.411/0001-56", "pao-de-acucar.jpg", "O Grupo Pão de Açúcar nasceu com uma doceria de mesmo nome fundada em 1948 pelo imigrante português Valentim dos Santos Diniz. O primeiro supermercado da rede foi aberto em 1959. ... Em 2002, compra a rede Sé Supermercados, com 60 lojas em 16 municípios.", 1, 1, "2020-11-27"),
(3, "Distribuidora Internacional de Alimentación, S.A.", "Dia %", "03.476.811/0008-28", "dia.jpg", "A rede foi criada em 1979 na Espanha e atualmente possui mais de 7.182 lojas em oito países. A bandeira Dia % é usada na Argentina, Brasil, China e Espanha.", 3, 2, "2020-11-27");


CREATE TABLE celular (
    idcelular INT PRIMARY KEY AUTO_INCREMENT,

    fkpessoa INT,
    FOREIGN KEY (fkpessoa) REFERENCES pessoa (id),

    celular VARCHAR(15)

)Engine=InnoDB;

INSERT INTO celular VALUES
(1, 1, "(15) 99753-8358"),
(2, 1, "(19) 03433-2933"),
(3, 1, "(14) 03813-6300"),
(4, 2, "(99) 99999-9999"),
(5, 3, "(11) 11111-1111");


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

INSERT INTO produto VALUES
(2, "Barra Chocolate Milka Morango e Óreo", NULL, "12.00", "11.40", 4, 100, 3, "2020-12-01"),
(3, "Lanche Sadia Congelado X-Bacon e X-Frango", NULL, "10.00", "7.50", 1, 100, 3, "2020-12-01"),
(4, "Barra de Chocolate Talento Recheada", NULL, "8.00", "8.00", 4, 10, 3, "2020-12-01"),
(5, "Cerveja Heineken 1 Lata 350 ml", NULL, "4.00", "4.00", 2, 96, 3, "2020-12-01"),
(6, "Energético Monster Mango Loco e Ultra Paradise 473 ml", NULL, "17.00", "15.30", 2, 10, 3, "2020-12-01"),
(7, "Saco Achocolatado Ovomaltine", NULL, "12.00", "11.88", 4, 100, 3, "2020-12-01"),
(8, "Fardinho de Cerveja Budweiser 6 garrafas", NULL, "40.00", "34.00", 2, 10, 3, "2020-12-01"),
(9, "Cerveja Eisenbahn Lata 350ml", NULL, "5.50", "4.95", 2, 100, 3, "2020-12-01"),
(10, "Pacote de Bombons Lacta - Sonho de Valsa e Ouro Branco", NULL, "35.00", "35.00", 4, 10, 3, "2020-12-01"),
(11, "Whisky Jack Daniel's Fire e Tradicional 1L", NULL, "160.00", "160.00", 2, 40, 3, "2020-12-01"),
(13, "Salgadinho Ruffles Original 57g", NULL, "5.99", "5.99", 3, 100, 3, "2020-12-01"),
(14, "Salgadinho Batata Frita Lays Chips Vários Sabores 96g", NULL, "10.00", "9.00", 1, 100, 1, "2020-12-01"),
(15, "Fardo de Cerveja Stella Artois 6 un. 350 ml", NULL, "35.00", "35.00", 2, 10, 1, "2020-12-01"),
(16, "Latinha Refrigerante Coca-Cola 220ml", NULL, "2.00", "2.00", 2, 2000, 1, "2020-12-01");


CREATE TABLE produto_imagem (
    idimagem INT PRIMARY KEY AUTO_INCREMENT,
    fkproduto INT, 
    FOREIGN KEY (fkproduto) REFERENCES produto (idproduto),
    imagem VARCHAR(270)

)Engine=InnoDB;

INSERT INTO produto_imagem VALUES
(6, 4, "07102019202816.jpg"),
(7, 4, "07102019202855.jpg"),
(8, 4, "07102019202933.jpg"),
(9, 5, "04102019080303.jpg"),
(12, 8, "ceveja bud.jpg"),
(13, 9, "eisenbahn.jpg"),
(14, 9, "eisenbahn2.jpg"),
(16, 6, "energetico mango loco.jpg"),
(17, 6, "energetico verde (1).jpg"),
(18, 7, "07102019201929.jpg"),
(19, 3, "04102019142655.jpg"),
(20, 3, "04102019142736 (1).jpg"),
(21, 2, "07102019202100.jpg"),
(22, 2, "07102019202124.jpg"),
(23, 11,"jack fire.jpg"),
(24, 11, "jack (1).jpg"),
(26, 13, "04102019080847.jpg"),
(27, 10, "04102019144359.jpg"),
(28, 10, "04102019144436.jpg"),
(30, 14, "lays-2.jpg"),
(31, 14, "lays3.jpg"),
(32, 15, "fardo stella.jpg"),
(33, 16, "coca2020.jpg");


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

