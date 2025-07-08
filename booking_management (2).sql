-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 04, 2025 at 07:08 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `booking_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `bills`
--

CREATE TABLE `bills` (
  `bill_id` int(11) NOT NULL,
  `bill_number` varchar(50) NOT NULL,
  `cashier` varchar(100) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `bill_date` datetime NOT NULL DEFAULT current_timestamp(),
  `table_id` int(11) DEFAULT NULL,
  `order_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `is_guest` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bills`
--

INSERT INTO `bills` (`bill_id`, `bill_number`, `cashier`, `total_amount`, `bill_date`, `table_id`, `order_id`, `user_id`, `is_guest`) VALUES
(1, 'BILL685e4b7f9f296', 'cashier', '1000.00', '2025-06-27 13:12:55', NULL, NULL, NULL, 0),
(2, 'BILL685e4b9068f8d', 'cashier', '1000.00', '2025-06-27 13:13:12', NULL, NULL, NULL, 0),
(3, 'BILL685e4be660a2e', 'cashier', '1000.00', '2025-06-27 13:14:38', NULL, NULL, NULL, 0),
(4, 'BILL685e4c0f79425', 'cashier', '1000.00', '2025-06-27 13:15:19', NULL, NULL, NULL, 0),
(5, 'BILL685e4c2a3cb00', 'cashier', '1000.00', '2025-06-27 13:15:46', NULL, NULL, NULL, 0),
(6, 'BILL685e4d66e83f9', 'cashier', '0.00', '2025-06-27 13:21:02', NULL, NULL, NULL, 0),
(7, 'BILL685e4f06a8677', 'cashier', '0.00', '2025-06-27 13:27:58', NULL, NULL, NULL, 0),
(8, 'BILL685e4f20d31c6', 'cashier', '0.00', '2025-06-27 13:28:24', NULL, NULL, NULL, 0),
(9, 'BILL685e50f9d605f', 'cashier', '0.00', '2025-06-27 13:36:17', NULL, NULL, NULL, 0),
(10, 'BILL685e518447e31', 'cashier', '0.00', '2025-06-27 13:38:36', NULL, NULL, NULL, 0),
(11, 'BILL685e5304d0327', 'cashier', '0.00', '2025-06-27 13:45:00', NULL, NULL, NULL, 0),
(12, 'BILL685e5370a7f85', 'cashier', '0.00', '2025-06-27 13:46:48', NULL, NULL, NULL, 0),
(13, 'BILL685e53cb388d4', 'cashier', '25.00', '2025-06-27 13:48:19', NULL, NULL, NULL, 0),
(14, 'BILL685e53d77e0cd', 'cashier', '5.00', '2025-06-27 13:48:31', NULL, NULL, NULL, 0),
(15, 'BILL685e53f0a6bd6', 'cashier', '5.00', '2025-06-27 13:48:56', NULL, NULL, NULL, 0),
(16, 'BILL685e54bb7ab9b', 'cashier', '25.00', '2025-06-27 13:52:19', NULL, NULL, NULL, 0),
(17, 'BILL685e54c235962', 'cashier', '5.00', '2025-06-27 13:52:26', NULL, NULL, NULL, 0),
(18, 'BILL685e54f6dd45a', 'cashier', '5.00', '2025-06-27 13:53:18', NULL, NULL, NULL, 0),
(19, 'BILL685e557294714', 'cashier', '30.00', '2025-06-27 13:55:22', NULL, NULL, NULL, 0),
(20, 'BILL685e5b4d189b1', 'uwaiscashier@gmail.com', '300.00', '2025-06-27 14:20:21', NULL, NULL, NULL, 0),
(21, 'BILL685e5ca3eca08', 'uwaiscashier@gmail.com', '275.00', '2025-06-27 14:26:03', NULL, NULL, NULL, 0),
(22, 'BILL685e603797855', 'uwaiscashier@gmail.com', '525.00', '2025-06-27 14:41:19', NULL, NULL, NULL, 0),
(23, 'BILL685e7b8453504', 'cashier', '50.00', '2025-06-27 16:37:48', NULL, NULL, NULL, 0),
(24, 'BILL685e87ef3f65d', 'cashier', '50.00', '2025-06-27 17:30:47', NULL, NULL, NULL, 0),
(25, 'BILL685e8b55256b1', 'cashier', '50.00', '2025-06-27 17:45:17', NULL, NULL, NULL, 0),
(26, 'BILL685e9df00fe00', 'cashier', '50.00', '2025-06-27 19:04:40', 1, NULL, NULL, 0),
(27, 'BILL685e9e7b4b298', 'cashier', '50.00', '2025-06-27 19:06:59', 1, NULL, NULL, 0),
(28, 'BILL685e9ee6f1caf', 'uwaiscashier@gmail.com', '50.00', '2025-06-27 19:08:46', NULL, NULL, NULL, 0),
(29, 'BILL685ea00221840', 'uwaiscashier@gmail.com', '50.00', '2025-06-27 19:13:30', 1, NULL, NULL, 0),
(30, 'BILL685ea1a11ee0c', 'uwaiscashier@gmail.com', '100.00', '2025-06-27 19:20:25', 1, NULL, NULL, 0),
(31, 'BILL685ea270a7c94', 'uwaiscashier@gmail.com', '50.00', '2025-06-27 19:23:52', 1, NULL, NULL, 0),
(32, 'BILL685ea37a6aae5', 'uwaiscashier@gmail.com', '5.00', '2025-06-27 19:28:18', 2, NULL, NULL, 0),
(33, 'BILL685ea44331d90', 'uwaiscashier@gmail.com', '5.00', '2025-06-27 19:31:39', 2, NULL, NULL, 0),
(34, 'BILL685ea6d071d55', 'uwaiscashier@gmail.com', '15.00', '2025-06-27 19:42:32', 5, NULL, NULL, 0),
(35, 'BILL685ea97dc13fb', 'uwaiscashier@gmail.com', '5.00', '2025-06-27 19:53:57', 5, NULL, NULL, 0),
(36, 'BILL685eac96c7cff', 'uwaiscashier@gmail.com', '65.00', '2025-06-27 20:07:10', 4, NULL, NULL, 0),
(37, 'BILL685ead3ecb40d', 'uwaiscashier@gmail.com', '50.00', '2025-06-27 20:09:58', 5, NULL, NULL, 0),
(38, 'BILL685ecb60288fb', 'uwaiscashier@gmail.com', '550.00', '2025-06-27 22:18:32', 2, NULL, NULL, 0),
(39, 'BILL685fa45478f63', 'uwaiscashier@gmail.com', '1255.00', '2025-06-28 13:44:12', 5, NULL, NULL, 0),
(40, 'BILL685fa4fbde66b', 'uwaiscashier@gmail.com', '305.00', '2025-06-28 13:46:59', 7, NULL, NULL, 0),
(41, 'BILL685fac51e61e5', 'uwaiscashier@gmail.com', '250.00', '2025-06-28 14:18:17', 1, NULL, NULL, 0),
(42, 'BILL685fb2aa390dc', 'uwaiscashier@gmail.com', '1005.00', '2025-06-28 14:45:22', 1, 1, NULL, 0),
(43, 'BILL685fb3b4c2ed9', 'uwaiscashier@gmail.com', '250.00', '2025-06-28 14:49:48', 1, NULL, NULL, 0),
(44, 'BILL685fb3d67ed0c', 'uwaiscashier@gmail.com', '250.00', '2025-06-28 14:50:22', 1, 3, NULL, 0),
(45, 'BILL685fb4922fd06', 'uwaiscashier@gmail.com', '300.00', '2025-06-28 14:53:30', 1, 4, NULL, 0),
(46, 'BILL685fb51525fe4', 'uwaiscashier@gmail.com', '255.00', '2025-06-28 14:55:41', 2, 2, NULL, 0),
(47, 'BILL686019bd1ed22', 'uwaiscashier@gmail.com', '1800.00', '2025-06-28 22:05:09', 7, 7, NULL, 0),
(48, 'BILL68601c6242c3a', 'uwaiscashier@gmail.com', '250.00', '2025-06-28 22:16:26', 2, 6, NULL, 0),
(49, 'BILL68601cc201177', 'uwaiscashier@gmail.com', '250.00', '2025-06-28 22:18:02', 3, 10, NULL, 0),
(50, 'BILL68601e40cb065', 'uwaiscashier@gmail.com', '2010.00', '2025-06-28 22:24:24', 1, 5, NULL, 0),
(51, 'BILL6860212ad25e9', 'uwaiscashier@gmail.com', '600.00', '2025-06-28 22:36:50', 3, 11, NULL, 0),
(52, 'BILL6860247172f89', 'uwaiscashier@gmail.com', '550.00', '2025-06-28 22:50:49', 2, 14, NULL, 0),
(53, 'BILL6860262164a06', 'uwaiscashier@gmail.com', '2010.00', '2025-06-28 22:58:01', 1, 16, NULL, 0),
(54, 'BILL68602e9d822ab', 'uwaiscashier@gmail.com', '500.00', '2025-06-28 23:34:13', 3, 18, NULL, 0),
(55, 'BILL68603724a5a4b', 'uwaiscashier@gmail.com', '300.00', '2025-06-29 00:10:36', 3, 20, NULL, 0),
(56, 'BILL6860378899e9e', 'uwaiscashier@gmail.com', '300.00', '2025-06-29 00:12:16', 5, 23, NULL, 0),
(57, 'BILL6860379453baf', 'uwaiscashier@gmail.com', '350.00', '2025-06-29 00:12:28', 9, 22, NULL, 0),
(58, 'BILL68603c8db5ea6', 'uwaiscashier@gmail.com', '4025.00', '2025-06-29 00:33:41', 1, 31, NULL, 0),
(59, 'BILL6860434334610', 'uwaiscashier@gmail.com', '1005.00', '2025-06-29 01:02:19', 2, 43, NULL, 0),
(60, 'BILL686045480cca7', 'uwaiscashier@gmail.com', '1005.00', '2025-06-29 01:10:56', 11, 46, NULL, 0),
(61, 'BILL686045813c28b', 'uwaiscashier@gmail.com', '105.00', '2025-06-29 01:11:53', 1, 48, NULL, 0),
(62, 'BILL6860459a1f683', 'uwaiscashier@gmail.com', '1500.00', '2025-06-29 01:12:18', 2, 49, NULL, 0),
(63, 'BILL68627d31d5a8f', 'uwaiscashier@gmail.com', '255.00', '2025-06-30 17:34:01', 1, 52, NULL, 0),
(64, 'BILL68627ddd63889', 'uwaiscashier@gmail.com', '250.00', '2025-06-30 17:36:53', 2, 55, NULL, 0),
(65, 'BILL68627df732341', 'uwaiscashier@gmail.com', '50.00', '2025-06-30 17:37:19', 4, 56, NULL, 0),
(66, 'BILL68628946643a6', 'uwaiscashier@gmail.com', '2005.00', '2025-06-30 18:25:34', 2, 63, NULL, 0),
(67, 'BILL686289cd15307', 'uwaiscashier@gmail.com', '55.00', '2025-06-30 18:27:49', 1, 65, NULL, 0),
(68, 'BILL6862ad0f7b9fb', 'cashier', '50.00', '2025-06-30 20:58:15', 1, 75, NULL, 0),
(69, 'BILL6863a6c579c67', 'uwaiscashier@gmail.com', '55.00', '2025-07-01 14:43:41', 1, 78, NULL, 0),
(70, 'BILL6863a7020fed8', 'uwaiscashier@gmail.com', '255.00', '2025-07-01 14:44:42', 2, 80, NULL, 0),
(71, 'BILL6863a769f3cbd', 'uwaiscashier@gmail.com', '50.00', '2025-07-01 14:46:25', 2, 83, NULL, 0),
(72, 'BILL6863a7759372f', 'uwaiscashier@gmail.com', '250.00', '2025-07-01 14:46:37', 1, 82, NULL, 0),
(73, 'BILL6863a901c2e73', 'uwaiscashier@gmail.com', '250.00', '2025-07-01 14:53:13', 1, 86, NULL, 0),
(74, 'BILL6863ab9357d91', 'uwaiscashier@gmail.com', '300.00', '2025-07-01 15:04:11', 1, 88, NULL, 0),
(75, 'BILL6863b0987d8d6', 'uwaiscashier@gmail.com', '550.00', '2025-07-01 15:25:36', 1, 90, 6, 0),
(76, 'BILL6863b0e6df643', 'uwaiscashier@gmail.com', '300.00', '2025-07-01 15:26:54', 2, 92, NULL, 1),
(77, 'BILL6863b1d37d82a', 'uwaiscashier@gmail.com', '1050.00', '2025-07-01 15:30:51', 2, 94, 6, 0),
(79, 'BILL6863ba22bcff3', 'uwaiscashier@gmail.com', '550.00', '2025-07-01 16:06:18', 2, 98, 8, 0),
(80, 'BILL6863bc0ba77e2', 'uwaiscashier@gmail.com', '100.00', '2025-07-01 16:14:27', 1, 100, 8, 1),
(81, 'BILL6864dc74c7722', 'uwaiscashier@gmail.com', '250.00', '2025-07-02 12:45:00', 1, 104, 6, 0),
(82, 'BILL6864dcc22abb5', 'uwaiscashier@gmail.com', '500.00', '2025-07-02 12:46:18', 2, 106, NULL, 1),
(83, 'BILL686646f169501', 'uwaiscashier@gmail.com', '250.00', '2025-07-03 14:31:37', 2, 112, NULL, 1),
(84, 'BILL6866470640579', 'uwaiscashier@gmail.com', '250.00', '2025-07-03 14:31:58', 2, 113, NULL, 1),
(85, 'BILL6866479803f58', 'uwaiscashier@gmail.com', '300.00', '2025-07-03 14:34:24', 1, 114, 8, 0),
(86, 'BILL686647cf79538', 'uwaiscashier@gmail.com', '300.00', '2025-07-03 14:35:19', 1, 115, 8, 0),
(87, 'BILL686647f2ccb9f', 'uwaiscashier@gmail.com', '5.00', '2025-07-03 14:35:54', 2, 117, 6, 0),
(88, 'BILL68664809e7508', 'uwaiscashier@gmail.com', '250.00', '2025-07-03 14:36:17', 1, 116, 6, 0),
(89, 'BILL6866482ce07c2', 'uwaiscashier@gmail.com', '50.00', '2025-07-03 14:36:52', 2, 118, NULL, 1),
(90, 'BILL68664d3e37d9f', 'uwaiscashier@gmail.com', '1005.00', '2025-07-03 14:58:30', 1, 121, 8, 0),
(91, 'BILL68664d5da3850', 'uwaiscashier@gmail.com', '1005.00', '2025-07-03 14:59:01', 1, 124, 3, 0),
(92, 'BILL68664e859dba2', 'uwaiscashier@gmail.com', '300.00', '2025-07-03 15:03:57', 1, 125, NULL, 1),
(93, 'BILL68664e936342c', 'uwaiscashier@gmail.com', '50.00', '2025-07-03 15:04:11', 1, 127, 3, 0),
(94, 'BILL68664ffbba33b', 'uwaiscashier@gmail.com', '55.00', '2025-07-03 15:10:11', 1, 128, 6, 0),
(95, 'BILL6866500c0d6f6', 'uwaiscashier@gmail.com', '1005.00', '2025-07-03 15:10:28', 2, 126, 3, 0),
(96, 'BILL68665032554db', 'uwaiscashier@gmail.com', '1005.00', '2025-07-03 15:11:06', 1, 129, 6, 0),
(97, 'BILL6866527ed97cc', 'uwaiscashier@gmail.com', '250.00', '2025-07-03 15:20:54', 3, 131, 3, 0),
(98, 'BILL68665628ea0b6', 'uwaiscashier@gmail.com', '250.00', '2025-07-03 15:36:32', 1, 135, 8, 0),
(99, 'BILL68665643e9db6', 'uwaiscashier@gmail.com', '1000.00', '2025-07-03 15:36:59', 2, 137, 6, 0),
(100, 'BILL6867c99325c89', 'poojaprem2004@gmail.com', '1000.00', '2025-07-04 18:01:15', 1, 144, 9, 0),
(101, 'BILL6867cad4c1230', 'poojaprem2004@gmail.com', '350.00', '2025-07-04 18:06:36', 1, 146, 8, 0),
(102, 'BILL6867caeac4bc9', 'poojaprem2004@gmail.com', '1000.00', '2025-07-04 18:06:58', 3, 147, NULL, 1),
(103, 'BILL6867cb3f5946e', 'poojaprem2004@gmail.com', '55.00', '2025-07-04 18:08:23', 2, 150, 6, 1);

-- --------------------------------------------------------

--
-- Table structure for table `bill_items`
--

CREATE TABLE `bill_items` (
  `item_id` int(11) NOT NULL,
  `bill_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bill_items`
--

INSERT INTO `bill_items` (`item_id`, `bill_id`, `product_name`, `quantity`, `price`, `total`) VALUES
(1, 1, 'dress', 1, '1000.00', '1000.00'),
(2, 2, 'dress', 1, '1000.00', '1000.00'),
(3, 3, 'dress', 1, '1000.00', '1000.00'),
(4, 4, 'dress', 1, '1000.00', '1000.00'),
(5, 5, 'dress', 1, '1000.00', '1000.00'),
(6, 6, 'watercan', 2, '0.00', '0.00'),
(7, 7, 'watercan', 2, '0.00', '0.00'),
(8, 8, 'watercan', 2, '0.00', '0.00'),
(9, 9, 'bed', 1, '0.00', '0.00'),
(10, 10, 'watercan', 1, '0.00', '0.00'),
(11, 11, 'watercan', 1, '0.00', '0.00'),
(12, 11, 'bed', 1, '0.00', '0.00'),
(13, 12, 'watercan', 2, '0.00', '0.00'),
(14, 13, 'banana', 5, '5.00', '25.00'),
(15, 14, 'banana', 1, '5.00', '5.00'),
(16, 15, 'banana', 1, '5.00', '5.00'),
(17, 16, 'banana', 5, '5.00', '25.00'),
(18, 17, 'banana', 1, '5.00', '5.00'),
(19, 18, 'banana', 1, '5.00', '5.00'),
(20, 19, 'watercan', 9, '0.00', '0.00'),
(21, 19, 'banana', 6, '5.00', '30.00'),
(22, 20, 'banana', 10, '5.00', '50.00'),
(23, 20, 'chocolate', 5, '50.00', '250.00'),
(24, 21, 'chocolate', 5, '50.00', '250.00'),
(25, 21, 'banana', 5, '5.00', '25.00'),
(26, 22, 'banana', 5, '5.00', '25.00'),
(27, 22, 'chocolate', 10, '50.00', '500.00'),
(28, 23, 'chocolate', 1, '50.00', '50.00'),
(29, 23, 'watercan', 1, '0.00', '0.00'),
(30, 24, 'chocolate', 1, '50.00', '50.00'),
(31, 25, 'chocolate', 1, '50.00', '50.00'),
(32, 26, 'chocolate', 1, '50.00', '50.00'),
(33, 27, 'chocolate', 1, '50.00', '50.00'),
(34, 28, 'chocolate', 1, '50.00', '50.00'),
(35, 29, 'chocolate', 1, '50.00', '50.00'),
(36, 30, 'chocolate', 2, '50.00', '100.00'),
(37, 31, 'chocolate', 1, '50.00', '50.00'),
(38, 32, 'banana', 1, '5.00', '5.00'),
(39, 33, 'banana', 1, '5.00', '5.00'),
(40, 33, 'watercan', 1, '0.00', '0.00'),
(41, 34, 'banana', 3, '5.00', '15.00'),
(42, 35, 'banana', 1, '5.00', '5.00'),
(43, 36, 'banana', 3, '5.00', '15.00'),
(44, 36, 'chocolate', 1, '50.00', '50.00'),
(45, 37, 'chocolate', 1, '50.00', '50.00'),
(46, 38, 'alcohol', 2, '250.00', '500.00'),
(47, 38, 'chocolate', 1, '50.00', '50.00'),
(48, 39, 'dress', 1, '1000.00', '1000.00'),
(49, 39, 'banana', 1, '5.00', '5.00'),
(50, 39, 'alcohol', 1, '250.00', '250.00'),
(51, 40, 'alcohol', 1, '250.00', '250.00'),
(52, 40, 'banana', 1, '5.00', '5.00'),
(53, 40, 'chocolate', 1, '50.00', '50.00'),
(54, 41, 'alcohol', 1, '250.00', '250.00'),
(55, 42, 'dress', 1, '1000.00', '1000.00'),
(56, 42, 'banana', 1, '5.00', '5.00'),
(57, 43, 'alcohol', 1, '250.00', '250.00'),
(58, 44, 'alcohol', 1, '250.00', '250.00'),
(59, 45, 'alcohol', 1, '250.00', '250.00'),
(60, 45, 'chocolate', 1, '50.00', '50.00'),
(61, 46, 'banana', 1, '5.00', '5.00'),
(62, 46, 'alcohol', 1, '250.00', '250.00'),
(63, 47, 'chocolate', 1, '50.00', '50.00'),
(64, 47, 'alcohol', 7, '250.00', '1750.00'),
(65, 48, 'alcohol', 1, '250.00', '250.00'),
(66, 49, 'alcohol', 1, '250.00', '250.00'),
(67, 50, 'dress', 2, '1000.00', '2000.00'),
(68, 50, 'banana', 2, '5.00', '10.00'),
(69, 51, 'alcohol', 2, '250.00', '500.00'),
(70, 51, 'chocolate', 2, '50.00', '100.00'),
(71, 52, 'alcohol', 2, '250.00', '500.00'),
(72, 52, 'chocolate', 1, '50.00', '50.00'),
(73, 53, 'dress', 2, '1000.00', '2000.00'),
(74, 53, 'banana', 2, '5.00', '10.00'),
(75, 54, 'alcohol', 2, '250.00', '500.00'),
(76, 55, 'chocolate', 1, '50.00', '50.00'),
(77, 55, 'alcohol', 1, '250.00', '250.00'),
(78, 56, 'chocolate', 1, '50.00', '50.00'),
(79, 56, 'alcohol', 1, '250.00', '250.00'),
(80, 57, 'chocolate', 2, '50.00', '100.00'),
(81, 57, 'alcohol', 1, '250.00', '250.00'),
(82, 58, 'dress', 4, '1000.00', '4000.00'),
(83, 58, 'banana', 5, '5.00', '25.00'),
(84, 59, 'dress', 1, '1000.00', '1000.00'),
(85, 59, 'banana', 1, '5.00', '5.00'),
(86, 60, 'dress', 1, '1000.00', '1000.00'),
(87, 60, 'banana', 1, '5.00', '5.00'),
(88, 61, 'banana', 1, '5.00', '5.00'),
(89, 61, 'chocolate', 2, '50.00', '100.00'),
(90, 62, 'alcohol', 2, '250.00', '500.00'),
(91, 62, 'dress', 1, '1000.00', '1000.00'),
(92, 63, 'banana', 1, '5.00', '5.00'),
(93, 63, 'alcohol', 1, '250.00', '250.00'),
(94, 64, 'alcohol', 1, '250.00', '250.00'),
(95, 65, 'chocolate', 1, '50.00', '50.00'),
(96, 66, 'dress', 2, '1000.00', '2000.00'),
(97, 66, 'banana', 1, '5.00', '5.00'),
(98, 67, 'banana', 1, '5.00', '5.00'),
(99, 67, 'chocolate', 1, '50.00', '50.00'),
(100, 68, 'chocolate', 1, '50.00', '50.00'),
(101, 69, 'banana', 1, '5.00', '5.00'),
(102, 69, 'chocolate', 1, '50.00', '50.00'),
(103, 70, 'banana', 1, '5.00', '5.00'),
(104, 70, 'alcohol', 1, '250.00', '250.00'),
(105, 71, 'chocolate', 1, '50.00', '50.00'),
(106, 72, 'alcohol', 1, '250.00', '250.00'),
(107, 73, 'alcohol', 1, '250.00', '250.00'),
(108, 74, 'chocolate', 1, '50.00', '50.00'),
(109, 74, 'alcohol', 1, '250.00', '250.00'),
(110, 75, 'chocolate', 1, '50.00', '50.00'),
(111, 75, 'alcohol', 2, '250.00', '500.00'),
(112, 76, 'chocolate', 1, '50.00', '50.00'),
(113, 76, 'alcohol', 1, '250.00', '250.00'),
(114, 77, 'dress', 1, '1000.00', '1000.00'),
(115, 77, 'chocolate', 1, '50.00', '50.00'),
(118, 79, 'alcohol', 2, '250.00', '500.00'),
(119, 79, 'chocolate', 1, '50.00', '50.00'),
(120, 80, 'chocolate', 2, '50.00', '100.00'),
(121, 81, 'alcohol', 1, '250.00', '250.00'),
(122, 82, 'alcohol', 2, '250.00', '500.00'),
(123, 83, 'alcohol', 1, '250.00', '250.00'),
(124, 84, 'alcohol', 1, '250.00', '250.00'),
(125, 85, 'chocolate', 1, '50.00', '50.00'),
(126, 85, 'alcohol', 1, '250.00', '250.00'),
(127, 86, 'chocolate', 1, '50.00', '50.00'),
(128, 86, 'alcohol', 1, '250.00', '250.00'),
(129, 87, 'banana', 1, '5.00', '5.00'),
(130, 88, 'alcohol', 1, '250.00', '250.00'),
(131, 89, 'chocolate', 1, '50.00', '50.00'),
(132, 90, 'dress', 1, '1000.00', '1000.00'),
(133, 90, 'banana', 1, '5.00', '5.00'),
(134, 91, 'dress', 1, '1000.00', '1000.00'),
(135, 91, 'banana', 1, '5.00', '5.00'),
(136, 92, 'chocolate', 1, '50.00', '50.00'),
(137, 92, 'alcohol', 1, '250.00', '250.00'),
(138, 93, 'chocolate', 1, '50.00', '50.00'),
(139, 94, 'banana', 1, '5.00', '5.00'),
(140, 94, 'chocolate', 1, '50.00', '50.00'),
(141, 95, 'dress', 1, '1000.00', '1000.00'),
(142, 95, 'banana', 1, '5.00', '5.00'),
(143, 96, 'banana', 1, '5.00', '5.00'),
(144, 96, 'dress', 1, '1000.00', '1000.00'),
(145, 97, 'alcohol', 1, '250.00', '250.00'),
(146, 98, 'alcohol', 1, '250.00', '250.00'),
(147, 99, 'dress', 1, '1000.00', '1000.00'),
(148, 100, 'dress', 1, '1000.00', '1000.00'),
(149, 101, 'alcohol', 1, '250.00', '250.00'),
(150, 101, 'chocolate', 2, '50.00', '100.00'),
(151, 102, 'dress', 1, '1000.00', '1000.00'),
(152, 103, 'chocolate', 1, '50.00', '50.00'),
(153, 103, 'banana', 1, '5.00', '5.00');

-- --------------------------------------------------------

--
-- Table structure for table `login_user`
--

CREATE TABLE `login_user` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('manager','cashier') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `login_user`
--

INSERT INTO `login_user` (`id`, `email`, `password`, `role`) VALUES
(1, 'sri@gmail.com', '123', 'manager'),
(2, 'vgsridharan27092004@gmail.com', '$2y$10$qOmtLYYm7Rk1yzASIMIRM.MZ0monIoeXGgwGYSAN//huQnH4HakPO', 'manager'),
(3, 'ak@gmail.com', '$2y$10$oyDIHHMnum0DorlD0KZ2XOISFIiB9e.ElNRsyyuREkZHXwA11Q3tu', 'manager'),
(4, 'uwais@gmail.com', '$2y$10$pfgBzhZtenNsXZ0mn8EN3ObDBVQqytqnygzPZviaiIwQE2RX2iWTK', 'manager'),
(5, 'uwais1@gmail.com', '$2y$10$6BNJ7xBauudCMjkyDmLU..0ToVKU0WZfd.xpGO1k7BZKKnlqJbbEi', 'manager'),
(6, 'pooja@gmail.com', '$2y$10$Zkm9fzxI1ym0cPY4pvjMEejpJ8jFcoywRqIao.F/aSkKB6QclMW8.', 'manager'),
(7, 'kavitha@gmail.com', '$2y$10$xazGAowH2PRmgEwkFM7dqOwxCz1oT4JFFQt03Iayi9RvFA2nKou32', 'cashier'),
(8, 'sri1@gmail.com', '$2y$10$MS92AprcW/J1DSNJLUbH5uJU5oc3NGWRBPOhOHxDwZjJ6ceCsf0gu', 'manager'),
(9, 'sri5@gmail.com', '$2y$10$Bxahxx4/HvxNZjpEwT3l..eFB1RhXbUNtdTKXlJygTAc3t5XxrwDy', 'manager'),
(10, 'poojaprem2004@gmail.com', '$2y$10$DfqjjAY7VDdVvuOqVUbv0OlIAFPnsceTFjzlpKoj42sWMLElSnb2.', 'manager'),
(11, 'ganesan@gmail.com', '$2y$10$UsjyREmUxfFvblxrhQGt9eAnXcIlas.MKXRpyTZdXJQCVIRSGivD6', 'manager'),
(12, 'uwaiscashier@gmail.com', '$2y$10$XGh.GVeApnlp9vUugTHJs.EQP9GsFDB2m1f8uJKKtpI582WZ1mXNa', 'cashier');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `table_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `is_guest` tinyint(1) DEFAULT 0,
  `status` enum('open','closed') DEFAULT 'open',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `table_id`, `user_id`, `is_guest`, `status`, `created_at`) VALUES
(77, 1, NULL, 0, 'closed', '2025-07-01 14:37:58'),
(78, 1, NULL, 0, 'closed', '2025-07-01 14:43:18'),
(79, 1, NULL, 0, 'closed', '2025-07-01 14:43:41'),
(80, 2, NULL, 0, 'closed', '2025-07-01 14:44:31'),
(81, 2, NULL, 0, 'closed', '2025-07-01 14:44:42'),
(82, 1, NULL, 0, 'closed', '2025-07-01 14:46:06'),
(83, 2, NULL, 0, 'closed', '2025-07-01 14:46:13'),
(84, 2, NULL, 0, 'closed', '2025-07-01 14:46:26'),
(85, 1, NULL, 0, 'closed', '2025-07-01 14:46:37'),
(86, 1, NULL, 0, 'closed', '2025-07-01 14:53:07'),
(87, 1, NULL, 0, 'closed', '2025-07-01 14:53:13'),
(88, 1, NULL, 0, 'closed', '2025-07-01 15:04:03'),
(89, 1, NULL, 0, 'closed', '2025-07-01 15:04:11'),
(90, 1, NULL, 0, 'closed', '2025-07-01 15:17:24'),
(91, 1, NULL, 0, 'closed', '2025-07-01 15:25:36'),
(92, 2, NULL, 0, 'closed', '2025-07-01 15:26:47'),
(93, 2, NULL, 0, 'closed', '2025-07-01 15:26:54'),
(94, 2, NULL, 0, 'closed', '2025-07-01 15:30:43'),
(95, 2, NULL, 0, 'closed', '2025-07-01 15:30:51'),
(96, 2, NULL, 0, 'closed', '2025-07-01 15:40:26'),
(97, 2, NULL, 0, 'closed', '2025-07-01 15:40:36'),
(98, 2, NULL, 0, 'closed', '2025-07-01 16:06:02'),
(99, 2, NULL, 0, 'closed', '2025-07-01 16:06:18'),
(100, 1, NULL, 0, 'closed', '2025-07-01 16:14:16'),
(101, 1, NULL, 0, 'closed', '2025-07-01 16:14:27'),
(102, 1, NULL, 0, 'closed', '2025-07-01 16:18:14'),
(103, 3, NULL, 0, 'closed', '2025-07-01 16:18:26'),
(104, 1, NULL, 0, 'closed', '2025-07-02 12:44:52'),
(105, 1, NULL, 0, 'closed', '2025-07-02 12:45:00'),
(106, 2, NULL, 0, 'closed', '2025-07-02 12:46:08'),
(107, 2, NULL, 0, 'closed', '2025-07-02 12:46:18'),
(108, 1, NULL, 0, 'closed', '2025-07-02 12:47:00'),
(109, 2, NULL, 0, 'closed', '2025-07-03 14:14:49'),
(110, 1, NULL, 0, 'closed', '2025-07-03 14:15:11'),
(111, 1, NULL, 0, 'closed', '2025-07-03 14:30:40'),
(112, 2, NULL, 0, 'closed', '2025-07-03 14:31:19'),
(113, 2, NULL, 0, 'closed', '2025-07-03 14:31:53'),
(114, 1, NULL, 0, 'closed', '2025-07-03 14:34:15'),
(115, 1, NULL, 0, 'closed', '2025-07-03 14:34:24'),
(116, 1, NULL, 0, 'closed', '2025-07-03 14:35:19'),
(117, 2, NULL, 0, 'closed', '2025-07-03 14:35:42'),
(118, 2, NULL, 0, 'closed', '2025-07-03 14:35:54'),
(119, 1, NULL, 0, 'closed', '2025-07-03 14:36:17'),
(120, 2, NULL, 0, 'closed', '2025-07-03 14:36:52'),
(121, 1, NULL, 0, 'closed', '2025-07-03 14:56:43'),
(122, 2, NULL, 0, 'closed', '2025-07-03 14:57:40'),
(123, 3, NULL, 0, 'closed', '2025-07-03 14:58:06'),
(124, 1, NULL, 0, 'closed', '2025-07-03 14:58:44'),
(125, 1, NULL, 0, 'closed', '2025-07-03 15:03:24'),
(126, 2, NULL, 0, 'closed', '2025-07-03 15:03:44'),
(127, 1, NULL, 0, 'closed', '2025-07-03 15:04:05'),
(128, 1, NULL, 0, 'closed', '2025-07-03 15:10:05'),
(129, 1, NULL, 0, 'closed', '2025-07-03 15:10:11'),
(130, 2, NULL, 0, 'closed', '2025-07-03 15:10:28'),
(131, 3, NULL, 0, 'closed', '2025-07-03 15:10:52'),
(132, 1, NULL, 0, 'closed', '2025-07-03 15:11:06'),
(133, 5, NULL, 0, 'closed', '2025-07-03 15:11:30'),
(134, 3, NULL, 0, 'closed', '2025-07-03 15:20:54'),
(135, 1, NULL, 0, 'closed', '2025-07-03 15:34:58'),
(136, 2, NULL, 0, 'closed', '2025-07-03 15:35:30'),
(137, 2, NULL, 0, 'closed', '2025-07-03 15:36:00'),
(138, 1, NULL, 0, 'closed', '2025-07-03 15:36:32'),
(139, 2, NULL, 0, 'closed', '2025-07-03 15:37:00'),
(140, 1, NULL, 0, 'closed', '2025-07-03 17:25:07'),
(141, 2, NULL, 0, 'closed', '2025-07-03 17:25:31'),
(142, 1, NULL, 0, 'closed', '2025-07-03 19:04:04'),
(143, 2, NULL, 0, 'closed', '2025-07-03 19:04:24'),
(144, 1, NULL, 0, 'closed', '2025-07-04 18:01:04'),
(145, 1, NULL, 0, 'closed', '2025-07-04 18:01:15'),
(146, 1, NULL, 0, 'closed', '2025-07-04 18:05:49'),
(147, 3, NULL, 0, 'closed', '2025-07-04 18:06:11'),
(148, 1, NULL, 0, 'closed', '2025-07-04 18:06:36'),
(149, 3, NULL, 0, 'closed', '2025-07-04 18:06:58'),
(150, 2, NULL, 0, 'closed', '2025-07-04 18:07:26'),
(151, 2, NULL, 0, 'closed', '2025-07-04 18:08:23');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `item_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`item_id`, `order_id`, `product_name`, `quantity`, `price`) VALUES
(73, 78, 'banana', 1, '5.00'),
(74, 78, 'chocolate', 1, '50.00'),
(75, 80, 'banana', 1, '5.00'),
(76, 80, 'alcohol', 1, '250.00'),
(77, 83, 'chocolate', 1, '50.00'),
(78, 82, 'alcohol', 1, '250.00'),
(79, 86, 'alcohol', 1, '250.00'),
(80, 88, 'chocolate', 1, '50.00'),
(81, 88, 'alcohol', 1, '250.00'),
(82, 90, 'chocolate', 1, '50.00'),
(83, 90, 'alcohol', 2, '250.00'),
(84, 92, 'chocolate', 1, '50.00'),
(85, 92, 'alcohol', 1, '250.00'),
(86, 94, 'dress', 1, '1000.00'),
(87, 94, 'chocolate', 1, '50.00'),
(88, 96, 'dress', 1, '1000.00'),
(89, 96, 'banana', 1, '5.00'),
(90, 98, 'alcohol', 2, '250.00'),
(91, 98, 'chocolate', 1, '50.00'),
(92, 100, 'chocolate', 2, '50.00'),
(98, 104, 'alcohol', 1, '250.00'),
(99, 106, 'alcohol', 2, '250.00'),
(100, 108, 'dress', 1, '1000.00'),
(101, 108, 'banana', 1, '5.00'),
(102, 109, 'alcohol', 1, '250.00'),
(103, 111, 'dress', 1, '1000.00'),
(104, 111, 'banana', 1, '5.00'),
(105, 112, 'alcohol', 1, '250.00'),
(106, 113, 'alcohol', 1, '250.00'),
(107, 114, 'chocolate', 1, '50.00'),
(108, 114, 'alcohol', 1, '250.00'),
(109, 115, 'chocolate', 1, '50.00'),
(110, 115, 'alcohol', 1, '250.00'),
(111, 117, 'banana', 1, '5.00'),
(112, 118, 'chocolate', 1, '50.00'),
(113, 116, 'alcohol', 1, '250.00'),
(114, 119, 'dress', 2, '1000.00'),
(115, 121, 'dress', 1, '1000.00'),
(116, 121, 'banana', 1, '5.00'),
(117, 122, 'alcohol', 1, '250.00'),
(118, 122, 'chocolate', 1, '50.00'),
(119, 123, 'chocolate', 3, '50.00'),
(121, 124, 'dress', 1, '1000.00'),
(122, 124, 'banana', 1, '5.00'),
(123, 125, 'chocolate', 1, '50.00'),
(124, 125, 'alcohol', 1, '250.00'),
(125, 126, 'dress', 1, '1000.00'),
(126, 126, 'banana', 1, '5.00'),
(127, 127, 'chocolate', 1, '50.00'),
(128, 128, 'banana', 1, '5.00'),
(129, 128, 'chocolate', 1, '50.00'),
(130, 129, 'banana', 1, '5.00'),
(131, 129, 'dress', 1, '1000.00'),
(132, 131, 'alcohol', 1, '250.00'),
(133, 133, 'alcohol', 1, '250.00'),
(134, 136, 'dress', 1, '1000.00'),
(135, 135, 'alcohol', 1, '250.00'),
(136, 137, 'dress', 1, '1000.00'),
(137, 142, 'dress', 2, '1000.00'),
(138, 143, 'alcohol', 1, '250.00'),
(139, 144, 'dress', 1, '1000.00'),
(140, 146, 'alcohol', 1, '250.00'),
(141, 146, 'chocolate', 2, '50.00'),
(142, 147, 'dress', 1, '1000.00'),
(143, 150, 'chocolate', 1, '50.00'),
(144, 150, 'banana', 1, '5.00');

-- --------------------------------------------------------

--
-- Table structure for table `stock`
--

CREATE TABLE `stock` (
  `stock_id` int(11) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `quantity` int(11) NOT NULL,
  `availability_flag` enum('yes','no') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `price` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stock`
--

INSERT INTO `stock` (`stock_id`, `product_name`, `quantity`, `availability_flag`, `created_at`, `price`) VALUES
(18, 'dress', 76, 'yes', '2025-06-27 07:04:50', '1000.00'),
(19, 'banana', 64, 'yes', '2025-06-27 08:17:34', '5.00'),
(20, 'chocolate', 127, 'yes', '2025-06-27 08:45:48', '50.00'),
(21, 'alcohol', 75, 'yes', '2025-06-27 14:41:09', '250.00');

-- --------------------------------------------------------

--
-- Table structure for table `stock_logs`
--

CREATE TABLE `stock_logs` (
  `log_id` int(11) NOT NULL,
  `stock_id` int(11) NOT NULL,
  `action` enum('add','update','delete') NOT NULL,
  `performed_by` varchar(100) NOT NULL,
  `role` enum('manager','cashier') NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `product_name` varchar(100) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `availability_flag` enum('yes','no') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stock_logs`
--

INSERT INTO `stock_logs` (`log_id`, `stock_id`, `action`, `performed_by`, `role`, `timestamp`, `product_name`, `quantity`, `availability_flag`) VALUES
(7, 7, 'add', 'poojaprem2004@gmail.com', 'manager', '2025-06-24 10:57:29', 'bed', 2, 'yes'),
(17, 11, 'add', 'poojaprem2004@gmail.com', 'manager', '2025-06-24 12:09:42', 'soap', 50, 'yes'),
(18, 11, 'delete', 'poojaprem2004@gmail.com', 'manager', '2025-06-24 12:10:26', 'soap', 50, 'yes'),
(19, 11, 'delete', 'poojaprem2004@gmail.com', 'manager', '2025-06-24 12:39:07', 'soap', 50, 'yes'),
(20, 12, 'add', 'poojaprem2004@gmail.com', 'manager', '2025-06-24 12:39:56', 'specs', 2, 'yes'),
(21, 12, 'update', 'poojaprem2004@gmail.com', 'manager', '2025-06-24 12:40:40', 'specs', 5, 'yes'),
(22, 12, 'delete', 'poojaprem2004@gmail.com', 'manager', '2025-06-24 12:41:16', 'specs', 5, 'yes'),
(23, 13, 'add', 'ganesan@gmail.com', 'manager', '2025-06-24 16:49:55', 'watercan', 100, 'yes'),
(24, 14, 'add', 'ganesan@gmail.com', 'manager', '2025-06-24 16:50:46', 'watercan', 100, 'yes'),
(25, 14, 'update', 'ganesan@gmail.com', 'manager', '2025-06-24 16:52:12', 'watercan', 50, 'yes'),
(26, 15, 'add', 'ganesan@gmail.com', 'manager', '2025-06-24 16:56:38', 'dress', 10, 'yes'),
(27, 15, 'delete', 'ganesan@gmail.com', 'manager', '2025-06-24 16:56:49', 'dress', 10, 'yes'),
(28, 16, 'add', 'ganesan@gmail.com', 'manager', '2025-06-24 16:57:29', 'apple', 10, 'yes'),
(29, 16, 'delete', 'ganesan@gmail.com', 'manager', '2025-06-24 16:57:43', 'apple', 10, 'yes'),
(30, 17, 'add', 'ganesan@gmail.com', 'manager', '2025-06-24 17:41:53', 'alcohol', 5, 'yes'),
(31, 17, 'update', 'ganesan@gmail.com', 'manager', '2025-06-24 17:42:39', 'alcohol', 10, 'yes'),
(32, 17, 'delete', 'ganesan@gmail.com', 'manager', '2025-06-24 17:44:06', 'alcohol', 10, 'yes'),
(33, 20, 'add', 'poojaprem2004@gmail.com', 'manager', '2025-06-27 08:45:48', 'chocolate', 100, 'yes'),
(34, 20, 'update', 'poojaprem2004@gmail.com', 'manager', '2025-06-27 08:46:58', 'chocolate', 150, 'yes'),
(35, 19, 'update', 'poojaprem2004@gmail.com', 'manager', '2025-06-27 14:04:40', 'banana', 100, 'yes'),
(36, 21, 'add', 'poojaprem2004@gmail.com', 'manager', '2025-06-27 14:41:09', 'alcohol', 50, 'yes'),
(37, 18, 'update', 'poojaprem2004@gmail.com', 'manager', '2025-06-27 17:00:33', 'dress', 100, 'yes'),
(38, 7, 'delete', 'poojaprem2004@gmail.com', 'manager', '2025-06-27 17:00:55', 'bed', 0, 'yes'),
(39, 14, 'delete', 'poojaprem2004@gmail.com', 'manager', '2025-06-27 17:01:14', 'watercan', 28, 'yes'),
(40, 21, 'update', 'poojaprem2004@gmail.com', 'manager', '2025-06-28 19:01:16', 'alcohol', 100, 'yes'),
(41, 20, 'update', 'poojaprem2004@gmail.com', 'manager', '2025-06-28 19:01:43', 'chocolate', 150, 'yes');

-- --------------------------------------------------------

--
-- Table structure for table `tables`
--

CREATE TABLE `tables` (
  `table_id` int(11) NOT NULL,
  `table_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tables`
--

INSERT INTO `tables` (`table_id`, `table_name`) VALUES
(1, 'Table 1'),
(2, 'Table 2'),
(3, 'Table 3'),
(4, 'Table 4'),
(5, 'Table 5');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `membership_number` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `membership_type` varchar(50) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `membership_number`, `email`, `phone`, `address`, `membership_type`, `notes`, `created_at`) VALUES
(3, 'Carol Lee', 'M1003', 'carol@example.com', '9876512345', '789 Lake Rd', 'Gold', '', '2025-06-30 16:03:11'),
(6, 'pooja', 'M1006', 'poojaprem2004@gmail.com', '6379852593', 'mahal 8th street', 'Gold', 'VIP', '2025-07-01 07:52:11'),
(8, 'sridharan', 'M1005', 'vgsridharan27092004@gmail.com', '8778861727', 'mahal 3rd street', 'Gold', 'VIP', '2025-07-01 10:35:36'),
(9, 'sri', 'M1001', 'sri@gmail.com', '8778861727', 'mahal 3rd street', 'Gold', '', '2025-07-03 13:28:25');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bills`
--
ALTER TABLE `bills`
  ADD PRIMARY KEY (`bill_id`),
  ADD KEY `fk_bills_user_id` (`user_id`);

--
-- Indexes for table `bill_items`
--
ALTER TABLE `bill_items`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `bill_id` (`bill_id`);

--
-- Indexes for table `login_user`
--
ALTER TABLE `login_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `fk_orders_user_id` (`user_id`),
  ADD KEY `fk_orders_table_id` (`table_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `fk_order_items_order_id` (`order_id`);

--
-- Indexes for table `stock`
--
ALTER TABLE `stock`
  ADD PRIMARY KEY (`stock_id`);

--
-- Indexes for table `stock_logs`
--
ALTER TABLE `stock_logs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `stock_logs_ibfk_1` (`stock_id`);

--
-- Indexes for table `tables`
--
ALTER TABLE `tables`
  ADD PRIMARY KEY (`table_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `membership_number` (`membership_number`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bills`
--
ALTER TABLE `bills`
  MODIFY `bill_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=104;

--
-- AUTO_INCREMENT for table `bill_items`
--
ALTER TABLE `bill_items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=154;

--
-- AUTO_INCREMENT for table `login_user`
--
ALTER TABLE `login_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=152;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=145;

--
-- AUTO_INCREMENT for table `stock`
--
ALTER TABLE `stock`
  MODIFY `stock_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `stock_logs`
--
ALTER TABLE `stock_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `tables`
--
ALTER TABLE `tables`
  MODIFY `table_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bills`
--
ALTER TABLE `bills`
  ADD CONSTRAINT `fk_bills_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `bill_items`
--
ALTER TABLE `bill_items`
  ADD CONSTRAINT `bill_items_ibfk_1` FOREIGN KEY (`bill_id`) REFERENCES `bills` (`bill_id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_table_id` FOREIGN KEY (`table_id`) REFERENCES `tables` (`table_id`),
  ADD CONSTRAINT `fk_orders_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `fk_order_items_order_id` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
