-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 16, 2025 at 04:10 AM
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
-- Database: `transportation_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `firstname`, `lastname`, `email`, `password`, `created_at`) VALUES
(1, 'admin1', 'main', 'admin123@gmail.com', '$2y$10$BZy5uCmYhpa1HSyOjuC/iO5coYDYOo0tGWM5RI1VBu.yqaVV5.VaC', '2025-04-19 09:53:23');

-- --------------------------------------------------------

--
-- Table structure for table `drivers`
--

CREATE TABLE `drivers` (
  `id` int(11) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `contact_no` varchar(11) NOT NULL,
  `license_number` varchar(255) NOT NULL,
  `driver_notes` varchar(999) NOT NULL,
  `ratings` varchar(255) NOT NULL,
  `driver_profile` varchar(255) NOT NULL,
  `car_seats` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `drivers`
--

INSERT INTO `drivers` (`id`, `firstname`, `lastname`, `email`, `password`, `contact_no`, `license_number`, `driver_notes`, `ratings`, `driver_profile`, `car_seats`, `created_at`) VALUES
(1, 'Ahlan-nour', 'Sencio', 'senciolannour@gmove.com', '$2y$10$coEVGQn3rkWkG1pmJVHKo.F7/jJFdHyulsSOakJhfDGJi7r2wjiPW', '09987654321', 'WMSU0001', 'No stress, no rush just a chill ride through Zamboanga. Sit back, relax, and let’s GoMove!', '5.0★', '', '4', '2025-04-28 14:43:26'),
(2, 'Alsamhel', 'Jawadil', 'jawadilalsamhel@gomove.com', '$2y$10$RbfzNYivPF8CCAKXpH9mtOamYdqxGJFWK3CUtTHVLbbbZpwoor2di', '09976543212', 'WMSU0002', 'Ready when you are! I’m your driver today, and we’ve got a smooth trip ahead. Thanks for choosing GoMove!', '4.8★', '', '4', '2025-04-28 14:47:28'),
(3, 'Ar-Rauf', 'Imar', 'imararrauf@gomove.com', '$2y$10$mNej5oTtpHDcjy7s0smm2ead5x4SCA86iQPiRQQNXW1ybbH/Wy3H.', '09965432123', 'WMSU0003', 'Hey passenger! Don’t worry about a thing—I got you covered. Let’s enjoy this ride through Zamboanga.', '4.5★', '../assets/images/rauf.jpg', '4', '2025-04-28 14:51:04'),
(4, 'Nathaniel', 'Amarille', 'amarillenathaniel@gomove.com', '$2y$10$WAeKbAOTNg5UJ4YxwB54T.GBE9a0Gk0ShHm2/uzxWJYqhwE6intXi', '09954321234', 'WMSU0004', 'Yo! Thanks for booking with GoMove. You relax, I’ll handle the traffic.  Let’s make this a good ride!', '4.9★', '../assets/images/nash.jpg', '4', '2025-04-28 14:52:34'),
(5, 'Aldrich', 'Zosobrado', 'zosobradoaldrich@gomove.com', '$2y$10$nSWe7X80ZVVQzemlnlRpVuv7J738274hQAnFU7tLfHjh3auRYNvKW', '09943212345', 'WMSU0005', 'Let’s GoMove!  I’m your driver today—Zamboanga’s roads don’t stand a chance. Let’s get moving!', '4.6★', '../assets/images/aldrich.jpg', '4', '2025-04-28 14:54:05'),
(6, 'Erzhad Dominic', 'Lutian', 'lutianerzhad@gmail.com', '$2y$10$0udHxO3k6n1BIqlM8eutxevgWGl77rp3e5GY01E4gyj09bW2u.nUq', '09932123456', 'WMSU0006', 'Hi! I’ve got your ride. Sit back, relax, and I’ll take care of the rest.', '4.7★', '../assets/images/erzhad.jpg', '4', '2025-04-28 15:01:53'),
(7, 'Aldrian', 'Sahid', 'sahidaldrian@gomove.com', '$2y$10$8kn0CIC40UdmgIVhjlHknuv0vmArQ5KHV.iuYxuwW446dsdq8tdg.', '09921098765', 'WMSU0007', 'Hello and welcome aboard. I know these Zamboanga streets well, and I’m here to make sure your trip is comfortable and hassle-free.', '4.9★', '', '4', '2025-04-28 15:02:10'),
(8, 'Jenson', 'Canones', 'canonesjenson@gomove.com', '$2y$10$uROm9/tla1UiXx5L0raMEOsMVW8cZJB2lzPdvMzXKeMzIKUYZYola', '09910987654', 'WMSU0008', 'Your driver’s here. Don’t worry about a thing—I’ll get you where you need to be, nice and easy.', '4.6★', '../assets/images/jenson.jpg', '4', '2025-04-28 15:05:28'),
(9, 'Justine', 'Toong', 'toongjustine@gomove.com', '$2y$10$LF06UVNo/56H9JHJhN3aLeIQxd9PWB6nvhCjbZkTOY5YNBpGgju7K', '09909876543', 'WMSU0009', 'Sit back, relax, and enjoy the ride', '5.0★', '../assets/images/cipher.jpg', '4', '2025-04-28 15:07:08'),
(10, 'Adolf', 'Hitler', 'hitleradolf@gomove.com', '$2y$10$t.CWvE6VGtdAvmaitV6T/ONobS4J5f/Naml8r7jUQw5Pr/0QzpTlO', '09998765432', 'WMSU0010', 'Hail🙋', '5.0★', '../assets/images/Adolf-Hitler-1933.webp', '4', '2025-04-28 15:09:58');

-- --------------------------------------------------------

--
-- Table structure for table `ratings`
--

CREATE TABLE `ratings` (
  `ratings_id` int(11) NOT NULL,
  `passenger` int(11) NOT NULL,
  `driver` int(11) NOT NULL,
  `ratings` decimal(10,1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ratings`
--

INSERT INTO `ratings` (`ratings_id`, `passenger`, `driver`, `ratings`) VALUES
(1, 1, 10, 4.0);

-- --------------------------------------------------------

--
-- Table structure for table `rides`
--

CREATE TABLE `rides` (
  `ride_id` int(11) NOT NULL,
  `passenger` int(11) NOT NULL,
  `driver` int(11) NOT NULL,
  `location` varchar(255) NOT NULL,
  `destination` varchar(255) NOT NULL,
  `pickup_time` varchar(255) NOT NULL,
  `user_contact` varchar(11) NOT NULL,
  `amount` varchar(255) NOT NULL,
  `status` enum('Completed','Pending','Active','Cancelled') DEFAULT NULL,
  `user_notified` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `completed_at` datetime DEFAULT NULL,
  `payment_confirmed` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rides`
--

INSERT INTO `rides` (`ride_id`, `passenger`, `driver`, `location`, `destination`, `pickup_time`, `user_contact`, `amount`, `status`, `user_notified`, `created_at`, `updated_at`, `completed_at`, `payment_confirmed`) VALUES
(1, 1, 10, 'Western Mindanao State University', 'Zamboanga Polytechnic State University', '30m', '09933068464', '40', 'Completed', 1, '2025-05-13 12:26:11', '2025-05-13 12:27:24', '2025-05-13 12:27:15', 1);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `settings_id` int(11) NOT NULL,
  `base_fare` decimal(10,2) NOT NULL,
  `per_km_rate` decimal(10,2) NOT NULL,
  `driver_commission` int(11) NOT NULL,
  `driver_quota` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`settings_id`, `base_fare`, `per_km_rate`, `driver_commission`, `driver_quota`) VALUES
(1, 40.00, 15.00, 80, 10);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_profile` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstname`, `lastname`, `email`, `password`, `user_profile`, `created_at`, `updated_at`) VALUES
(1, 'Justine', 'Toong', 'programmerjustine@gmail.com', '$2y$10$n3wzad1CtSuiVqTWq0G4Ke4SaHA3pCPhZtVjq7R1qx3xDmt/Rg/1y', '../assets/images/cipher.jpg', '2025-05-13 04:25:06', '2025-05-16 00:51:44');

--
-- Triggers `users`
--
DELIMITER $$
CREATE TRIGGER `updated_at_trigger` BEFORE INSERT ON `users` FOR EACH ROW BEGIN
    SET NEW.updated_at = CURRENT_TIMESTAMP;
END
$$
DELIMITER ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `drivers`
--
ALTER TABLE `drivers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `ratings`
--
ALTER TABLE `ratings`
  ADD PRIMARY KEY (`ratings_id`),
  ADD KEY `passenger` (`passenger`),
  ADD KEY `driver` (`driver`);

--
-- Indexes for table `rides`
--
ALTER TABLE `rides`
  ADD PRIMARY KEY (`ride_id`),
  ADD KEY `passenger` (`passenger`),
  ADD KEY `driver` (`driver`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`settings_id`);

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
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `drivers`
--
ALTER TABLE `drivers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `ratings`
--
ALTER TABLE `ratings`
  MODIFY `ratings_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `rides`
--
ALTER TABLE `rides`
  MODIFY `ride_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `settings_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `ratings`
--
ALTER TABLE `ratings`
  ADD CONSTRAINT `ratings_ibfk_1` FOREIGN KEY (`passenger`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ratings_ibfk_2` FOREIGN KEY (`driver`) REFERENCES `drivers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `rides`
--
ALTER TABLE `rides`
  ADD CONSTRAINT `rides_ibfk_1` FOREIGN KEY (`passenger`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `rides_ibfk_2` FOREIGN KEY (`driver`) REFERENCES `drivers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
