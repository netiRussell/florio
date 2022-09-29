-- phpMyAdmin SQL Dump
-- version 4.9.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Sep 29, 2022 at 10:04 PM
-- Server version: 5.7.26
-- PHP Version: 7.4.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `florio`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT 'New category'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Roses'),
(2, 'Rosemary'),
(3, 'Lily'),
(4, 'Daisy'),
(5, 'Category arbitrary'),
(6, 'Smth else');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(10) UNSIGNED NOT NULL,
  `status` varchar(65) NOT NULL,
  `quantity` int(10) UNSIGNED NOT NULL,
  `delivery_address` text NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `customer_id` int(11) NOT NULL,
  `scheduled_for` varchar(100) NOT NULL,
  `price` double UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `status`, `quantity`, `delivery_address`, `product_id`, `customer_id`, `scheduled_for`, `price`) VALUES
(23, 'Placed', 2, '123', 1, 2, '2022-08-30', 80),
(24, 'Placed', 1, 'ggh', 2, 2, '2022-09-13', 39.99),
(25, 'Placed', 1, '213', 5, 2, '2022-08-30', 42.31),
(26, 'Placed', 1, '23', 6, 2, '2022-08-29', 10),
(27, 'Placed', 2, '33', 1, 2, '2022-08-31', 80),
(28, 'Placed', 3, '1', 5, 2, '2022-09-13', 126.93),
(29, 'In cart', 4, '', 2, 2, '', 159.96),
(30, 'Placed', 1, '1', 6, 2, '2022-08-28', 10),
(31, 'Placed', 2, '213', 6, 2, '2022-08-31', 20),
(32, 'In cart', 1, '', 2, 2, '', 39.99);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT 'New product',
  `thumbnail` text,
  `price` double UNSIGNED DEFAULT NULL,
  `description` mediumtext,
  `category_id` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `thumbnail`, `price`, `description`, `category_id`) VALUES
(1, 'Product 1', 'img/markus-clemens-mibjbNoS1XA-unsplash.jpg', 40, 'Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia. Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginiaampden-Sydney College in Virginia. Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old.', '1,3'),
(2, 'Product 2', 'img/tanalee-youngblood-T-OYshlMvL0-unsplash.jpg', 39.99, 'Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia. Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginiaampden-Sydney College in Virginia. Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old.', '2,3,4'),
(4, 'Product 4, Elite', 'img/markus-clemens-mibjbNoS1XA-unsplash.jpg', 10009300000, 'Some arbitrary description', NULL),
(5, 'Roses special', 'img/markus-clemens-mibjbNoS1XA-unsplash.jpg', 42.31, 'Some arbitrary description long Some arbitrary descriptionSome arbitrary descriptionSome arbitrary descriptionSome arbitrary description', '3,2'),
(6, 'Product 1', 'img/tanalee-youngblood-T-OYshlMvL0-unsplash.jpg', 10, 'Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia. Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginiaampden-Sydney College in Virginia. Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old.', '1,4');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(10) UNSIGNED NOT NULL,
  `role` varchar(15) NOT NULL DEFAULT 'user',
  `username` varchar(15) DEFAULT NULL,
  `password` varchar(90) DEFAULT NULL,
  `name` tinytext,
  `account` double UNSIGNED DEFAULT NULL,
  `orders` mediumtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `role`, `username`, `password`, `name`, `account`, `orders`) VALUES
(2, 'user', 'user_test', '$2y$10$cSoMbOxBUGQuL9CF7m6uOeN8pXOt00gfPQFTSwwqx4qM.F38mw3VW', 'Mark Bowles', 443.84, ''),
(4, 'admin', 'admin_test', '$2y$10$Plb186ECPaASAeVf5JKp6.l/6BpgtybQWDsYO7/x5S9RdyFa2P27G', 'Russell', 10000, ''),
(8, 'user', 'russell', '$2y$10$U/sglH.bXfVKHbJ8kB/xnusQJbG0rP41TepcvycQvxv2zUThNJWxC', 'Russell', 10000, ''),
(9, 'user', 'new_acc', '$2y$10$fBTiZDZ7huEv22w3v.oANey8kWDGLZn5tnu2rt7QD.c1YltbuxvFW', 'New acc', 0, ''),
(11, 'user', '11', '$2y$10$AMrARGHIsy38L7JMJ.u3vObXJmwHL6i6WnWW5nn.9ZwQ5Ku1ykDJK', '1', 0, ''),
(12, 'user', 'test', '$2y$10$hUnISyLt3h9qQENQ9SX/DeAo/wTeZPrl392NW.KcK.VHSpMZYOprq', 'First Last', 0, ''),
(15, 'user', 'test1', '$2y$10$yEsIgVXWa6iEJostbeGGGuMXv6j9R.lXNeX8Jam6A6FPNgh5xhR/a', 'Johny', 0, ''),
(16, 'user', 'test2', '$2y$10$fAlVfM3iIibNRNvKOoBs2.qQiSrS1r0a5LRpNH57A9QkV9Ph/rY.e', 'First Last', 0, '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
