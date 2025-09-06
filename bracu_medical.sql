-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 06, 2025 at 08:42 AM
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
-- Database: `bracu_medical`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `role` varchar(50) DEFAULT NULL,
  `full_name` varchar(150) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `password_hash` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `username`, `role`, `full_name`, `email`, `password_hash`) VALUES
(1, 'admin', 'Chief', 'admin guru', 'admin@admin.com', '$2y$10$/DNjkkWuBSaF.GWibQiEuOREZfpecagQqTqa2bEDTIFQfJgbdPHvK');

-- --------------------------------------------------------

--
-- Table structure for table `blood_donation`
--

CREATE TABLE `blood_donation` (
  `id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `camp_id` int(11) DEFAULT NULL,
  `donation_date` date DEFAULT NULL,
  `bag_count` int(11) DEFAULT 1,
  `flag` int(2) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `blood_donation`
--

INSERT INTO `blood_donation` (`id`, `student_id`, `camp_id`, `donation_date`, `bag_count`, `flag`) VALUES
(1, 2, 1, '2025-08-28', 2, 1),
(2, 3, 2, '2025-08-28', 4, 1),
(3, 4, 3, '2025-08-28', 7, 0),
(4, 2, 2, '2025-08-28', 1, 1),
(5, 2, 3, '2025-08-30', 2, 0),
(6, 2, 3, '2025-08-30', 2, 0),
(8, 4, 2, '2025-08-06', 1, 1),
(9, 4, 1, '2025-08-27', 1, 1),
(10, 6, 2, '2025-09-01', 1, 1),
(11, 2, 4, '2025-09-03', 7, 1),
(12, 3, 3, '2025-09-03', 6, 1),
(13, 2, 2, '2025-09-03', 3, 1),
(16, 3, 3, '2025-09-04', 1, 1),
(17, 2, 3, '2025-09-04', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `blood_donation_camp`
--

CREATE TABLE `blood_donation_camp` (
  `camp_id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `time` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `blood_donation_camp`
--

INSERT INTO `blood_donation_camp` (`camp_id`, `title`, `location`, `date`, `time`) VALUES
(1, 'Multipurpose Hall', 'Ground floor', '2025-08-27', NULL),
(2, 'Medical centre', '1st floor', '2025-08-06', NULL),
(3, 'Exibition hall', '3rd floor', NULL, NULL),
(4, 'Link road', 'Badda', '2025-08-30', '23:43:00'),
(6, 'YoYo', 'Jani na', '2025-09-02', '01:37:00'),
(7, 'YoYo', 'Jani na', '2025-09-02', '01:37:00'),
(8, 'YoYo', 'Jani na', '2025-09-02', '01:37:00'),
(9, 'YoYo', 'Jani na', '2025-09-02', '01:37:00');

-- --------------------------------------------------------

--
-- Table structure for table `blood_request`
--

CREATE TABLE `blood_request` (
  `req_id` int(11) NOT NULL,
  `name` varchar(150) DEFAULT NULL,
  `phone_no` varchar(40) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `blood_group` varchar(10) DEFAULT NULL,
  `urgency_level` enum('low','medium','high') DEFAULT 'medium',
  `status` enum('open','matched','fulfilled') DEFAULT 'open',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `blood_request`
--

INSERT INTO `blood_request` (`req_id`, `name`, `phone_no`, `location`, `blood_group`, `urgency_level`, `status`, `created_at`) VALUES
(1, 'hhjhjhjh', '111111', 'Badda', 'A+', 'high', '', '2025-09-04 18:25:23'),
(2, 'Zisan', '1', 'Badda', 'A+', 'medium', '', '2025-09-04 18:38:02'),
(3, 'Shishir', '1', 'Cafe', 'A+', 'high', 'open', '2025-09-05 20:47:37');

-- --------------------------------------------------------

--
-- Table structure for table `certification`
--

CREATE TABLE `certification` (
  `certification_id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `badge_name` varchar(100) DEFAULT NULL,
  `badge_icon` varchar(255) DEFAULT NULL,
  `training_type` varchar(100) DEFAULT NULL,
  `date_awarded` date DEFAULT NULL,
  `badge_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `certification`
--

INSERT INTO `certification` (`certification_id`, `student_id`, `badge_name`, `badge_icon`, `training_type`, `date_awarded`, `badge_id`) VALUES
(2, 10, 'CPR', 'img/CPR.png', 'CPR', '2025-09-04', 0),
(3, 2, 'CPR', 'img/CPR.png', 'CPR', '2025-09-04', 0),
(4, 2, 'CPR', 'img/CPR.png', 'CPR', '2025-09-04', 0),
(5, 2, 'CPR', 'img/CPR.png', 'CPR', '2025-09-04', 0),
(6, 2, 'CPR', 'img/CPR.png', 'CPR', '2025-09-04', 0),
(7, 3, 'CPR', 'img/CPR.png', 'CPR', '2025-09-04', 0),
(8, 1, 'CPR', 'img/CPR.png', 'CPR', '2025-09-04', 0),
(9, 2, 'CPR', NULL, 'CPR', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `d_blood`
--

CREATE TABLE `d_blood` (
  `name` varchar(255) NOT NULL,
  `student_id` varchar(50) NOT NULL,
  `camp_id` varchar(50) NOT NULL,
  `blood_score` int(10) NOT NULL,
  `blood_group` varchar(5) NOT NULL,
  `flag` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `d_blood`
--

INSERT INTO `d_blood` (`name`, `student_id`, `camp_id`, `blood_score`, `blood_group`, `flag`) VALUES
('Apurbo', '2', '', 47, '', 1),
('Tasmim', '3', '3', 70, '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `d_financial`
--

CREATE TABLE `d_financial` (
  `id` int(11) NOT NULL,
  `name` varchar(150) DEFAULT NULL,
  `finance_score` decimal(10,2) DEFAULT NULL,
  `flag` varchar(50) DEFAULT NULL,
  `student_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `d_financial`
--

INSERT INTO `d_financial` (`id`, `name`, `finance_score`, `flag`, `student_id`) VALUES
(1, 'a', 2.00, '0', 1),
(3, 'Apurbo', 9.30, '1', 2),
(4, 'Shishir', 0.10, '1', 4);

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `admin_id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `rating` tinyint(4) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `given_by` varchar(150) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `flag` int(2) NOT NULL DEFAULT 0,
  `phone` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`admin_id`, `student_id`, `rating`, `comment`, `given_by`, `created_at`, `flag`, `phone`) VALUES
(0, 3, 5, 'Very Good', 'Apurbo', '2025-08-27 17:38:37', 0, NULL),
(0, 3, 5, 'Good (Phone: 01760202953)', 'A', '2025-08-27 17:49:47', 0, NULL),
(0, 3, 4, 'anything (Phone: 0180000000)', 'Diba', '2025-08-28 05:45:01', 0, NULL),
(0, NULL, 5, 'a', 'Apurbo', '2025-09-01 09:59:31', 1, '1760202953'),
(0, 1, 5, 'www', 'Apurbo', '2025-09-03 18:42:02', 0, '1760202953'),
(0, 1, 5, 'www', 'Apurbo', '2025-09-03 18:43:37', 0, '1760202953'),
(0, NULL, 5, 'sssss', 'Apurbo', '2025-09-03 18:44:03', 0, '1760202953'),
(0, 1, 5, 'www', 'Apurbo', '2025-09-03 18:46:53', 0, '1760202953'),
(0, 1, 5, '1111', 'Apurbo', '2025-09-03 18:47:00', 0, '1760202953'),
(0, 1, 5, '1111', 'Apurbo', '2025-09-03 18:48:45', 0, '1760202953'),
(0, 1, 5, '1111', 'Apurbo', '2025-09-03 18:50:27', 0, '1760202953'),
(0, 1, 5, '1111', 'Apurbo', '2025-09-03 18:51:09', 0, '1760202953'),
(0, 1, 5, '1111', 'Apurbo', '2025-09-03 18:51:30', 0, '1760202953'),
(0, 1, 5, '1111', 'Apurbo', '2025-09-03 18:51:42', 0, '1760202953'),
(0, 4, 3, 'dadd', 'Diba', '2025-09-04 06:45:31', 0, '0180000000'),
(0, 4, 3, 'dadd', 'Diba', '2025-09-04 06:51:00', 0, '0180000000'),
(0, 1, 5, 'adasdasd', 'Apurbo', '2025-09-04 17:44:47', 0, '1760202953'),
(0, 1, 5, 'adasdasd', 'Apurbo', '2025-09-04 17:48:19', 1, '1760202953'),
(0, 1, 5, 'asasasas', 'Anonymous', '2025-09-04 17:49:09', 0, 'Anonymous'),
(0, 1, 5, '122121', 'aaa', '2025-09-04 17:49:30', 1, '1212121212'),
(0, 1, 5, '122121', 'aaa', '2025-09-04 17:52:07', 1, '1212121212'),
(0, 1, 5, 'daddad', 'dasd', '2025-09-04 17:56:37', 1, '01760202953'),
(0, 2, 5, '111111111', 'adsadasd', '2025-09-04 18:00:06', 1, '111111111111'),
(0, 2, 5, 'asdasdad', 'ddasd', '2025-09-04 18:22:29', 1, 'asdasdas');

-- --------------------------------------------------------

--
-- Table structure for table `f_donation`
--

CREATE TABLE `f_donation` (
  `f_id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `flag` int(2) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `f_donation`
--

INSERT INTO `f_donation` (`f_id`, `student_id`, `amount`, `date`, `flag`) VALUES
(1, 2, 500.00, '2025-08-27', 0),
(2, 1, 1000.00, '2025-08-27', 1),
(3, 2, 1.00, '2025-08-28', 0),
(4, 3, 2.00, '2025-08-28', 1),
(5, 2, 40.00, '2025-08-28', 0),
(6, 2, 80.00, '2025-08-30', 1),
(7, 2, 7000.00, '2025-08-30', 0),
(8, 2, 70.00, '2025-08-30', 0),
(9, 2, 50.00, '2025-08-30', 1),
(19, 2, 10.00, '2025-09-03', 1),
(20, 2, 30.00, '2025-09-03', 1),
(21, 2, 50.00, '2025-09-03', 1),
(22, 2, 500.00, '2025-09-03', 1),
(23, 2, 400.00, '2025-09-03', 0),
(24, 2, 30.00, '2025-09-03', 1),
(25, 4, 10.00, '2025-09-04', 1);

-- --------------------------------------------------------

--
-- Table structure for table `register_session`
--

CREATE TABLE `register_session` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `session_id` int(11) DEFAULT NULL,
  `status` enum('pending','confirmed','completed') DEFAULT 'pending',
  `registered_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `register_session`
--

INSERT INTO `register_session` (`id`, `student_id`, `session_id`, `status`, `registered_at`) VALUES
(1, 3, 1, 'pending', '2025-08-28 17:44:36'),
(2, 2, 1, 'completed', '2025-08-28 17:45:03'),
(4, 4, 1, 'confirmed', '2025-08-30 19:44:42'),
(19, 10, 1, 'completed', '2025-09-04 16:42:23'),
(23, 10, 1, 'pending', '2025-09-04 17:27:25'),
(24, 10, 2, 'pending', '2025-09-04 17:27:27'),
(25, 10, 1, 'completed', '2025-09-04 17:27:36'),
(26, 2, 2, 'pending', '2025-09-04 17:31:25'),
(27, 3, 2, 'pending', '2025-09-04 17:32:20');

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `student_id` int(11) NOT NULL,
  `full_name` varchar(150) NOT NULL,
  `gsuit` varchar(150) DEFAULT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `area` varchar(150) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `blood_group` varchar(10) DEFAULT NULL,
  `available` enum('available','not available') DEFAULT 'available',
  `profile_url` varchar(255) DEFAULT NULL,
  `last_donation_date` date DEFAULT NULL,
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`student_id`, `full_name`, `gsuit`, `phone`, `area`, `city`, `blood_group`, `available`, `profile_url`, `last_donation_date`, `username`, `password`, `created_at`) VALUES
(1, 'Rifat Hossain', 'rifat@bracu.edu', '018XXXXXXXX', 'Dhanmondi', 'Dhaka', 'A+', 'available', NULL, NULL, 'rifat', '$2y$10$7VGM/tG.8T3zxccJC4i.U.TU8QuS9R09mgRTdlCne7r5WNgW7xAoK', '2025-08-27 17:17:37'),
(2, 'Apurbo', 'apurbo@gmail.com', '01760202953', 'Badda', NULL, 'A+', 'not available', NULL, '2025-09-04', 'apurbo', '$2y$10$7VGM/tG.8T3zxccJC4i.U.TU8QuS9R09mgRTdlCne7r5WNgW7xAoK', '2025-08-27 17:26:35'),
(3, 'Tasmim', 'tasmim@gmail.com', '01975124857', 'Kallyanpur', NULL, 'B+', 'available', NULL, '2025-09-01', 'ratri', '$2y$10$7VGM/tG.8T3zxccJC4i.U.TU8QuS9R09mgRTdlCne7r5WNgW7xAoK', '2025-08-27 17:30:20'),
(4, 'Shishir', 'shishir@gmail.com', '01521728063', 'Noyatola', NULL, 'O+', 'available', NULL, '2025-08-27', 'shishir', '$2y$10$7VGM/tG.8T3zxccJC4i.U.TU8QuS9R09mgRTdlCne7r5WNgW7xAoK', '2025-08-28 10:21:11'),
(6, 'eleen', 'abc@gmail.com', '0124004467', 'aftabnagar', 'Dhaka', 'B-', 'available', 'kkkk', NULL, 'eleen', '$2y$10$7VGM/tG.8T3zxccJC4i.U.TU8QuS9R09mgRTdlCne7r5WNgW7xAoK', '2025-09-01 09:23:30'),
(10, 'Proyash', 'proyash@gmail.com', '11111111111111', 'Badda', 'Dhaka', 'A+', '', 's', '2025-09-02', 'proyash', '$2y$10$FGF6QhQyCAxGfsBnaWAvAOZnoEHuSNwl5pQc3VtGcaRxRnVzv3XL.', '2025-09-04 12:23:17'),
(23301300, 'Apurbo Bhaket', 'abc@gmail.com', '01760202953', 'Badda', 'Dhaka', 'A+', 'available', 'a', NULL, 'bhaket', '$2y$10$R7FvY2ZQ3J7B5NTJcA0FKesb5NpAONhNbDbaVTEQQq9OTIqHUAd/u', '2025-09-04 17:06:54');

-- --------------------------------------------------------

--
-- Table structure for table `training_session`
--

CREATE TABLE `training_session` (
  `session_id` int(11) NOT NULL,
  `title` varchar(200) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `time` time DEFAULT NULL,
  `status` enum('open','full','done') DEFAULT 'open',
  `capacity` int(11) DEFAULT 50,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `training_session`
--

INSERT INTO `training_session` (`session_id`, `title`, `location`, `date`, `time`, `status`, `capacity`, `created_by`, `created_at`) VALUES
(1, 'CPR', 'Cafe', '2025-08-30', '23:49:00', 'open', 50, 1, '2025-08-28 17:44:30'),
(2, 'ECG', 'Fulkoli', '2025-09-05', '01:51:00', 'open', 50, 1, '2025-08-30 19:45:37');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `blood_donation`
--
ALTER TABLE `blood_donation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `camp_id` (`camp_id`);

--
-- Indexes for table `blood_donation_camp`
--
ALTER TABLE `blood_donation_camp`
  ADD PRIMARY KEY (`camp_id`);

--
-- Indexes for table `blood_request`
--
ALTER TABLE `blood_request`
  ADD PRIMARY KEY (`req_id`);

--
-- Indexes for table `certification`
--
ALTER TABLE `certification`
  ADD PRIMARY KEY (`certification_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `badge_id` (`certification_id`);

--
-- Indexes for table `d_blood`
--
ALTER TABLE `d_blood`
  ADD PRIMARY KEY (`student_id`);

--
-- Indexes for table `d_financial`
--
ALTER TABLE `d_financial`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `f_donation`
--
ALTER TABLE `f_donation`
  ADD PRIMARY KEY (`f_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `register_session`
--
ALTER TABLE `register_session`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `session_id` (`session_id`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`student_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `training_session`
--
ALTER TABLE `training_session`
  ADD PRIMARY KEY (`session_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `blood_donation`
--
ALTER TABLE `blood_donation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `blood_donation_camp`
--
ALTER TABLE `blood_donation_camp`
  MODIFY `camp_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `blood_request`
--
ALTER TABLE `blood_request`
  MODIFY `req_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `certification`
--
ALTER TABLE `certification`
  MODIFY `certification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `d_financial`
--
ALTER TABLE `d_financial`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `f_donation`
--
ALTER TABLE `f_donation`
  MODIFY `f_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `register_session`
--
ALTER TABLE `register_session`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `student`
--
ALTER TABLE `student`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23301302;

--
-- AUTO_INCREMENT for table `training_session`
--
ALTER TABLE `training_session`
  MODIFY `session_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `blood_donation`
--
ALTER TABLE `blood_donation`
  ADD CONSTRAINT `blood_donation_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student` (`student_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `blood_donation_ibfk_2` FOREIGN KEY (`camp_id`) REFERENCES `blood_donation_camp` (`camp_id`) ON DELETE SET NULL;

--
-- Constraints for table `certification`
--
ALTER TABLE `certification`
  ADD CONSTRAINT `certification_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student` (`student_id`) ON DELETE CASCADE;

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student` (`student_id`) ON DELETE CASCADE;

--
-- Constraints for table `f_donation`
--
ALTER TABLE `f_donation`
  ADD CONSTRAINT `f_donation_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student` (`student_id`) ON DELETE SET NULL;

--
-- Constraints for table `register_session`
--
ALTER TABLE `register_session`
  ADD CONSTRAINT `register_session_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student` (`student_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `register_session_ibfk_2` FOREIGN KEY (`session_id`) REFERENCES `training_session` (`session_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
