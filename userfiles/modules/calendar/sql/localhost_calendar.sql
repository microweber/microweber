-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 23, 2018 at 07:39 PM
-- Server version: 10.1.30-MariaDB
-- PHP Version: 7.2.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `microweber`
--

-- --------------------------------------------------------

--
-- Table structure for table `localhost_calendar`
--

CREATE TABLE `localhost_calendar` (
  `id` int(10) UNSIGNED NOT NULL,
  `recurrence_type` enum('daily','weekly_on_the_day_name','weekly_on_the_days_names','weekly_on_all_days','every_weekday','monthly_on_the_day_number','monthly_on_the_week_number_day_name','annually_on_the_month_name_day_number','custom') COLLATE utf8_unicode_ci DEFAULT 'custom',
  `recurrence_repeat_type` enum('day','week','month','year') COLLATE utf8_unicode_ci DEFAULT NULL,
  `recurrence_repeat_every` int(11) DEFAULT NULL,
  `recurrence_repeat_on` text COLLATE utf8_unicode_ci NOT NULL,
  `start_date` date DEFAULT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `all_day` int(11) DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `content_id` int(11) DEFAULT NULL,
  `calendar_group_id` int(11) DEFAULT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `image_url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `link_url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `active` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `localhost_calendar`
--

INSERT INTO `localhost_calendar` (`id`, `recurrence_type`, `recurrence_repeat_type`, `recurrence_repeat_every`, `recurrence_repeat_on`, `start_date`, `start_time`, `end_time`, `all_day`, `end_date`, `content_id`, `calendar_group_id`, `title`, `image_url`, `link_url`, `description`) VALUES
(32, 'weekly_on_the_day_name', 'week', 1, '{\"sunday\":\"1\"}', '2018-09-23', '20:30:00', '21:00:00', NULL, '2018-09-23', 0, 0, 'Team meating', '', '', 'Team meating'),
(33, 'annually_on_the_month_name_day_number', 'day', 1, '', '2018-09-22', '14:42:00', '14:42:00', 1, '2018-09-22', 0, 0, 'DEN NA NEZAVISIMOSTTA', '', '', ''),
(34, 'annually_on_the_month_name_day_number', 'day', 1, '', '2019-04-25', '14:54:00', '14:54:00', NULL, '2019-04-25', 0, 0, 'BOJIDAR ROJDEN DEN', '', '', ''),
(36, 'custom', 'day', 4, '', '2018-09-23', '10:00:00', '10:15:00', NULL, '2018-09-23', 0, 0, 'WZEMI SI DUSH', '', '', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `localhost_calendar`
--
ALTER TABLE `localhost_calendar`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `localhost_calendar`
--
ALTER TABLE `localhost_calendar`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
