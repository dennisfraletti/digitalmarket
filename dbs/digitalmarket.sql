-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: sql312.byetcluster.com
-- Tempo de geração: 15/03/2021 às 15:34
-- Versão do servidor: 5.6.48-88.0
-- Versão do PHP: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `digitalmarket`
--
CREATE DATABASE IF NOT EXISTS `digitalmarket` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `digitalmarket`;

-- --------------------------------------------------------

--
-- Estrutura para tabela `carrinho`
--

CREATE TABLE `carrinho` (
  `idcarrinho` int(11) NOT NULL,
  `fkproduto` int(11) DEFAULT NULL,
  `fkcliente` int(11) DEFAULT NULL,
  `qtd` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `categoria`
--

CREATE TABLE `categoria` (
  `idcategoria` int(11) NOT NULL,
  `categoria` varchar(30) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Despejando dados para a tabela `categoria`
--

INSERT INTO `categoria` (`idcategoria`, `categoria`) VALUES
(1, 'Alimentos'),
(2, 'Bebidas'),
(3, 'Carnes'),
(4, 'Laticínios'),
(5, 'Limpeza'),
(6, 'Padaria'),
(7, 'Perfumaria'),
(8, 'Outros');

-- --------------------------------------------------------

--
-- Estrutura para tabela `categoria_imagem`
--

CREATE TABLE `categoria_imagem` (
  `idimagem` int(11) NOT NULL,
  `fkcategoria` int(11) DEFAULT NULL,
  `imagem` varchar(270) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Despejando dados para a tabela `categoria_imagem`
--

INSERT INTO `categoria_imagem` (`idimagem`, `fkcategoria`, `imagem`) VALUES
(1, 1, 'alimentos-1.jpg'),
(2, 1, 'alimentos-2.jpg'),
(3, 1, 'alimentos-3.jpg'),
(4, 1, 'alimentos-4.jpg'),
(5, 1, 'alimentos-5.jpg'),
(6, 1, 'alimentos-6.jpg'),
(7, 1, 'alimentos-7.jpg'),
(8, 2, 'bebidas-1.jpg'),
(9, 2, 'bebidas-2.jpg'),
(10, 2, 'bebidas-3.jpg'),
(11, 2, 'bebidas-4.jpg'),
(12, 2, 'bebidas-5.jpg'),
(13, 2, 'bebidas-6.jpg'),
(14, 3, 'carnes-1.jpg'),
(15, 3, 'carnes-2.jpg'),
(16, 3, 'carnes-3.jpg'),
(17, 3, 'carnes-4.jpg'),
(18, 3, 'carnes-5.jpg'),
(19, 4, 'laticinios-1.jpg'),
(20, 4, 'laticinios-2.jpg'),
(21, 4, 'laticinios-3.jpg'),
(22, 4, 'laticinios-4.jpg'),
(23, 4, 'laticinios-5.jpg'),
(24, 4, 'laticinios-6.jpg'),
(25, 5, 'limpeza-1.jpg'),
(26, 5, 'limpeza-2.jpg'),
(27, 5, 'limpeza-3.jpg'),
(28, 5, 'limpeza-4.jpg'),
(29, 5, 'limpeza-5.jpg'),
(30, 6, 'padaria-1.jpg'),
(31, 6, 'padaria-2.jpg'),
(32, 6, 'padaria-3.jpg'),
(33, 6, 'padaria-4.jpg'),
(34, 6, 'padaria-5.jpg'),
(35, 6, 'padaria-6.jpg'),
(36, 7, 'perfumaria-1.jpg'),
(37, 7, 'perfumaria-2.jpg'),
(38, 7, 'perfumaria-3.jpg'),
(39, 7, 'perfumaria-4.jpg'),
(40, 7, 'perfumaria-5.jpg'),
(41, 8, 'outros-1.jpg'),
(42, 8, 'outros-2.jpg'),
(43, 8, 'outros-3.jpg'),
(44, 8, 'outros-4.jpg'),
(45, 8, 'outros-5.jpg');

-- --------------------------------------------------------

--
-- Estrutura para tabela `celular`
--

CREATE TABLE `celular` (
  `idcelular` int(11) NOT NULL,
  `fkpessoa` int(11) DEFAULT NULL,
  `celular` varchar(15) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Despejando dados para a tabela `celular`
--

INSERT INTO `celular` (`idcelular`, `fkpessoa`, `celular`) VALUES
(1, 1, '(11) 11111-1111'),
(2, 2, '(00) 00000-0000'),
(3, 3, '(00) 00000-0000'),
(4, 4, '(14) 99885-5800');

-- --------------------------------------------------------

--
-- Estrutura para tabela `cliente`
--

CREATE TABLE `cliente` (
  `idcliente` int(11) DEFAULT NULL,
  `fkendereco` int(11) DEFAULT NULL,
  `fkdcartao` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Despejando dados para a tabela `cliente`
--

INSERT INTO `cliente` (`idcliente`, `fkendereco`, `fkdcartao`) VALUES
(4, 4, 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `dados_cartao`
--

CREATE TABLE `dados_cartao` (
  `iddcartao` int(11) NOT NULL,
  `ncartao` varchar(19) DEFAULT NULL,
  `titular` varchar(50) DEFAULT NULL,
  `validade` date DEFAULT NULL,
  `cvv` char(3) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Despejando dados para a tabela `dados_cartao`
--

INSERT INTO `dados_cartao` (`iddcartao`, `ncartao`, `titular`, `validade`, `cvv`) VALUES
(1, '0000.0000.0000.0000', 'Dennis Galvão Fraletti', '2020-07-00', '199');

-- --------------------------------------------------------

--
-- Estrutura para tabela `dados_conta`
--

CREATE TABLE `dados_conta` (
  `iddconta` int(11) NOT NULL,
  `beneficiario` varchar(70) DEFAULT NULL,
  `documento` varchar(18) DEFAULT NULL,
  `banco` varchar(50) DEFAULT NULL,
  `agencia` varchar(7) DEFAULT NULL,
  `conta` varchar(10) DEFAULT NULL,
  `digito` varchar(1) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Despejando dados para a tabela `dados_conta`
--

INSERT INTO `dados_conta` (`iddconta`, `beneficiario`, `documento`, `banco`, `agencia`, `conta`, `digito`) VALUES
(1, 'Mercados Saint Louis', '00.000.000/0000-00', 'Santander', '0000-00', '0000000000', '0'),
(2, 'Andrea Pirlo', '111.111.111-11', 'Next', '0000-00', '0000000000', '0'),
(3, 'Johan Cruyff', '111.111.111-11', 'Banco do Brasil', '0000-00', '0000000000', '0');

-- --------------------------------------------------------

--
-- Estrutura para tabela `endereco`
--

CREATE TABLE `endereco` (
  `idendereco` int(11) NOT NULL,
  `cep` varchar(9) DEFAULT NULL,
  `rua` varchar(50) DEFAULT NULL,
  `numero` varchar(8) DEFAULT NULL,
  `bairro` varchar(50) DEFAULT NULL,
  `cidade` varchar(50) DEFAULT NULL,
  `estado` varchar(20) DEFAULT NULL,
  `complemento` varchar(80) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Despejando dados para a tabela `endereco`
--

INSERT INTO `endereco` (`idendereco`, `cep`, `rua`, `numero`, `bairro`, `cidade`, `estado`, `complemento`) VALUES
(1, '13201-280', 'Rua França', '42', 'Vila Municipal', 'Jundiaí', 'São Paulo', ''),
(2, '13471-550', 'Rua Itália', '602', 'Jardim Paulistano', 'Americana', 'São Paulo', ''),
(3, '07180-200', 'Rua Holandesa', 'S/N', 'Cidade Jardim Cumbica', 'Guarulhos', 'São Paulo', ''),
(4, '18580-000', 'S/R', 'S/N', 'Bairro Bela Vista', 'Pereiras', 'São Paulo', 'Rodovia Floriano Camargo de Barros, KM 12');

-- --------------------------------------------------------

--
-- Estrutura para tabela `mercado`
--

CREATE TABLE `mercado` (
  `idmercado` int(11) DEFAULT NULL,
  `razaosocial` varchar(50) DEFAULT NULL,
  `nomefantasia` varchar(50) DEFAULT NULL,
  `cnpj` varchar(18) DEFAULT NULL,
  `logo` varchar(270) DEFAULT NULL,
  `bio` varchar(255) DEFAULT NULL,
  `fkendereco` int(11) DEFAULT NULL,
  `fkdconta` int(11) DEFAULT NULL,
  `cadastro` date DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Despejando dados para a tabela `mercado`
--

INSERT INTO `mercado` (`idmercado`, `razaosocial`, `nomefantasia`, `cnpj`, `logo`, `bio`, `fkendereco`, `fkdconta`, `cadastro`) VALUES
(1, 'Mercados Saint Louis', 'Saint Louis', '00.000.000/0000-00', 'fofo (1).jpg', '', 1, 1, '2021-01-25'),
(2, 'Mercatos Luigi\'s ', 'Luigi\'s', '00.000.000/0000-00', 'luigi (1).jpg', '', 2, 2, '2021-01-25'),
(3, 'Grupo Nederlands Away', 'Nederlands Market', '00.000.000/0000-00', 'holanda.jpg', '', 3, 3, '2021-01-25');

-- --------------------------------------------------------

--
-- Estrutura para tabela `pedido`
--

CREATE TABLE `pedido` (
  `idpedido` int(11) NOT NULL,
  `fkcliente` int(11) DEFAULT NULL,
  `troco` decimal(12,2) DEFAULT NULL,
  `pagamento` char(1) DEFAULT NULL,
  `precototal` decimal(12,2) DEFAULT NULL,
  `data_pedido` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Despejando dados para a tabela `pedido`
--

INSERT INTO `pedido` (`idpedido`, `fkcliente`, `troco`, `pagamento`, `precototal`, `data_pedido`) VALUES
(1, 4, NULL, '0', '26.79', '2021-01-25 20:01:36');

-- --------------------------------------------------------

--
-- Estrutura para tabela `pedido_detalhes`
--

CREATE TABLE `pedido_detalhes` (
  `iddetalhe` int(11) NOT NULL,
  `fkpedido` int(11) DEFAULT NULL,
  `fkproduto` int(11) DEFAULT NULL,
  `entregue` char(1) DEFAULT NULL,
  `qtd` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Despejando dados para a tabela `pedido_detalhes`
--

INSERT INTO `pedido_detalhes` (`iddetalhe`, `fkpedido`, `fkproduto`, `entregue`, `qtd`) VALUES
(1, 1, 1, '0', 1),
(2, 1, 5, '0', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `pesquisas`
--

CREATE TABLE `pesquisas` (
  `idpesquisa` int(11) NOT NULL,
  `pesquisa` varchar(150) DEFAULT NULL,
  `fkcliente` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Despejando dados para a tabela `pesquisas`
--

INSERT INTO `pesquisas` (`idpesquisa`, `pesquisa`, `fkcliente`) VALUES
(1, 'a', 4),
(2, 'sucrilhos', 4),
(3, 'sucri', 4),
(4, 'sucrilhos', 4);

-- --------------------------------------------------------

--
-- Estrutura para tabela `pessoa`
--

CREATE TABLE `pessoa` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) DEFAULT NULL,
  `email` varchar(70) DEFAULT NULL,
  `senha` char(40) DEFAULT NULL,
  `cpf` char(14) DEFAULT NULL,
  `rg` char(12) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Despejando dados para a tabela `pessoa`
--

INSERT INTO `pessoa` (`id`, `nome`, `email`, `senha`, `cpf`, `rg`) VALUES
(1, 'Zinédine Zidane', 'saintlouis@gmail.com', '91e8878497e633f0a27459f7de0ab9052fb063fa', '111.111.111-11', '11.111.111-1'),
(2, 'Andrea Pirlo', 'luigis@hotmail.com', 'cb597be5f63a82e997abd968f59c20635ae9fb49', '111.111.111-11', '11.111.111-1'),
(3, 'Johan Cruyff', 'nederlandsaway@gmail.com', '6dcc4f581190bf00eb4858eb949dc891c67e57c4', '111.111.111-11', '11.111.111-1'),
(4, 'Dennis Galvão Fraletti Augusto da Silva', 'dennisfgalvao1@gmail.com', '32bbf4efedbf79c55380f014eb6dcc3b784b027f', '111.111.111-11', '00.000.000-0');

-- --------------------------------------------------------

--
-- Estrutura para tabela `pessoa_endereco`
--

CREATE TABLE `pessoa_endereco` (
  `fkpessoa` int(11) DEFAULT NULL,
  `fkendereco` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Despejando dados para a tabela `pessoa_endereco`
--

INSERT INTO `pessoa_endereco` (`fkpessoa`, `fkendereco`) VALUES
(1, 1),
(2, 2),
(3, 3),
(4, 4);

-- --------------------------------------------------------

--
-- Estrutura para tabela `produto`
--

CREATE TABLE `produto` (
  `idproduto` int(11) NOT NULL,
  `titulo` varchar(60) DEFAULT NULL,
  `descricao` varchar(150) DEFAULT NULL,
  `preco` decimal(12,2) DEFAULT NULL,
  `precodesconto` decimal(12,2) DEFAULT NULL,
  `fkcategoria` int(11) DEFAULT NULL,
  `estoque` int(11) DEFAULT NULL,
  `fkmercado` int(11) DEFAULT NULL,
  `cadastro` date DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Despejando dados para a tabela `produto`
--

INSERT INTO `produto` (`idproduto`, `titulo`, `descricao`, `preco`, `precodesconto`, `fkcategoria`, `estoque`, `fkmercado`, `cadastro`) VALUES
(1, 'Achocolatado com Flocos Crocantes Ovomaltine', NULL, '12.00', '9.60', 1, 8, 3, '2021-01-25'),
(2, 'Chocolate Recheado Talento ', NULL, '6.99', '6.01', 1, 1000, 3, '2021-01-25'),
(3, 'Cerveja Lata Eisenbahn ', NULL, '7.00', '7.00', 2, 30, 3, '2021-01-25'),
(4, 'Fardo de Cerveja Stella Artois Long Neck 330ml ', NULL, '22.99', '21.15', 2, 10, 2, '2021-01-25'),
(5, 'Batata Frita Lays Chips 96g', NULL, '8.00', '7.20', 1, 98, 2, '2021-01-25'),
(6, 'Pacote de Bomboms Lacta 50 un. ', NULL, '45.00', '45.00', 1, 20, 2, '2021-01-25');

-- --------------------------------------------------------

--
-- Estrutura para tabela `produto_imagem`
--

CREATE TABLE `produto_imagem` (
  `idimagem` int(11) NOT NULL,
  `fkproduto` int(11) DEFAULT NULL,
  `imagem` varchar(270) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Despejando dados para a tabela `produto_imagem`
--

INSERT INTO `produto_imagem` (`idimagem`, `fkproduto`, `imagem`) VALUES
(1, 1, '07102019201929.jpg'),
(2, 2, '07102019202816.jpg'),
(3, 2, '07102019202855.jpg'),
(4, 2, '07102019202933.jpg'),
(5, 3, 'eisenbahn.jpg'),
(6, 3, 'eisenbahn2.jpg'),
(7, 4, 'fardo stella.jpg'),
(8, 5, 'lays1.jpg'),
(9, 5, 'lays (1).jpg'),
(10, 6, '04102019144359.jpg'),
(11, 6, '04102019144436.jpg'),
(12, 7, 'pizza 1.jpg'),
(13, 7, 'pizza 2.jpg'),
(14, 7, 'pizza 3.jpg'),
(15, 8, '04102019080031.jpg'),
(16, 9, '04102019085512.jpg');

--
-- Índices de tabelas apagadas
--

--
-- Índices de tabela `carrinho`
--
ALTER TABLE `carrinho`
  ADD PRIMARY KEY (`idcarrinho`),
  ADD KEY `fkproduto` (`fkproduto`),
  ADD KEY `fkcliente` (`fkcliente`);

--
-- Índices de tabela `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`idcategoria`);

--
-- Índices de tabela `categoria_imagem`
--
ALTER TABLE `categoria_imagem`
  ADD PRIMARY KEY (`idimagem`),
  ADD KEY `fkcategoria` (`fkcategoria`);

--
-- Índices de tabela `celular`
--
ALTER TABLE `celular`
  ADD PRIMARY KEY (`idcelular`),
  ADD KEY `fkpessoa` (`fkpessoa`);

--
-- Índices de tabela `cliente`
--
ALTER TABLE `cliente`
  ADD KEY `idcliente` (`idcliente`),
  ADD KEY `fkendereco` (`fkendereco`),
  ADD KEY `fkdcartao` (`fkdcartao`);

--
-- Índices de tabela `dados_cartao`
--
ALTER TABLE `dados_cartao`
  ADD PRIMARY KEY (`iddcartao`);

--
-- Índices de tabela `dados_conta`
--
ALTER TABLE `dados_conta`
  ADD PRIMARY KEY (`iddconta`);

--
-- Índices de tabela `endereco`
--
ALTER TABLE `endereco`
  ADD PRIMARY KEY (`idendereco`);

--
-- Índices de tabela `mercado`
--
ALTER TABLE `mercado`
  ADD KEY `idmercado` (`idmercado`),
  ADD KEY `fkendereco` (`fkendereco`),
  ADD KEY `fkdconta` (`fkdconta`);

--
-- Índices de tabela `pedido`
--
ALTER TABLE `pedido`
  ADD PRIMARY KEY (`idpedido`),
  ADD KEY `fkcliente` (`fkcliente`);

--
-- Índices de tabela `pedido_detalhes`
--
ALTER TABLE `pedido_detalhes`
  ADD PRIMARY KEY (`iddetalhe`),
  ADD KEY `fkpedido` (`fkpedido`),
  ADD KEY `fkproduto` (`fkproduto`);

--
-- Índices de tabela `pesquisas`
--
ALTER TABLE `pesquisas`
  ADD PRIMARY KEY (`idpesquisa`),
  ADD KEY `fkcliente` (`fkcliente`);

--
-- Índices de tabela `pessoa`
--
ALTER TABLE `pessoa`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `pessoa_endereco`
--
ALTER TABLE `pessoa_endereco`
  ADD KEY `fkpessoa` (`fkpessoa`),
  ADD KEY `fkendereco` (`fkendereco`);

--
-- Índices de tabela `produto`
--
ALTER TABLE `produto`
  ADD PRIMARY KEY (`idproduto`),
  ADD KEY `fkcategoria` (`fkcategoria`),
  ADD KEY `fkmercado` (`fkmercado`);

--
-- Índices de tabela `produto_imagem`
--
ALTER TABLE `produto_imagem`
  ADD PRIMARY KEY (`idimagem`),
  ADD KEY `fkproduto` (`fkproduto`);

--
-- AUTO_INCREMENT de tabelas apagadas
--

--
-- AUTO_INCREMENT de tabela `carrinho`
--
ALTER TABLE `carrinho`
  MODIFY `idcarrinho` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `categoria`
--
ALTER TABLE `categoria`
  MODIFY `idcategoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `categoria_imagem`
--
ALTER TABLE `categoria_imagem`
  MODIFY `idimagem` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT de tabela `celular`
--
ALTER TABLE `celular`
  MODIFY `idcelular` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `dados_cartao`
--
ALTER TABLE `dados_cartao`
  MODIFY `iddcartao` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `dados_conta`
--
ALTER TABLE `dados_conta`
  MODIFY `iddconta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `endereco`
--
ALTER TABLE `endereco`
  MODIFY `idendereco` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `pedido`
--
ALTER TABLE `pedido`
  MODIFY `idpedido` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `pedido_detalhes`
--
ALTER TABLE `pedido_detalhes`
  MODIFY `iddetalhe` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `pesquisas`
--
ALTER TABLE `pesquisas`
  MODIFY `idpesquisa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `pessoa`
--
ALTER TABLE `pessoa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `produto`
--
ALTER TABLE `produto`
  MODIFY `idproduto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de tabela `produto_imagem`
--
ALTER TABLE `produto_imagem`
  MODIFY `idimagem` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
