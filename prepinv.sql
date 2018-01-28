-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 28, 2018 at 03:18 AM
-- Server version: 5.7.17
-- PHP Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `prepinv`
--

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `id` int(16) NOT NULL,
  `item` int(16) NOT NULL,
  `location` varchar(16) NOT NULL,
  `qty` decimal(16,2) NOT NULL DEFAULT '0.00',
  `qty_max` decimal(16,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `inv_categories_1`
--

CREATE TABLE `inv_categories_1` (
  `id` int(16) NOT NULL,
  `parent` int(16) DEFAULT NULL,
  `name` text COLLATE utf8_bin NOT NULL,
  `description` text COLLATE utf8_bin NOT NULL,
  `icon` varchar(128) COLLATE utf8_bin NOT NULL DEFAULT '../icons/default-48.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `inv_categories_2`
--

CREATE TABLE `inv_categories_2` (
  `id` int(16) NOT NULL,
  `parent` int(16) DEFAULT NULL,
  `name` text COLLATE utf8_bin NOT NULL,
  `description` text COLLATE utf8_bin NOT NULL,
  `icon` varchar(128) COLLATE utf8_bin NOT NULL DEFAULT '../icons/default-48.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `inv_items`
--

CREATE TABLE `inv_items` (
  `id` int(16) NOT NULL,
  `name` text COLLATE utf8_bin NOT NULL,
  `description` text COLLATE utf8_bin NOT NULL,
  `keywords` text COLLATE utf8_bin NOT NULL,
  `icon` varchar(128) COLLATE utf8_bin NOT NULL DEFAULT 'fa-square-o',
  `cat` varchar(16) COLLATE utf8_bin NOT NULL,
  `unit` int(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `inv_locations_1`
--

CREATE TABLE `inv_locations_1` (
  `id` int(16) NOT NULL,
  `parent` int(16) DEFAULT NULL,
  `name` text COLLATE utf8_bin NOT NULL,
  `description` text COLLATE utf8_bin NOT NULL,
  `icon` varchar(128) COLLATE utf8_bin NOT NULL DEFAULT '../icons/default-48.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `inv_locations_2`
--

CREATE TABLE `inv_locations_2` (
  `id` int(16) NOT NULL,
  `parent` int(16) DEFAULT NULL,
  `name` text COLLATE utf8_bin NOT NULL,
  `description` text COLLATE utf8_bin NOT NULL,
  `icon` varchar(128) COLLATE utf8_bin NOT NULL DEFAULT '../icons/default-48.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `inv_locations_3`
--

CREATE TABLE `inv_locations_3` (
  `id` int(16) NOT NULL,
  `parent` int(16) DEFAULT NULL,
  `name` text COLLATE utf8_bin NOT NULL,
  `description` text COLLATE utf8_bin NOT NULL,
  `icon` varchar(128) COLLATE utf8_bin NOT NULL DEFAULT '../icons/default-48.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `inv_locations_4`
--

CREATE TABLE `inv_locations_4` (
  `id` int(16) NOT NULL,
  `parent` int(16) DEFAULT NULL,
  `name` text COLLATE utf8_bin NOT NULL,
  `description` text COLLATE utf8_bin NOT NULL,
  `icon` varchar(128) COLLATE utf8_bin NOT NULL DEFAULT '../icons/default-48.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `inv_log`
--

CREATE TABLE `inv_log` (
  `id` int(32) NOT NULL,
  `item` int(16) NOT NULL,
  `location` varchar(16) NOT NULL,
  `action` varchar(8) NOT NULL DEFAULT '=',
  `qty` decimal(16,2) NOT NULL DEFAULT '0.00',
  `user` varchar(64) NOT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `inv_units`
--

CREATE TABLE `inv_units` (
  `id` int(16) NOT NULL,
  `name` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `nid` int(24) NOT NULL,
  `uid` int(16) DEFAULT NULL,
  `text` text,
  `time` int(16) NOT NULL DEFAULT '0',
  `readed` int(1) NOT NULL DEFAULT '0',
  `deleted` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `reloading_batches`
--

CREATE TABLE `reloading_batches` (
  `id` int(16) NOT NULL,
  `caliber` int(16) NOT NULL,
  `data` int(16) NOT NULL,
  `lot` int(16) DEFAULT NULL,
  `powder_charge` decimal(5,3) NOT NULL,
  `trim` int(1) DEFAULT '0',
  `test_grouping` decimal(10,3) DEFAULT NULL,
  `test_grouping_unit` int(16) DEFAULT NULL,
  `test_distance` int(5) DEFAULT NULL,
  `test_distance_unit` int(16) DEFAULT NULL,
  `test_result` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `reloading_calibers`
--

CREATE TABLE `reloading_calibers` (
  `id` int(16) NOT NULL,
  `name` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `reloading_data`
--

CREATE TABLE `reloading_data` (
  `id` int(16) NOT NULL,
  `caliber` int(16) NOT NULL,
  `source` text NOT NULL,
  `bullet` int(24) NOT NULL,
  `primer` int(24) NOT NULL,
  `powder` int(24) NOT NULL,
  `powder_min` decimal(5,3) NOT NULL,
  `powder_max` decimal(5,3) NOT NULL,
  `oal_max` decimal(5,3) NOT NULL,
  `case_length_max` decimal(5,3) NOT NULL,
  `case_length_trimto` decimal(5,3) NOT NULL,
  `len_unit` int(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `reloading_shell_lots`
--

CREATE TABLE `reloading_shell_lots` (
  `id` int(16) NOT NULL,
  `caliber` int(16) NOT NULL,
  `trim` int(2) NOT NULL DEFAULT '0',
  `trim_max` int(2) NOT NULL DEFAULT '5',
  `reload` int(2) NOT NULL DEFAULT '0',
  `reload_max` int(2) NOT NULL DEFAULT '0',
  `qty` decimal(6,2) NOT NULL DEFAULT '1.00',
  `discarded` int(1) NOT NULL DEFAULT '0',
  `details` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `uid` int(24) NOT NULL,
  `username` varchar(128) NOT NULL,
  `password` varchar(164) NOT NULL,
  `email` varchar(164) NOT NULL,
  `name_first` varchar(128) DEFAULT NULL,
  `name_last` varchar(128) DEFAULT NULL,
  `location` varchar(128) DEFAULT NULL,
  `rating` int(3) NOT NULL DEFAULT '0',
  `role` varchar(24) NOT NULL DEFAULT 'user',
  `last_activity` int(16) NOT NULL DEFAULT '0',
  `last_activity_note` varchar(128) DEFAULT NULL,
  `disabled` int(1) NOT NULL DEFAULT '0',
  `deleted` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`uid`, `username`, `password`, `email`, `name_first`, `name_last`, `location`, `rating`, `role`, `last_activity`, `last_activity_note`, `disabled`, `deleted`) VALUES
(1, 'admin', 'password', 'test@sample.com', NULL, NULL, NULL, 0, 'admin', 1517109005, '/PrepInventory/pages/login.php', 2, 2),
(2, 'Test', '8b398839', 'test@test.com', NULL, NULL, NULL, 0, 'user', 0, NULL, 0, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inv_categories_1`
--
ALTER TABLE `inv_categories_1`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inv_categories_2`
--
ALTER TABLE `inv_categories_2`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inv_items`
--
ALTER TABLE `inv_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inv_locations_1`
--
ALTER TABLE `inv_locations_1`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inv_locations_2`
--
ALTER TABLE `inv_locations_2`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inv_locations_3`
--
ALTER TABLE `inv_locations_3`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inv_locations_4`
--
ALTER TABLE `inv_locations_4`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inv_log`
--
ALTER TABLE `inv_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inv_units`
--
ALTER TABLE `inv_units`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`nid`);

--
-- Indexes for table `reloading_batches`
--
ALTER TABLE `reloading_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reloading_calibers`
--
ALTER TABLE `reloading_calibers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reloading_data`
--
ALTER TABLE `reloading_data`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reloading_shell_lots`
--
ALTER TABLE `reloading_shell_lots`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`uid`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id` int(16) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `inv_categories_1`
--
ALTER TABLE `inv_categories_1`
  MODIFY `id` int(16) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `inv_categories_2`
--
ALTER TABLE `inv_categories_2`
  MODIFY `id` int(16) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `inv_items`
--
ALTER TABLE `inv_items`
  MODIFY `id` int(16) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `inv_locations_1`
--
ALTER TABLE `inv_locations_1`
  MODIFY `id` int(16) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `inv_locations_2`
--
ALTER TABLE `inv_locations_2`
  MODIFY `id` int(16) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `inv_locations_3`
--
ALTER TABLE `inv_locations_3`
  MODIFY `id` int(16) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `inv_locations_4`
--
ALTER TABLE `inv_locations_4`
  MODIFY `id` int(16) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `inv_log`
--
ALTER TABLE `inv_log`
  MODIFY `id` int(32) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `inv_units`
--
ALTER TABLE `inv_units`
  MODIFY `id` int(16) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `nid` int(24) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `reloading_batches`
--
ALTER TABLE `reloading_batches`
  MODIFY `id` int(16) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `reloading_calibers`
--
ALTER TABLE `reloading_calibers`
  MODIFY `id` int(16) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `reloading_data`
--
ALTER TABLE `reloading_data`
  MODIFY `id` int(16) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `reloading_shell_lots`
--
ALTER TABLE `reloading_shell_lots`
  MODIFY `id` int(16) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `uid` int(24) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
