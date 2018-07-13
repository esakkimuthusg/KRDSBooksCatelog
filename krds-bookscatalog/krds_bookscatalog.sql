-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 12, 2018 at 09:37 PM
-- Server version: 10.1.29-MariaDB
-- PHP Version: 7.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `krds_bookscatalog`
--

-- --------------------------------------------------------

--
-- Table structure for table `krds_books`
--

CREATE TABLE `krds_books` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `isbn` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `coverImage` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `krds_books`
--

INSERT INTO `krds_books` (`id`, `title`, `author`, `isbn`, `description`, `coverImage`) VALUES
(7, 'Book 1', 'Book1Author', '12345sdf', 'Book1 Desc', '1082916271635a2273-a764-4a0b-9c83-15bf310d002a-original.jpeg'),
(10, 'Book4', 'TEst', '2323423', 'TEst', '638500162635a2273-a764-4a0b-9c83-15bf310d002a-original.jpeg'),
(11, 'Book4', 'sasdas', '21321312', 'asdasd', '901218029635a2273-a764-4a0b-9c83-15bf310d002a-original.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `krds_books_users`
--

CREATE TABLE `krds_books_users` (
  `userId` int(11) NOT NULL,
  `bookId` int(11) NOT NULL,
  `personal` enum('1','0') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `krds_books_users`
--

INSERT INTO `krds_books_users` (`userId`, `bookId`, `personal`) VALUES
(1, 7, '1'),
(2, 11, '0');

-- --------------------------------------------------------

--
-- Table structure for table `krds_users`
--

CREATE TABLE `krds_users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `registerDate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `krds_users`
--

INSERT INTO `krds_users` (`id`, `name`, `username`, `password`, `registerDate`) VALUES
(1, 'User', 'user', 'ee11cbb19052e40b07aac0ca060c23ee', '0000-00-00 00:00:00'),
(2, 'User 2', 'user2', '7e58d63b60197ceb55a1c487989a3720', '0000-00-00 00:00:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `krds_books`
--
ALTER TABLE `krds_books`
  ADD PRIMARY KEY (`id`),
  ADD KEY `title_index` (`title`),
  ADD KEY `isbn_index` (`isbn`);

--
-- Indexes for table `krds_books_users`
--
ALTER TABLE `krds_books_users`
  ADD KEY `FK_userId` (`userId`),
  ADD KEY `FK_bookId` (`bookId`);

--
-- Indexes for table `krds_users`
--
ALTER TABLE `krds_users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `krds_books`
--
ALTER TABLE `krds_books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `krds_users`
--
ALTER TABLE `krds_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `krds_books_users`
--
ALTER TABLE `krds_books_users`
  ADD CONSTRAINT `FK_bookId` FOREIGN KEY (`bookId`) REFERENCES `krds_books` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_userId` FOREIGN KEY (`userId`) REFERENCES `krds_users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
