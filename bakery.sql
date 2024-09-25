-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 24, 2024 at 05:42 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bakery`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `message` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `name`, `email`, `message`) VALUES
(1, 'Jane Smith', 'jane@example.com', 'I love your cakes!'),
(2, 'brian', 'brian@gmail.com', 'yawa'),
(3, 'brian', 'brian@gmail.com', 'yawa'),
(4, 'brian', 'brian@gmail.com', 'yawa'),
(5, 'brian', 'brian@gmail.com', 'yawa'),
(6, 'brian', 'brian@gmail.com', 'yawa'),
(7, 'brian', 'brian@gmail.com', 'yawa'),
(8, 'brian', 'brian@gmail.com', 'yawa'),
(9, 'brian', 'brian@gmail.com', 'yawa');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `address` text NOT NULL,
  `status` varchar(50) DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `product_id`, `quantity`, `total_price`, `payment_method`, `address`, `status`) VALUES
(12, 7, 1, 2, 52.04, 'online', 'FOR REAL REAL!!', 'pending'),
(13, 7, 1, 3, 78.06, 'paypal', 'hi', 'delivered');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `featured` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `image`, `category_id`, `featured`) VALUES
(1, 'Chocolate Cake', 'A delicious chocolate cake', 26.15, 'chocolate_cake.jpg', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `review` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `user_id`, `product_id`, `rating`, `review`) VALUES
(1, 5, 1, 2, 'uglyy'),
(2, 5, 1, 5, 'soperduer\r\n');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `loyalty_points` int(11) DEFAULT 0,
  `reset_token` varchar(255) DEFAULT NULL,
  `isAdmin` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `loyalty_points`, `reset_token`, `isAdmin`) VALUES
(5, 'brian', 'brian@gmail.com', '$2y$10$lM5v8K94knSl/8uJzsJ5Ue9GJyF8F8VW4OhXpQyrP38O2Rb073dOy', 100, NULL, 0),
(6, 'admin', 'admin@gmail.com', '$2y$10$yf3pCYzsjeIOaUVHYG3g4ey.ujdz0DZGZ4ycFwesMkB6bJHOqx46W', 0, NULL, 1),
(7, 'test', 'test@gmail.com', '$2y$10$ZbKv0zSpNUlHIwRbBeUoVuLsCC10F32PTokzC0eqrrn5i1/zEiZ6G', 52, NULL, 0),
(10, 'RAHH', 'rah@gmail.com', '$2y$10$YMhRBdmTJWymPyno5c68FOteV8CYM7/BguZ8TzNNTRl32B9RFG2oy', 0, NULL, 0),
(11, 'rawr123', 'rawr123@gmail.com', '$2y$10$LKfY3T9Vls7W8wmhju07yeY9igSbtv8wVlPvQAfTW6E6TxR/HzM3u', 0, NULL, 0),
(12, 'bushet1', 'bushet1@gmail.com', '$2y$10$J6d.Y4CS3WY8gDKF8gdyLeZerRTGcgpxJ7jLD3KSKt1MEg7nVhibm', 0, NULL, 0),
(13, 'bushet2', 'bushet2@gmail.com', '$2y$10$xTkkg4kys.kJlAe18a.DeeEYbq1Ku1ZqbipLLWktOWv9mCHh53lcS', 0, NULL, 0),
(14, 'bushet3', 'bushet3@gmail.com', '$2y$10$.NCwr2j.2RvXR7y4DXDRBONv1RhbgLU7jFYKqdg.aoVCsZNZi43uG', 0, NULL, 0),
(15, 'bushet4', 'bushet4@gmail.com', '$2y$10$2J/Y3AI7KVzmEh9.Zth/ueZoeveTGaJxRCTEo4feyT9YB1AfJ/z0u', 0, NULL, 0),
(16, 'bushet5', 'bushet5@gmail.com', '$2y$10$InlYDqjjqAebXMLuVohFC.zI/VtTEGx0DTLKWcrIp0PXJAIPD9gJ.', 0, NULL, 0),
(17, 'bushet6', 'bushet6@gmail.com', '$2y$10$NlhTHgTGk79F05bB9y/rce9uN5i5wKiRKjlBoeMM4yUwayY56G.E6', 0, NULL, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
