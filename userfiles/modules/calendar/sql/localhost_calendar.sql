-- phpMyAdmin SQL Dump
-- version 4.8.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 28, 2018 at 09:27 AM
-- Server version: 10.1.31-MariaDB
-- PHP Version: 7.2.4

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
  `recurrence_type` enum('daily','weekly_on_the_day_name','weekly_on_the_days_names','weekly_on_all_days','every_weekday','monthly_on_the_day_number','monthly_on_the_week_number_day_name','annually_on_the_month_name_day_number','custom','doesnt_repeat') COLLATE utf8_unicode_ci DEFAULT 'custom',
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

INSERT INTO `localhost_calendar` (`id`, `recurrence_type`, `recurrence_repeat_type`, `recurrence_repeat_every`, `recurrence_repeat_on`, `start_date`, `start_time`, `end_time`, `all_day`, `end_date`, `content_id`, `calendar_group_id`, `title`, `image_url`, `link_url`, `description`, `active`) VALUES
(48, 'daily', 'day', 1, '', '2018-09-28', '19:00:00', '19:05:00', 0, '2018-09-28', 0, 0, 'Daily event - Smoke cigarate', '', '', '', '1'),
(49, 'doesnt_repeat', 'day', 1, '', '2018-09-28', '18:49:00', '18:49:00', 1, '2018-09-28', 0, 0, 'Doesn\'t repeat event', '', '', '', '1'),
(50, 'weekly_on_the_day_name', 'week', 1, '{\"friday\":\"1\"}', '2018-09-28', '18:00:00', '19:00:00', 0, '2018-09-28', 0, 0, 'Weekly on Friday - Drink beer after job', '', '', '', '1'),
(51, 'weekly_on_all_days', 'week', 1, '{\"sunday\":\"1\",\"monday\":\"1\",\"tuesday\":\"1\",\"wednesday\":\"1\",\"thursday\":\"1\",\"friday\":\"1\",\"saturday\":\"1\"}', '2018-09-28', '19:00:00', '19:15:00', 0, '2018-09-28', 0, 0, 'Weekly on all days - Cleaning teeth', '', '', '', '1'),
(52, 'every_weekday', 'week', 1, '{\"monday\":\"1\",\"tuesday\":\"1\",\"wednesday\":\"1\",\"thursday\":\"1\",\"friday\":\"1\"}', '2018-09-28', '22:00:00', '18:30:00', 0, '2018-09-28', 0, 0, 'Every weekday - Go to work', '', '', '', '1'),
(53, 'monthly_on_the_day_number', 'month', 1, '', '2018-09-28', '19:03:00', '19:03:00', 1, '2018-09-28', 0, 0, 'Monthly on day', '', '', '', '1'),
(54, 'monthly_on_the_week_number_day_name', 'month', 1, '', '2018-09-28', '19:06:00', '19:06:00', 1, '2018-09-28', 0, 0, 'Monthly on the Last Day Name Of Month', '', '', '', '1'),
(55, 'annually_on_the_month_name_day_number', 'day', 1, '', '1997-04-25', '19:08:00', '19:08:00', 1, '1997-04-25', 0, 0, 'Annually on - Bozhidar Birthday', '', '', '', '1'),
(56, 'custom', 'year', 1, '', '2018-04-25', '19:12:00', '19:12:00', 1, '2018-04-25', 0, 0, 'Custom repeat every 1 year - Bozhidar Birthday', '', '', '', '1'),
(57, 'custom', 'day', 3, '', '2018-09-28', '20:00:00', '20:30:00', 0, '2018-09-28', 0, 0, 'Custom repeat every 3 days - Train your brain', '', '', '', '1');

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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
