
-- Database: `hamrogharjagga`
CREATE DATABASE IF NOT EXISTS `hamrogharjagga` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `hamrogharjagga`;
-- --------------------------------------------------------


---- Table structure for table `properties`
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `properties`
--

INSERT INTO `properties` (`id`, `seller_id`, `title`, `price`, `municipality`, `district`, `province`, `size`, `type`, `category`, `image`, `description`, `contact_name`, `contact_phone`, `contact_email`, `created_at`) VALUES
(1, 3, 'Modern 3BHK Apartment', 500000, 'Kathmandu Metropolitan', 'Kathmandu', 'Bagmati Province', 2500, 'Residential Building', 'Building', 'uploads/68a7405d445090.97755163.png', 'Spacious and modern 3BHK apartment located in the heart of Kathmandu. Comes with modular kitchen, 2 balconies, 2 bathrooms, 24/7 security, and parking space. Ideal for families looking for comfort and convenience.', 'Suman Shrestha', '+977-9841234567', 'suman.shrestha@example.com', '2025-08-21 15:50:53'),
(3, 3, 'Prime 10 Aana Land in Bhaisepati', 15000, 'Lalitpur Metropolitan', 'Lalitpur', 'Bagmati Province', 3338, 'Commercial Space', 'Land', 'uploads/68a74746b147a5.23723643.png', 'Rectangular-shaped 10 aana land located in peaceful residential area of Bhaisepati, Lalitpur. Close to main road, easy access to water and electricity. Suitable for building a house or investment purpose. Clean papers and ready to transfer.', 'Ramesh Kharel', '+977-9801234567', 'ramesh.kharel@example.com', '2025-08-21 16:20:22');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `user_id`, `property_id`, `amount`, `status`, `transaction_id`, `created_at`) VALUES
(1, 2, 1, 50.00, '0', 'txn_68a744375fe9b', '2025-08-21 16:07:19');

-- --------------------------------------------------------

--
-- Table structure for table `unlocked_properties`
--

CREATE TABLE `unlocked_properties` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `property_id` int(11) NOT NULL,
  `unlocked_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `unlocked_properties`
--

INSERT INTO `unlocked_properties` (`id`, `user_id`, `property_id`, `unlocked_at`) VALUES
(1, 2, 1, '2025-08-21 16:07:19');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('buyer','seller') DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`) VALUES
(2, 'buyer', 'buyer@gmail.com', '$2y$10$U.OlO9NCiVfgehM9qFj5k.NpU0wLav.ZNukuMldOzH85QzqZu2p5K', 'buyer', '2025-08-21 13:31:54'),
(3, 'seller', 'seller@gmail.com', '$2y$10$6Q0g2T49E6DRuJvgKCQkuOsFBQf2UaCcnnw778M.7SVmsp9U.Nvu2', 'seller', '2025-08-21 13:43:30');

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `property_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `properties`
--
ALTER TABLE `properties`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_seller` (`seller_id`);

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
  ADD UNIQUE KEY `user_property_unique` (`user_id`,`property_id`),
  ADD KEY `property_id` (`property_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_property_unique` (`user_id`,`property_id`),
  ADD KEY `property_id` (`property_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `properties`
--
ALTER TABLE `properties`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `unlocked_properties`
--
ALTER TABLE `unlocked_properties`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `properties`
--
ALTER TABLE `properties`
  ADD CONSTRAINT `fk_seller` FOREIGN KEY (`seller_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transactions_ibfk_2` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `unlocked_properties`
--
ALTER TABLE `unlocked_properties`
  ADD CONSTRAINT `unlocked_properties_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `unlocked_properties_ibfk_2` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD CONSTRAINT `wishlist_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `wishlist_ibfk_2` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
