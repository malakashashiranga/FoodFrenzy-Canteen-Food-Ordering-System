-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 29, 2024 at 08:14 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `foodfrenzy`
--

-- --------------------------------------------------------

--
-- Table structure for table `active_sessions`
--

CREATE TABLE `active_sessions` (
  `user_id` varchar(7) NOT NULL,
  `last_active_session_id` varchar(512) NOT NULL,
  `created_date` date DEFAULT NULL,
  `created_time` time DEFAULT NULL,
  `last_response_date` date DEFAULT NULL,
  `last_response_time` time DEFAULT NULL,
  `activeness` varchar(20) DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cart_no` int(11) NOT NULL,
  `user_id` varchar(7) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `status` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cart_foods`
--

CREATE TABLE `cart_foods` (
  `number` int(11) NOT NULL,
  `cart_no` int(11) DEFAULT NULL,
  `food_number` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contact_forms`
--

CREATE TABLE `contact_forms` (
  `number` int(11) NOT NULL,
  `user_id` varchar(7) DEFAULT NULL,
  `name` varchar(150) NOT NULL,
  `sender_email` varchar(100) NOT NULL,
  `sender_phone_number` varchar(15) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `recieved_date` date DEFAULT NULL,
  `recieved_time` time DEFAULT NULL,
  `reply` text DEFAULT 'no_reply',
  `replyed_date` date DEFAULT NULL,
  `replyed_time` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `deleted_users`
--

CREATE TABLE `deleted_users` (
  `user_id` varchar(7) NOT NULL,
  `email` varchar(100) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `mobile_number` int(10) NOT NULL,
  `address` varchar(150) NOT NULL,
  `registered_date` date NOT NULL,
  `registered_time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `finished_orders`
--

CREATE TABLE `finished_orders` (
  `number` int(11) NOT NULL,
  `order_id` varchar(10) DEFAULT NULL,
  `finished_date` date DEFAULT NULL,
  `finished_time` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `foods`
--

CREATE TABLE `foods` (
  `food_number` int(11) NOT NULL,
  `food_name` varchar(255) DEFAULT NULL,
  `discount_price` decimal(10,2) NOT NULL,
  `non_discount_price` decimal(10,2) NOT NULL,
  `category` enum('main_dish','short_eat','dessert','drink') NOT NULL,
  `availability` enum('available','unavailable','deleted') NOT NULL,
  `photo_path` varchar(255) DEFAULT NULL,
  `details` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` varchar(10) NOT NULL,
  `cart_no` int(11) DEFAULT NULL,
  `ordered_date` date DEFAULT NULL,
  `ordered_time` time DEFAULT NULL,
  `pay_method` varchar(20) DEFAULT NULL,
  `state` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` varchar(7) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` text DEFAULT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `mobile_number` int(10) DEFAULT NULL,
  `address` varchar(150) DEFAULT NULL,
  `user_type` varchar(10) DEFAULT 'customer',
  `registered_date` date DEFAULT NULL,
  `registered_time` time DEFAULT NULL,
  `photo_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users_last_login`
--

CREATE TABLE `users_last_login` (
  `user_id` varchar(7) NOT NULL,
  `auth_token` varchar(512) NOT NULL,
  `last_sign_date` date DEFAULT NULL,
  `last_sign_time` time DEFAULT NULL,
  `token_expiration_date` date DEFAULT NULL,
  `token_expiration_time` time DEFAULT NULL,
  `token_validity` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_wallets`
--

CREATE TABLE `user_wallets` (
  `wallet_id` int(11) NOT NULL,
  `user_id` varchar(7) DEFAULT NULL,
  `current_balance` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `verify_codes`
--

CREATE TABLE `verify_codes` (
  `number` int(11) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `user_id` varchar(7) DEFAULT NULL,
  `otp` int(8) NOT NULL,
  `date` date DEFAULT NULL,
  `time` time DEFAULT NULL,
  `status` varchar(10) DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wallet_records`
--

CREATE TABLE `wallet_records` (
  `number` int(11) NOT NULL,
  `wallet_id` int(11) DEFAULT NULL,
  `payment` decimal(10,2) DEFAULT NULL,
  `payment_method` enum('expired_order_returns','deposits','withdraw') DEFAULT NULL,
  `past_balance` decimal(10,2) DEFAULT NULL,
  `balance` decimal(10,2) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `time` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `active_sessions`
--
ALTER TABLE `active_sessions`
  ADD PRIMARY KEY (`user_id`,`last_active_session_id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_no`),
  ADD KEY `fk_cart_user` (`user_id`);

--
-- Indexes for table `cart_foods`
--
ALTER TABLE `cart_foods`
  ADD PRIMARY KEY (`number`),
  ADD KEY `fk_cart_food` (`food_number`),
  ADD KEY `fk_cart_no` (`cart_no`);

--
-- Indexes for table `contact_forms`
--
ALTER TABLE `contact_forms`
  ADD PRIMARY KEY (`number`),
  ADD KEY `fk_user_contact_forms` (`user_id`);

--
-- Indexes for table `deleted_users`
--
ALTER TABLE `deleted_users`
  ADD PRIMARY KEY (`user_id`,`registered_date`,`registered_time`) USING BTREE;

--
-- Indexes for table `finished_orders`
--
ALTER TABLE `finished_orders`
  ADD PRIMARY KEY (`number`),
  ADD UNIQUE KEY `order_id` (`order_id`);

--
-- Indexes for table `foods`
--
ALTER TABLE `foods`
  ADD PRIMARY KEY (`food_number`),
  ADD UNIQUE KEY `food_name` (`food_name`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD UNIQUE KEY `cart_no` (`cart_no`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `users_last_login`
--
ALTER TABLE `users_last_login`
  ADD PRIMARY KEY (`user_id`,`auth_token`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `user_wallets`
--
ALTER TABLE `user_wallets`
  ADD PRIMARY KEY (`wallet_id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `verify_codes`
--
ALTER TABLE `verify_codes`
  ADD PRIMARY KEY (`number`),
  ADD KEY `fk_verify_codes` (`user_id`);

--
-- Indexes for table `wallet_records`
--
ALTER TABLE `wallet_records`
  ADD PRIMARY KEY (`number`),
  ADD KEY `fk_wallet_records_user_wallet` (`wallet_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `cart_foods`
--
ALTER TABLE `cart_foods`
  MODIFY `number` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=106;

--
-- AUTO_INCREMENT for table `contact_forms`
--
ALTER TABLE `contact_forms`
  MODIFY `number` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;

--
-- AUTO_INCREMENT for table `finished_orders`
--
ALTER TABLE `finished_orders`
  MODIFY `number` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `foods`
--
ALTER TABLE `foods`
  MODIFY `food_number` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT for table `user_wallets`
--
ALTER TABLE `user_wallets`
  MODIFY `wallet_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `verify_codes`
--
ALTER TABLE `verify_codes`
  MODIFY `number` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `wallet_records`
--
ALTER TABLE `wallet_records`
  MODIFY `number` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `active_sessions`
--
ALTER TABLE `active_sessions`
  ADD CONSTRAINT `fk_active_sessions` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `fk_cart_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `cart_foods`
--
ALTER TABLE `cart_foods`
  ADD CONSTRAINT `fk_cart_food` FOREIGN KEY (`food_number`) REFERENCES `foods` (`food_number`),
  ADD CONSTRAINT `fk_cart_no` FOREIGN KEY (`cart_no`) REFERENCES `cart` (`cart_no`) ON DELETE CASCADE;

--
-- Constraints for table `contact_forms`
--
ALTER TABLE `contact_forms`
  ADD CONSTRAINT `fk_user_contact_forms` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `finished_orders`
--
ALTER TABLE `finished_orders`
  ADD CONSTRAINT `fk_finished_orders_orders` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_cart` FOREIGN KEY (`cart_no`) REFERENCES `cart` (`cart_no`) ON DELETE CASCADE;

--
-- Constraints for table `users_last_login`
--
ALTER TABLE `users_last_login`
  ADD CONSTRAINT `fk_users_last_login` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `user_wallets`
--
ALTER TABLE `user_wallets`
  ADD CONSTRAINT `fk_user_wallets_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `verify_codes`
--
ALTER TABLE `verify_codes`
  ADD CONSTRAINT `fk_verify_codes` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `wallet_records`
--
ALTER TABLE `wallet_records`
  ADD CONSTRAINT `fk_wallet_records_user_wallet` FOREIGN KEY (`wallet_id`) REFERENCES `user_wallets` (`wallet_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
