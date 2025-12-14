-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 14, 2025 at 06:45 AM
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
-- Database: `event_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `event_date` date NOT NULL,
  `location` varchar(150) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `title`, `description`, `event_date`, `location`, `created_by`, `created_at`) VALUES
(2, 'Kickoff Day', 'Get to know each other.', '2025-11-21', 'RP Tumba', 3, '2025-11-20 09:21:42'),
(3, 'Tech Meet up', 'Monthly tech discussion.', '2025-11-23', 'NewYork', 3, '2025-11-20 09:25:00'),
(4, 'Tech Meeting', 'At this event, we will see the impact of AI on our daily lives.', '2025-11-23', 'RP Tumba', 4, '2025-11-22 09:50:38'),
(5, 'Tech Meet up', 'We will get know each other', '2025-11-30', 'kigali', 5, '2025-11-22 18:18:48');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `created_at`) VALUES
(1, 'dosite_cyiza', 'dositecyiza@gmail.com', '$2y$10$Cl/ebVUOVAVxfWkA2M6x2OH70YbjliwfRApHrQ31ePx90AmKiq56G', '2025-11-18 16:32:08'),
(2, 'Cyiza', 'cyiza@gmail.com', '$2y$10$E0hhM8ULVHgDkUOMh/xlb.kh2bb8EFWAxGKXV.8nzqLRhfinQAarK', '2025-11-20 08:45:32'),
(3, 'lucky', 'lucky@gmail.com', '$2y$10$6J/JJlJfg/SBLKw6jqkZMOiK5Dt2rvJxpsqbGs1oyFadHugZjs6fm', '2025-11-20 08:50:43'),
(4, 'Dosite', 'dosite@gmail.com', '$2y$10$3YdFjG8kHaP1K6NrzvqAmOq8.gyBgmxUZfhI/Godxsn5c6dYSK9QO', '2025-11-22 09:46:48'),
(5, 'Emanuel', 'emmanuel40@gmail.com', '$2y$10$Ftfhnxi498lr6q0dwz4uPehLbtRNXYwrA5ZHXLmdfOYq2JbXWOGOi', '2025-11-22 18:17:21'),
(6, 'Iradukunda dosite', 'iradukunda@gmail.com', '$2y$10$.9TYDayqSaLs42F96FwQXO6LJCbz9JsjA6wdAUivVNYFu3PGrQoDK', '2025-12-02 16:08:22');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
