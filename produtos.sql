-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Tempo de geração: 24/10/2018 às 00:08
-- Versão do servidor: 10.2.17-MariaDB
-- Versão do PHP: 7.1.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `u831942291_prod`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `produtos`
--

CREATE TABLE `produtos` (
  `id` int(11) NOT NULL,
  `nome` varchar(220) NOT NULL,
  `foto` varchar(220) DEFAULT NULL,
  `descricao` varchar(520) NOT NULL,
  `altura_produto` double NOT NULL,
  `largura_produto` double NOT NULL,
  `profundidade_produto` double NOT NULL,
  `codigo_produto` varchar(220) NOT NULL,
  `valor_compra` double DEFAULT NULL,
  `valor_venda` double NOT NULL,
  `disponivel_estoque` int(11) NOT NULL,
  `min_estoque` int(11) DEFAULT NULL,
  `max_estoque` int(11) DEFAULT NULL,
  `cadastro` int(11) NOT NULL,
  `edito` int(11) DEFAULT NULL,
  `categorias_produto_id` int(11) NOT NULL,
  `fornecedore_id` int(11) DEFAULT NULL,
  `situacao_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Fazendo dump de dados para tabela `produtos`
--

INSERT INTO `produtos` (`id`, `nome`, `foto`, `descricao`, `altura_produto`, `largura_produto`, `profundidade_produto`, `codigo_produto`, `valor_compra`, `valor_venda`, `disponivel_estoque`, `min_estoque`, `max_estoque`, `cadastro`, `edito`, `categorias_produto_id`, `fornecedore_id`, `situacao_id`, `created`, `modified`) VALUES
(1, 'Mouse', '008739-1.jpg', 'Mouse vermelho com led', 80, 120, 40, '2222222', 25, 150, 12, 2, 20, 1, 1, 1, 1, 1, '2018-10-22 18:16:18', '2018-10-23 20:48:56'),
(2, 'Refrigerador', 'indice.jpg', 'Refrigerador Electrolux Duplex DC35A 260L - Branco', 0, 0, 0, '2355555', 1500, 2100, 23, 5, 25, 1, 1, 2, 1, 1, '2018-10-22 18:20:13', '2018-10-23 23:59:32'),
(3, 'Kit Parafusadeira', 'kit-parafusadeira-philco-4-8v-ppf01mf-com-maleta-1-26.jpg', 'Kit Parafusadeira Philco Force 4,8V PPF01MF com Maleta - Bivolt', 0, 0, 0, '1111111', 198, 250, 56, 20, 80, 1, NULL, 4, 1, 1, '2018-10-22 18:27:13', NULL),
(4, 'Impressora Multifuncional', 'impressora-multifuncional-com-wifi-ecotank-epson-l4150-1-23.jpg', 'Impressora Multifuncional com WiFi Ecotank Epson L4150 - Bivolt', 0, 0, 0, '2555555', 1200, 1300, 3, 1, 5, 1, 1, 1, 1, 1, '2018-10-22 18:51:25', '2018-10-23 20:48:35'),
(5, 'Fogão', 'fogao-4-bocas-atlas-coliseum-glass-branco-1-32.jpg', 'Fogão 4 Bocas Atlas Coliseum Glass Forno Autolimpante - Branco', 853, 489, 573, '1114984', 300, 573, 5, 1, 5, 1, 1, 2, 1, 1, '2018-10-22 19:09:01', '2018-10-23 23:59:59');

--
-- Índices de tabelas apagadas
--

--
-- Índices de tabela `produtos`
--
ALTER TABLE `produtos`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de tabelas apagadas
--

--
-- AUTO_INCREMENT de tabela `produtos`
--
ALTER TABLE `produtos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
