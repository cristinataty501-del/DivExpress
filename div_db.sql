-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 21-Maio-2026 às 10:44
-- Versão do servidor: 10.4.24-MariaDB
-- versão do PHP: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `div_db`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `cart`
--

CREATE TABLE `cart` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `admin_id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` int(100) NOT NULL,
  `quantity` int(100) NOT NULL,
  `image` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `admin_id`, `name`, `price`, `quantity`, `image`) VALUES
(141, 18, 1, 'sapatos', 15000, 20, 'IMG-20260506-WA0022.jpg'),
(142, 18, 1, 'bones', 5000, 1, 'IMG-20260506-WA0027.jpg'),
(144, 24, 1, 'sapatos', 15000, 1, 'IMG-20260506-WA0022.jpg');

-- --------------------------------------------------------

--
-- Estrutura da tabela `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `descricao` varchar(100) NOT NULL,
  `image` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `message`
--

CREATE TABLE `message` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `admin_id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `number` varchar(12) NOT NULL,
  `message` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `message`
--

INSERT INTO `message` (`id`, `user_id`, `admin_id`, `name`, `email`, `number`, `message`) VALUES
(10, 8, 0, 'Ruth', 'ruth@gmail.com', '943443433', 'Bom dia '),
(12, 18, 0, 'toy', 'toy@gmail.com', '933455667', 'nao consigo ver o comprovativo da minha compra '),
(13, 18, 20, 'toy', 'toy@gmail.com', '933455667', 'nao consigo ver a fatura da minha compra');

-- --------------------------------------------------------

--
-- Estrutura da tabela `orders`
--

CREATE TABLE `orders` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `admin_id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `number` varchar(12) NOT NULL,
  `email` varchar(100) NOT NULL,
  `method` varchar(50) NOT NULL,
  `address` varchar(500) NOT NULL,
  `total_products` varchar(1000) NOT NULL,
  `total_price` int(100) NOT NULL,
  `total_price_admin` int(100) NOT NULL,
  `total_price_vend` int(100) NOT NULL,
  `placed_on` varchar(50) NOT NULL,
  `payment_status` varchar(20) NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `admin_id`, `name`, `number`, `email`, `method`, `address`, `total_products`, `total_price`, `total_price_admin`, `total_price_vend`, `placed_on`, `payment_status`) VALUES
(26, 18, 1, 'toy', '933455678', 'toy@gmail.com', 'pagamento_na_entrega', 'flat no. 1, Luanda, LUANDA, Angola - 123', ', bones (5) ', 2500, 2500, 0, '13-May-2026', 'pending'),
(27, 18, 20, 'Toy', '937975019', 'toy@gmail.com', 'multicaixa_express', 'flat no. 2, Avenida Brasil, Hoji-ya-Henda, Angola - 3', ', Vestido  (1) ', 9999, 2500, 7499, '16-May-2026', 'completed'),
(28, 18, 1, 'Cristina Taty', '945466811', 'cristinataty501@gmail.com', 'pagamento_na_entrega', 'flat no. 101, Luanda, LUANDA, Angola - -1', ', sapatos (2) ', 30000, 30000, 0, '18-May-2026', 'pending'),
(29, 18, 1, 'toy', '933455678', 'toy@gmail.com', 'transferencia_bancaria', 'flat no. 2, Luanda, LUANDA, Angola - 2', ', iphones (1) ', 120000, 120000, 0, '18-May-2026', 'completed'),
(30, 24, 1, 'Joyce António', '925456357', 'joyce@gmail.com', 'pagamento_na_entrega', 'Luanda, Belas - 2332', ', iphones (1) ', 120000, 120000, 0, '20-May-2026', 'pending');

-- --------------------------------------------------------

--
-- Estrutura da tabela `products`
--

CREATE TABLE `products` (
  `id` int(100) NOT NULL,
  `admin_id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` int(100) NOT NULL,
  `quantity` int(100) NOT NULL,
  `descricao` varchar(100) NOT NULL,
  `category` varchar(50) NOT NULL,
  `image` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `products`
--

INSERT INTO `products` (`id`, `admin_id`, `name`, `price`, `quantity`, `descricao`, `category`, `image`) VALUES
(36, 1, 'sapatos', 15000, 0, 'sapato social', 'calcados', 'IMG-20260506-WA0022.jpg'),
(37, 1, 'iphones', 120000, 0, 'iphone 13 pro max', 'roupas', 'IMG-20260506-WA0032.jpg'),
(38, 1, 'bones', 5000, 0, 'bones', 'acessorios', 'IMG-20260506-WA0027.jpg'),
(39, 20, 'tenis', 15000, 20, 'AirForce', 'calcados', 'IMG-20260506-WA0026.jpg');

-- --------------------------------------------------------

--
-- Estrutura da tabela `selectcart`
--

CREATE TABLE `selectcart` (
  `id` int(100) NOT NULL,
  `cart_id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `admin_id` int(100) NOT NULL,
  `name` varchar(50) NOT NULL,
  `price` int(100) NOT NULL,
  `qty` int(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `selectcart`
--

INSERT INTO `selectcart` (`id`, `cart_id`, `user_id`, `admin_id`, `name`, `price`, `qty`) VALUES
(88, 140, 18, 1, 'iphones', 120000, 50);

-- --------------------------------------------------------

--
-- Estrutura da tabela `users`
--

CREATE TABLE `users` (
  `id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `user_type` varchar(20) NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `user_type`) VALUES
(1, 'Taty', 'taty@gmail.com', '2c9cef39a5cad03bbec590b2ac921e32', 'admin'),
(18, 'toy', 'toy@gmail.com', '870042b211d34201c9d97495432e2a92', 'comp'),
(19, 'dinis', 'dinis@gmail.com', 'afdeab9d4e3cb9452415e811c8527a61', 'vendemp'),
(20, 'afonso', 'afonso@gmail.com', '7c8dee76dae3f9dd1e26d023949b4a92', 'vendf'),
(21, 'Rui Alberto', 'Rui@gmail.com', 'b25013af51cbb47d7e5f5c54b2881fcb', 'vendf'),
(22, 'Alberto', 'alberto@gmail.com', '55c1d2a82dedd08d29dfe9de21195250', 'vendf'),
(23, 'Marcos', 'marcos@gmail.c0m', 'f57c0b7df3bdeb63195cec2f45476eec', 'vendf'),
(24, 'Joyce António', 'joyce@gmail.com', '8af4502bd99613d71fe4c7274c699906', 'comp');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `selectcart`
--
ALTER TABLE `selectcart`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=145;

--
-- AUTO_INCREMENT de tabela `message`
--
ALTER TABLE `message`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de tabela `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de tabela `products`
--
ALTER TABLE `products`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT de tabela `selectcart`
--
ALTER TABLE `selectcart`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=91;

--
-- AUTO_INCREMENT de tabela `users`
--
ALTER TABLE `users`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
