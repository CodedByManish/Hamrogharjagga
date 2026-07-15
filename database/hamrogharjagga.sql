-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jul 15, 2026 at 04:00 PM
-- Server version: 12.2.2-MariaDB
-- PHP Version: 8.3.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hamrogharjagga`
--

-- --------------------------------------------------------

--
-- Table structure for table `otps`
--

CREATE TABLE `otps` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `code` varchar(6) NOT NULL,
  `purpose` enum('email_verification','password_reset') NOT NULL,
  `expires_at` timestamp NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `properties`
--

CREATE TABLE `properties` (
  `id` int(11) NOT NULL,
  `seller_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `price` int(11) NOT NULL,
  `municipality` varchar(100) NOT NULL,
  `district` varchar(100) NOT NULL,
  `province` varchar(100) NOT NULL,
  `size` int(11) NOT NULL,
  `type` varchar(50) NOT NULL,
  `category` varchar(50) NOT NULL,
  `image` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `contact_name` varchar(255) NOT NULL,
  `contact_phone` varchar(20) NOT NULL,
  `contact_email` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `property_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` varchar(50) NOT NULL,
  `transaction_id` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `unlocked_properties`
--

CREATE TABLE `unlocked_properties` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `property_id` int(11) NOT NULL,
  `unlocked_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('buyer','seller') NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `is_active`, `email_verified_at`, `created_at`) VALUES
(1, 'Manish Kafle', 'manish@example.com', '$2y$10$MpH2R7i6U4xMRPSh4InLXueNMxos5pECMEf93Yob2vTV2MA8QKis2', 'buyer', 1, NULL, '2026-07-14 17:11:04'),
(2, 'Manish Kafle', 'manish03@example.com', '$2y$10$tbGqZD60vWqsYF2Q5pjxcu6PdPSMrT.HRwfor4zdV0EoKjxQLfXvW', 'buyer', 1, NULL, '2026-07-14 17:11:49');

-- --------------------------------------------------------

--
-- Table structure for table `user_tokens`
--

CREATE TABLE `user_tokens` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token_hash` varchar(64) NOT NULL,
  `expires_at` timestamp NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `property_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `otps`
--
ALTER TABLE `otps`
  ADD PRIMARY KEY (`id`),
  ADD KEY `email` (`email`,`code`);

--
-- Indexes for table `properties`
--
ALTER TABLE `properties`
  ADD PRIMARY KEY (`id`),
  ADD KEY `seller_id` (`seller_id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `property_id` (`property_id`);

--
-- Indexes for table `unlocked_properties`
--
ALTER TABLE `unlocked_properties`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`property_id`),
  ADD KEY `property_id` (`property_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_tokens`
--
ALTER TABLE `user_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token_hash` (`token_hash`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`property_id`),
  ADD KEY `property_id` (`property_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `otps`
--
ALTER TABLE `otps`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `properties`
--
ALTER TABLE `properties`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `unlocked_properties`
--
ALTER TABLE `unlocked_properties`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user_tokens`
--
ALTER TABLE `user_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `properties`
--
ALTER TABLE `properties`
  ADD CONSTRAINT `1` FOREIGN KEY (`seller_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `2` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `unlocked_properties`
--
ALTER TABLE `unlocked_properties`
  ADD CONSTRAINT `1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `2` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_tokens`
--
ALTER TABLE `user_tokens`
  ADD CONSTRAINT `1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD CONSTRAINT `1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `2` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
