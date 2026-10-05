-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 05, 2026 at 08:50 AM
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
-- Database: `homelink_ke`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `full_name` varchar(100) DEFAULT NULL,
  `role` enum('super_admin','admin') NOT NULL DEFAULT 'admin',
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `created_at`, `full_name`, `role`, `active`, `updated_at`) VALUES
(1, 'admin', '$2y$10$A3dixhqNXDyDKaUnR.wUtujLxHEIetK.TmZ6Ixxr/9phESanzxBHa', '2026-09-24 16:58:16', NULL, 'super_admin', 1, '2026-10-01 06:14:15');

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` int(11) NOT NULL,
  `actor` varchar(100) NOT NULL,
  `action` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `audit_logs`
--

INSERT INTO `audit_logs` (`id`, `actor`, `action`, `created_at`) VALUES
(1, 'admin', 'Admin login', '2026-09-28 08:44:47'),
(2, 'admin', 'Updated property #2', '2026-09-28 08:45:36'),
(3, 'admin', 'Updated property #1', '2026-09-28 08:46:14'),
(4, 'admin', 'Toggled admin #1', '2026-09-28 08:47:38'),
(5, 'admin', 'Toggled admin #1', '2026-09-28 08:47:41'),
(6, 'admin', 'Toggled admin #1', '2026-09-28 08:47:42'),
(7, 'admin', 'Toggled admin #1', '2026-09-28 08:47:44'),
(8, 'admin', 'Updated website settings', '2026-09-28 08:48:19'),
(9, 'admin', 'New viewing request submitted for property #5', '2026-09-28 08:49:59'),
(10, 'admin', 'Admin login', '2026-09-29 12:06:43'),
(11, 'admin', 'Admin login', '2026-09-29 12:13:08'),
(12, 'admin', 'Updated property #4', '2026-09-29 12:15:29'),
(13, 'admin', 'Show property #4', '2026-09-29 12:16:11'),
(14, 'admin', 'Updated property #9', '2026-09-30 07:11:34'),
(15, 'admin', 'Added property #10', '2026-09-30 07:15:02'),
(16, 'admin', 'Updated property #10', '2026-09-30 07:16:54'),
(17, 'admin', 'Admin login', '2026-10-01 06:06:41'),
(18, 'admin', 'Updated property #3', '2026-10-01 06:08:30'),
(19, 'admin', 'Added property #11', '2026-10-01 06:10:57'),
(20, 'admin', 'Admin login', '2026-10-01 06:13:01'),
(21, 'admin', 'Verify property #11', '2026-10-01 06:13:37'),
(22, 'admin', 'Changed own admin password', '2026-10-01 06:14:15'),
(23, 'admin', 'Admin login', '2026-10-01 06:17:48'),
(24, 'admin', 'Admin login', '2026-10-01 06:20:34');

-- --------------------------------------------------------

--
-- Table structure for table `bnb_requests`
--

CREATE TABLE `bnb_requests` (
  `id` int(11) NOT NULL,
  `property_id` int(11) DEFAULT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(30) NOT NULL,
  `check_in` date NOT NULL,
  `check_out` date NOT NULL,
  `guests` int(11) NOT NULL DEFAULT 1,
  `notes` text DEFAULT NULL,
  `status` enum('pending','confirmed','checked_in','completed','cancelled') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `complaints`
--

CREATE TABLE `complaints` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `phone` varchar(30) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `details` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('open','in_progress','resolved','closed') NOT NULL DEFAULT 'open'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inquiries`
--

CREATE TABLE `inquiries` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `property_id` int(11) DEFAULT NULL,
  `status` enum('new','read','closed') NOT NULL DEFAULT 'new'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inquiries`
--

INSERT INTO `inquiries` (`id`, `name`, `email`, `subject`, `message`, `created_at`, `property_id`, `status`) VALUES
(1, 'Saviours', 'dioursmuzimi@gmail.com', 'bedsitter', 'beddsiter arround mwembe', '2026-10-01 05:46:53', 0, 'new');

-- --------------------------------------------------------

--
-- Table structure for table `landlord_requests`
--

CREATE TABLE `landlord_requests` (
  `id` int(11) NOT NULL,
  `landlord_name` varchar(100) NOT NULL,
  `phone` varchar(30) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `property_type` varchar(50) DEFAULT NULL,
  `location` varchar(255) NOT NULL,
  `details` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('new','contacted','listed','closed') NOT NULL DEFAULT 'new'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `landlord_requests`
--

INSERT INTO `landlord_requests` (`id`, `landlord_name`, `phone`, `email`, `property_type`, `location`, `details`, `created_at`, `status`) VALUES
(1, 'sm', '0794137618', '', 'Single Rooms', 'mosocho', '', '2026-09-28 08:51:20', 'new');

-- --------------------------------------------------------

--
-- Table structure for table `properties`
--

CREATE TABLE `properties` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `status` enum('available','rented','sold') DEFAULT 'available',
  `description` text DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `deposit_info` varchar(255) DEFAULT NULL,
  `bedrooms` int(11) DEFAULT NULL,
  `furnished_status` enum('Furnished','Unfurnished','Semi-Furnished') DEFAULT 'Unfurnished',
  `amenities` text DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `is_verified` tinyint(1) DEFAULT 0,
  `is_hidden` tinyint(1) DEFAULT 0,
  `landlord_name` varchar(255) DEFAULT NULL,
  `landlord_phone` varchar(50) DEFAULT NULL,
  `landlord_email` varchar(255) DEFAULT NULL,
  `landlord_notes` text DEFAULT NULL,
  `video_path` varchar(255) DEFAULT NULL,
  `availability` varchar(50) NOT NULL DEFAULT 'Available',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `properties`
--

INSERT INTO `properties` (`id`, `title`, `location`, `price`, `status`, `description`, `image_url`, `created_at`, `deposit_info`, `bedrooms`, `furnished_status`, `amenities`, `category`, `is_verified`, `is_hidden`, `landlord_name`, `landlord_phone`, `landlord_email`, `landlord_notes`, `video_path`, `availability`, `updated_at`) VALUES
(1, 'Modern 1-Bedroom Apartment', 'Nyanchwa, Kisii Town', 12000.00, 'available', 'Spacious 1-bedroom unit with modern finishes, 24/7 security, and reliable water supply. 5-minute drive to Kisii CBD.', NULL, '2026-09-24 16:58:42', '', NULL, 'Unfurnished', '0', 'Single Rooms', 1, 0, 'sm', '0712345678', '', '', NULL, 'Available', '2026-09-28 08:46:14'),
(2, 'Executive Student Bedsitter', 'Near Kisii University Main Gate', 6666.00, 'available', 'Clean and secure bedsitter with tiled floors, inside sink, and high-speed Wi-Fi access. Ideal for Kisii University students.', NULL, '2026-09-24 16:58:42', '', NULL, 'Unfurnished', '0', 'Single Rooms', 1, 0, 'zi', '0712345678', '', '', NULL, 'Available', '2026-09-28 08:45:36'),
(3, 'Commercial Shop / Office Space', 'Kisii Town CBD (Hospital Road)', 25000.00, 'available', 'Prime commercial space on the 1st floor along busy street. Great foot traffic and suitable for boutique, salon, or office.', NULL, '2026-09-24 16:58:42', '', NULL, 'Unfurnished', '0', 'Single Rooms', 1, 0, 'sm', '0700000000', 'sm@123', '', NULL, 'Available', '2026-10-01 06:08:30'),
(4, '2-Bedroom Master Ensuite', 'Mosocho / Kisii-Kisumu Highway', 18000.00, 'available', 'Secure perimeter wall, ample parking space, constant water flow, and modern kitchen cabinets.', NULL, '2026-09-24 16:58:42', '', NULL, 'Unfurnished', '0', 'Single Rooms', 1, 0, 'sm', '0712345678', '', '', NULL, 'Available', '2026-09-29 12:16:11'),
(5, 'single', 'mwembe', 4500.00, 'available', '', '../uploads/photos/1790427134_121_123.jpg', '2026-09-26 12:52:14', '1500', NULL, 'Unfurnished', 'wifi', 'Single Rooms', 1, 0, 'sm', '0712345678', '', '', '', 'Available', '2026-09-28 08:43:17'),
(6, 'singel', 'mwembe', 4500.00, 'available', '', '../uploads/photos/1790427216_171_123.jpg', '2026-09-26 12:53:36', '1500', NULL, 'Unfurnished', 'wifi', 'Single Rooms', 1, 0, 'sm', '0712345678', '', '', '', 'Available', '2026-09-28 08:43:17'),
(8, 'single rooms', 'mwembe', 4500.00, 'available', '', '../uploads/photos/1790427431_506_123.jpg', '2026-09-26 12:57:11', '1500', NULL, 'Unfurnished', 'wifi', 'Single Rooms', 1, 0, 'sm', '0712345678', '', '', '', 'Available', '2026-09-28 08:43:17'),
(9, 'bedsitter', 'mwembe', 7600.00, '', '', '../uploads/photos/1790541261_609_IMG_17750240742403833.jpg', '2026-09-26 19:23:25', '1500', NULL, 'Unfurnished', '0', 'Bedsitters', 1, 0, 'zi', '0712345678', '', '', '', 'Available', '2026-09-30 07:11:34'),
(10, 'speed hostels', 'kisumu ndogo', 20000.00, 'available', '', NULL, '2026-09-30 07:15:02', '10000', NULL, 'Furnished', '0', '1 Bedroom', 1, 0, 'speeddeveloper', '0700000000', 'pavioursmuzimi@gmail.com', '', NULL, 'Available', '2026-09-30 07:16:54'),
(11, 'sigle room', 'kisumu ndogo', 6500.00, 'available', '', NULL, '2026-10-01 06:10:57', '1500', NULL, 'Unfurnished', '0', 'Single Rooms', 1, 0, 'sm', '0712345678', 'sm@123', '', NULL, 'Available', '2026-10-01 06:13:37');

-- --------------------------------------------------------

--
-- Table structure for table `property_photos`
--

CREATE TABLE `property_photos` (
  `id` int(11) NOT NULL,
  `property_id` int(11) NOT NULL,
  `photo_path` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `property_photos`
--

INSERT INTO `property_photos` (`id`, `property_id`, `photo_path`) VALUES
(1, 5, '../uploads/photos/1790427134_121_123.jpg'),
(2, 6, '../uploads/photos/1790427216_171_123.jpg'),
(4, 8, '../uploads/photos/1790427431_506_123.jpg'),
(6, 9, '../uploads/photos/1790541261_609_IMG_17750240742403833.jpg'),
(7, 9, '../uploads/photos/1790541261_732_IMG_17750252460960083.jpg'),
(8, 9, '../uploads/photos/1790541261_862_IMG_17750253321153812.jpg'),
(9, 5, 'uploads/photos/1790427134_121_123.jpg'),
(10, 6, 'uploads/photos/1790427216_171_123.jpg'),
(11, 8, 'uploads/photos/1790427431_506_123.jpg'),
(12, 9, 'uploads/photos/1790541261_609_IMG_17750240742403833.jpg'),
(13, 9, 'uploads/photos/1790541261_732_IMG_17750252460960083.jpg'),
(14, 9, 'uploads/photos/1790541261_862_IMG_17750253321153812.jpg'),
(15, 2, 'uploads/photos/eb8abbcc61a23ee9.png'),
(16, 1, 'uploads/photos/f6f2c22a31e494d5.png'),
(17, 4, 'uploads/photos/a5dc1ded194284af.jpg'),
(18, 4, 'uploads/photos/dd8df8d3eb1e3533.jpg'),
(19, 4, 'uploads/photos/e87aa4c2f334a889.jpg'),
(20, 10, 'uploads/photos/f8061d01fcbf66c4.jpg'),
(21, 3, 'uploads/photos/58ef7fe619fb6dbc.jpg'),
(22, 11, 'uploads/photos/cf1bd8348eb8d411.jpg'),
(23, 11, 'uploads/photos/62d37e4bb87e3a35.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `service_fees`
--

CREATE TABLE `service_fees` (
  `id` int(11) NOT NULL,
  `property_type` varchar(100) NOT NULL,
  `fee_type` enum('fixed','percentage') NOT NULL DEFAULT 'fixed',
  `amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `service_fees`
--

INSERT INTO `service_fees` (`id`, `property_type`, `fee_type`, `amount`, `active`, `updated_at`) VALUES
(1, 'Single Rooms', 'fixed', 500.00, 1, '2026-09-28 08:43:17'),
(2, 'Bedsitters', 'fixed', 750.00, 1, '2026-09-28 08:43:17'),
(3, '1 Bedroom', 'fixed', 1000.00, 1, '2026-09-28 08:43:17'),
(4, '2 Bedroom', 'fixed', 1500.00, 1, '2026-09-28 08:43:17'),
(5, 'Commercial', 'fixed', 2000.00, 1, '2026-09-28 08:43:17'),
(6, 'BNB', 'fixed', 500.00, 1, '2026-09-28 08:43:17');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `site_name` varchar(100) DEFAULT 'HomeLink',
  `site_tagline` varchar(50) DEFAULT '-KE',
  `site_logo` varchar(255) DEFAULT 'assets/images/logo.png',
  `contact_phone` varchar(20) DEFAULT '0712345678',
  `contact_email` varchar(100) DEFAULT 'info@homelink.co.ke',
  `contact_location` varchar(100) DEFAULT 'Kisii Town, Kenya',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `site_name`, `site_tagline`, `site_logo`, `contact_phone`, `contact_email`, `contact_location`, `updated_at`) VALUES
(1, 'HomeLink', '-KE', 'assets/images/logo.png', '+254 712 345 678', 'info@homelink.co.ke', 'Kisii Town, Kenya', '2026-09-27 07:36:53');

-- --------------------------------------------------------

--
-- Table structure for table `site_settings`
--

CREATE TABLE `site_settings` (
  `id` int(11) NOT NULL DEFAULT 1,
  `site_name` varchar(150) NOT NULL DEFAULT 'HomeLink-KE',
  `site_logo` varchar(255) DEFAULT '',
  `contact_email` varchar(100) NOT NULL DEFAULT 'info@homelink.co.ke',
  `contact_phone` varchar(50) NOT NULL DEFAULT '+254 700 000000',
  `office_location` varchar(255) NOT NULL DEFAULT 'Kisii CBD, Kenya',
  `mpesa_paybill` varchar(50) DEFAULT '247247',
  `mpesa_account` varchar(100) DEFAULT 'HomeLink',
  `viewing_fee` decimal(10,2) DEFAULT 1000.00,
  `maintenance_mode` tinyint(1) DEFAULT 0,
  `footer_text` varchar(255) DEFAULT '© 2026 HomeLink-KE. All rights reserved.',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `facebook_link` varchar(255) DEFAULT '',
  `twitter_link` varchar(255) DEFAULT '',
  `whatsapp_number` varchar(50) DEFAULT '',
  `instagram_link` varchar(255) DEFAULT '',
  `site_tagline` varchar(100) DEFAULT '-KE',
  `contact_location` varchar(255) DEFAULT 'Kisii CBD, Kenya'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `site_settings`
--

INSERT INTO `site_settings` (`id`, `site_name`, `site_logo`, `contact_email`, `contact_phone`, `office_location`, `mpesa_paybill`, `mpesa_account`, `viewing_fee`, `maintenance_mode`, `footer_text`, `updated_at`, `facebook_link`, `twitter_link`, `whatsapp_number`, `instagram_link`, `site_tagline`, `contact_location`) VALUES
(1, 'HomeLink-KE', 'uploads/system/logo_c2f32f3e2774.png', 'info@homelink.co.ke', '+254 700 000000', 'Kisii CBD, Kenya', '', '', 999.99, 0, '© 2026 HomeLink-KE. All rights reserved.', '2026-09-28 08:48:19', '', '', '0712345678', '', '-KE', 'Kisii CBD, Kenya');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(20) DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `viewing_requests`
--

CREATE TABLE `viewing_requests` (
  `id` int(11) NOT NULL,
  `property_id` int(11) DEFAULT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(30) NOT NULL,
  `view_date` date NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','confirmed','completed','cancelled') NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `viewing_requests`
--

INSERT INTO `viewing_requests` (`id`, `property_id`, `full_name`, `email`, `phone`, `view_date`, `notes`, `created_at`, `status`) VALUES
(1, 5, 'sm', '', '0794137618', '2026-09-29', '', '2026-09-28 08:49:59', 'pending');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bnb_requests`
--
ALTER TABLE `bnb_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `property_id` (`property_id`),
  ADD KEY `status` (`status`,`check_in`,`check_out`);

--
-- Indexes for table `complaints`
--
ALTER TABLE `complaints`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inquiries`
--
ALTER TABLE `inquiries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `landlord_requests`
--
ALTER TABLE `landlord_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `properties`
--
ALTER TABLE `properties`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `property_photos`
--
ALTER TABLE `property_photos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `property_id` (`property_id`);

--
-- Indexes for table `service_fees`
--
ALTER TABLE `service_fees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `property_type` (`property_type`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `site_settings`
--
ALTER TABLE `site_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `viewing_requests`
--
ALTER TABLE `viewing_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_viewing_properties` (`property_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `bnb_requests`
--
ALTER TABLE `bnb_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `complaints`
--
ALTER TABLE `complaints`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inquiries`
--
ALTER TABLE `inquiries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `landlord_requests`
--
ALTER TABLE `landlord_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `properties`
--
ALTER TABLE `properties`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `property_photos`
--
ALTER TABLE `property_photos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `service_fees`
--
ALTER TABLE `service_fees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `viewing_requests`
--
ALTER TABLE `viewing_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bnb_requests`
--
ALTER TABLE `bnb_requests`
  ADD CONSTRAINT `bnb_requests_ibfk_1` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `property_photos`
--
ALTER TABLE `property_photos`
  ADD CONSTRAINT `property_photos_ibfk_1` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `viewing_requests`
--
ALTER TABLE `viewing_requests`
  ADD CONSTRAINT `fk_viewing_properties` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
