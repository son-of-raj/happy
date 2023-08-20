-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 25, 2021 at 07:57 AM
-- Server version: 10.4.16-MariaDB
-- PHP Version: 7.4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `socket_chat`
--

-- --------------------------------------------------------

--
-- Table structure for table `chat_interactions`
--

CREATE TABLE `chat_interactions` (
  `message_id` int(11) NOT NULL,
  `to_id` varchar(255) NOT NULL,
  `from_id` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `ip_address` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `chat_interactions`
--

INSERT INTO `chat_interactions` (`message_id`, `to_id`, `from_id`, `message`, `time`, `ip_address`) VALUES
(51, 'User C', 'User A', 'Hi anu, this is siva', '2021-02-25 04:41:11', '127.0.0.1'),
(52, 'User C', 'User B', 'Hi sakthi this is siva', '2021-02-25 04:40:43', '127.0.0.1'),
(56, 'User B', 'User C', 'Hi siva, this is sakthi', '2021-02-25 04:40:45', '127.0.0.1'),
(57, 'User A', 'User B', 'Hi sakthi, this is anu', '2021-02-25 04:41:29', '127.0.0.1'),
(58, 'User A', 'User C', 'Hi siva this is anu', '2021-02-25 04:41:09', '127.0.0.1'),
(59, 'User B', 'User A', 'Hi sakthi, this is anu', '2021-02-25 04:41:07', '127.0.0.1');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `chat_interactions`
--
ALTER TABLE `chat_interactions`
  ADD PRIMARY KEY (`message_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `chat_interactions`
--
ALTER TABLE `chat_interactions`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=117;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
