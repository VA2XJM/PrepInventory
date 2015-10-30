-- phpMyAdmin SQL Dump
-- version 4.4.13.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 30, 2015 at 02:00 AM
-- Server version: 5.6.25
-- PHP Version: 5.5.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `prepinv`
--
CREATE DATABASE IF NOT EXISTS `prepinv` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `prepinv`;

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE IF NOT EXISTS `inventory` (
  `id` int(16) NOT NULL,
  `item` int(16) NOT NULL,
  `location` varchar(16) NOT NULL,
  `qty` decimal(16,0) NOT NULL DEFAULT '0',
  `qty_max` decimal(16,0) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `inv_categories_1`
--

CREATE TABLE IF NOT EXISTS `inv_categories_1` (
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

CREATE TABLE IF NOT EXISTS `inv_categories_2` (
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

CREATE TABLE IF NOT EXISTS `inv_items` (
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

CREATE TABLE IF NOT EXISTS `inv_locations_1` (
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

CREATE TABLE IF NOT EXISTS `inv_locations_2` (
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

CREATE TABLE IF NOT EXISTS `inv_locations_3` (
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

CREATE TABLE IF NOT EXISTS `inv_locations_4` (
  `id` int(16) NOT NULL,
  `parent` int(16) DEFAULT NULL,
  `name` text COLLATE utf8_bin NOT NULL,
  `description` text COLLATE utf8_bin NOT NULL,
  `icon` varchar(128) COLLATE utf8_bin NOT NULL DEFAULT '../icons/default-48.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `inv_units`
--

CREATE TABLE IF NOT EXISTS `inv_units` (
  `id` int(16) NOT NULL,
  `name` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
-- Indexes for table `inv_units`
--
ALTER TABLE `inv_units`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id` int(16) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `inv_categories_1`
--
ALTER TABLE `inv_categories_1`
  MODIFY `id` int(16) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `inv_categories_2`
--
ALTER TABLE `inv_categories_2`
  MODIFY `id` int(16) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `inv_items`
--
ALTER TABLE `inv_items`
  MODIFY `id` int(16) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `inv_locations_1`
--
ALTER TABLE `inv_locations_1`
  MODIFY `id` int(16) NOT NULL AUTO_INCREMENT;
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
-- AUTO_INCREMENT for table `inv_units`
--
ALTER TABLE `inv_units`
  MODIFY `id` int(16) NOT NULL AUTO_INCREMENT;