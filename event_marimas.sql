-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 22, 2024 at 04:01 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `event_marimas`
--

-- --------------------------------------------------------

--
-- Table structure for table `event`
--

CREATE TABLE `event` (
  `no` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `date` date NOT NULL,
  `instansi` varchar(100) NOT NULL,
  `jumlah` int(150) NOT NULL,
  `nomorwa` varchar(100) NOT NULL,
  `umur_minimum` varchar(100) NOT NULL,
  `umur_maksimal` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `event`
--

INSERT INTO `event` (`no`, `name`, `email`, `date`, `instansi`, `jumlah`, `nomorwa`, `umur_minimum`, `umur_maksimal`) VALUES
(55, 'dodi', 'bintorojevan2@gmail.com', '2024-05-21', 'tk 3', 100, '0', '', ''),
(57, 'sari', 'bintorojevan2@gmail.com', '2024-04-16', 'toko bagus', 65, '0812 3456 7891', '', ''),
(59, 'dani', 'bintorojevan2@gmail.com', '2024-04-16', 'toko besar', 11, '1234 5678 9964', '', ''),
(60, 'dimas', 'bintorojevan2@gmail.com', '2024-04-18', 'toko cinta', 100, '0812 3456 7891', '', ''),
(64, 'Didi Santoso', 'bintorojevan2@gmail.com', '2024-05-21', 'universitas indonesia maju kuat sejahtera', 100, '0812 3456 7891', '', ''),
(65, 'Gina Susanti', 'bintorojevan2@gmail.com', '2024-05-21', 'Sd Tadika Mesra Unggul', 120, '0812 3456 7891', '', ''),
(67, 'Diah Indrawati', 'bintorojevan2@gmail.com', '2024-05-22', 'Company A', 111, '0812 3456 7891', '', ''),
(68, 'didi', 'bintorojevan2@gmail.com', '2024-05-23', 'company aa', 111, '0812 3456 7891', '100', '1'),
(69, 'dodi', 'bintorojevan2@gmail.com', '2024-05-24', 'Toko Roti Enak', 112, '0812 3456 7891', '100', '1'),
(70, 'Dudi dermawan', 'bintorojevan2@gmail.com', '2024-05-25', 'Bank Indonesia Maju', 113, '0812 3456 7891', '10', '10'),
(71, 'Gugun Santoso', 'bintorojevan2@gmail.com', '2024-05-25', 'PT MAJU Jaya Mulya', 19, '0812 3456 7891', '7', '31');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `created_at`) VALUES
(1, 'admin', 'marimasfactorytour', '2024-03-27 08:45:45'),
(2, 'user', 'user', '2024-03-27 09:00:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `event`
--
ALTER TABLE `event`
  ADD PRIMARY KEY (`no`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `event`
--
ALTER TABLE `event`
  MODIFY `no` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
