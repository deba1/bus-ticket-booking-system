-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 06, 2019 at 08:20 PM
-- Server version: 10.1.37-MariaDB
-- PHP Version: 7.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bus_ticket`
--

-- --------------------------------------------------------

--
-- Table structure for table `buses`
--

CREATE TABLE `buses` (
  `id` int(5) NOT NULL,
  `bname` varchar(25) NOT NULL,
  `bus_no` varchar(25) NOT NULL,
  `owner_id` int(5) NOT NULL,
  `from_loc` varchar(20) NOT NULL,
  `from_time` varchar(8) NOT NULL,
  `to_loc` varchar(20) NOT NULL,
  `to_time` varchar(8) NOT NULL,
  `fare` int(5) NOT NULL,
  `approved` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `buses`
--

INSERT INTO `buses` (`id`, `bname`, `bus_no`, `owner_id`, `from_loc`, `from_time`, `to_loc`, `to_time`, `fare`, `approved`) VALUES
(1, 'Hanif', 'NM-12456', 3, 'Naogaon', '10:30 PM', 'Dhaka', '05:00 AM', 500, 1),
(2, 'Hanif', 'NM-12456', 3, 'Dhaka', '09:45 PM', 'Naogaon', '04:15 AM', 500, 0),
(4, 'Himachal', 'BD-123456', 3, 'Dhaka', '10:30 AM', 'Rajshahi', '4:30 PM', 700, 0);

-- --------------------------------------------------------

--
-- Table structure for table `earnings`
--

CREATE TABLE `earnings` (
  `id` int(5) NOT NULL,
  `bus_id` int(5) NOT NULL,
  `date` varchar(10) NOT NULL,
  `ssold` int(3) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `id` int(5) NOT NULL,
  `name` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`id`, `name`) VALUES
(1, 'Dhaka'),
(2, 'Naogaon'),
(3, 'Chittagong'),
(4, 'Rajshahi');

-- --------------------------------------------------------

--
-- Table structure for table `notices`
--

CREATE TABLE `notices` (
  `id` int(5) NOT NULL,
  `recep` int(5) NOT NULL,
  `message` varchar(120) NOT NULL,
  `from` int(5) NOT NULL,
  `title` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tickets`
--

CREATE TABLE `tickets` (
  `id` int(5) NOT NULL,
  `passenger_id` int(5) NOT NULL,
  `bus_id` int(5) NOT NULL,
  `jdate` varchar(25) NOT NULL,
  `seats` varchar(120) NOT NULL,
  `fare` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tickets`
--

INSERT INTO `tickets` (`id`, `passenger_id`, `bus_id`, `jdate`, `seats`, `fare`) VALUES
(9, 2, 2, '04/08/2019', 'a:2:{i:0;s:2:\"D1\";i:1;s:2:\"D2\";}', 1050),
(11, 2, 2, '05/08/2019', 'a:1:{i:0;s:2:\"E1\";}', 1050),
(24, 2, 2, '06/08/2019', 'a:2:{i:0;s:2:\"D3\";i:1;s:2:\"D4\";}', 550),
(25, 2, 2, '06/08/2019', 'a:1:{i:0;s:2:\"E4\";}', 550),
(26, 5, 2, '06/08/2019', 'a:2:{i:0;s:2:\"C3\";i:1;s:2:\"C4\";}', 1050),
(27, 2, 2, '07/08/2019', 'a:2:{i:0;s:2:\"E1\";i:1;s:2:\"E2\";}', 1050);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(5) NOT NULL,
  `uname` varchar(20) NOT NULL,
  `name` varchar(25) NOT NULL,
  `email` varchar(40) NOT NULL,
  `password` varchar(25) NOT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `utype` enum('Admin','Owner','Passenger') NOT NULL,
  `address` varchar(120) NOT NULL,
  `mobile` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `uname`, `name`, `email`, `password`, `gender`, `utype`, `address`, `mobile`) VALUES
(1, 'admin', 'Admin', '----', 'admin', 'Male', 'Admin', '----', '0'),
(2, 'deba', 'Debashish Sarker', 'dsarker333@gmail.com', '123456', 'Male', 'Passenger', '', '1000000000'),
(3, 'oni', 'Onimesh', 'osarker@gmail.com', '123456', 'Male', 'Owner', '', '0000000000'),
(4, 'hori', 'Habibur Hori', 'habiburaiub@gmail.com', '123456', 'Male', 'Owner', 'Kuril, Dhaka', '1700000000'),
(5, 'rubel', 'Rubel', 'rubelmhr@gmail.com', '123456', 'Male', 'Passenger', 'Kuril', '1722222222');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `buses`
--
ALTER TABLE `buses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_usr_bus` (`owner_id`);

--
-- Indexes for table `earnings`
--
ALTER TABLE `earnings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_ear_bus` (`bus_id`);

--
-- Indexes for table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notices`
--
ALTER TABLE `notices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_usrf_not` (`from`),
  ADD KEY `fk_usrt_not` (`recep`);

--
-- Indexes for table `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_usr_tic` (`passenger_id`),
  ADD KEY `fk_bus_tic` (`bus_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`uname`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `buses`
--
ALTER TABLE `buses`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `earnings`
--
ALTER TABLE `earnings`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `notices`
--
ALTER TABLE `notices`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `buses`
--
ALTER TABLE `buses`
  ADD CONSTRAINT `fk_usr_bus` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `earnings`
--
ALTER TABLE `earnings`
  ADD CONSTRAINT `fk_ear_bus` FOREIGN KEY (`bus_id`) REFERENCES `buses` (`id`);

--
-- Constraints for table `notices`
--
ALTER TABLE `notices`
  ADD CONSTRAINT `fk_usrf_not` FOREIGN KEY (`from`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `fk_usrt_not` FOREIGN KEY (`recep`) REFERENCES `users` (`id`);

--
-- Constraints for table `tickets`
--
ALTER TABLE `tickets`
  ADD CONSTRAINT `fk_bus_tic` FOREIGN KEY (`bus_id`) REFERENCES `buses` (`id`),
  ADD CONSTRAINT `fk_usr_tic` FOREIGN KEY (`passenger_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
