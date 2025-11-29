-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 24, 2025 at 04:48 AM
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
-- Database: `house_rental_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int(11) NOT NULL,
  `property_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `appointment_date` date NOT NULL,
  `appointment_time` time NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `maintenance_requests`
--

CREATE TABLE `maintenance_requests` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `property_id` int(11) NOT NULL,
  `description` text NOT NULL,
  `status` enum('pending','in_progress','resolved','noticed') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `monthly_rentals`
--

CREATE TABLE `monthly_rentals` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `property_id` int(11) NOT NULL,
  `monthly_rent` decimal(10,2) NOT NULL,
  `start_date` date NOT NULL,
  `status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `application_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `payment_date` datetime NOT NULL,
  `payment_status` enum('pending','paid') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `properties`
--

CREATE TABLE `properties` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) NOT NULL,
  `rental_price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `properties`
--

INSERT INTO `properties` (`id`, `name`, `location`, `price`, `image`, `rental_price`) VALUES
(1, 'Rumah Sewa Melur', 'No 1, Lot 601, Jalan Teluk Gadong Kecil, Jln Yadi, 41250 Klang, Selangor', 700.00, 'images/property1.jpg', 0.00),
(2, 'Rumah Sewa Anggerik', 'No 51, Kampung Bechah Semak Bunut Susu, 17020 Pasir Mas, Kelantan', 600.00, 'images/property2.jpg', 0.00),
(3, 'Rumah Sewa Mawar', 'No 19, Kampung Cahaya Baru, 86100 Batu Pahat, Johor', 650.00, 'images/property3.jpg', 0.00),
(4, 'Rumah Sewa Kekwa', 'No 2, Lot 601, Jalan Teluk Gadong Kecil, Jln Yadi, 41250 Klang, Selangor', 750.00, 'images/property4.jpg', 0.00),
(5, 'Rumah Sewa Melati', 'Lot 12160, Lorong Hjh Kasmi, Jalan Sungai Udang, 41250 Klang, Selangor', 700.00, 'images/property5.jpg', 0.00),
(6, 'Rumah Sewa Teratai', 'Lot111, Taman Bukit Cheng 75260, Melaka', 540.00, 'images/property6.jpg', 0.00),
(7, 'Rumah Sewa Cempaka', 'No.25, Jalan Serindit 4, Taman Serindit, 70400 Seremban, Negeri Sembilan', 700.00, 'images/property7.jpg', 0.00),
(8, 'Rumah Sewa Bougainvillea', 'No.10, Jalan Meru 2, Taman Meru, 31400 Ipoh, Perak', 550.00, 'images/property8.jpg', 0.00),
(9, 'Rumah Sewa Orkid', 'No.45, Jalan Lintas, Taman Lintas Jaya, 88450 Kota Kinabalu, Sabah', 600.00, 'images/property9.jpg', 0.00),
(10, 'Rumah Sewa Dahlia', 'No.12, Jalan PJU 1/12, Bandar Petaling Jaya, 47301 Petaling Jaya, Selangor', 800.00, 'images/property10.jpg', 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `property_details`
--

CREATE TABLE `property_details` (
  `id` int(11) NOT NULL,
  `property_id` int(11) DEFAULT NULL,
  `size` int(11) DEFAULT NULL,
  `bedrooms` int(11) DEFAULT NULL,
  `bathrooms` int(11) DEFAULT NULL,
  `garage` tinyint(1) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `year_built` int(11) DEFAULT NULL,
  `available_from` date DEFAULT NULL,
  `parking_space` int(11) DEFAULT NULL,
  `floor` int(11) DEFAULT NULL,
  `pet_friendly` tinyint(1) DEFAULT NULL,
  `furniture` text DEFAULT NULL,
  `neighborhood` text DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `property_details`
--

INSERT INTO `property_details` (`id`, `property_id`, `size`, `bedrooms`, `bathrooms`, `garage`, `description`, `year_built`, `available_from`, `parking_space`, `floor`, `pet_friendly`, `furniture`, `neighborhood`, `status`) VALUES
(3, 1, 1200, 3, 2, 1, 'A beautiful 3-bedroom house with a spacious living room and modern kitchen.', 2015, '2025-01-01', 1, NULL, 1, 'Fully furnished with modern appliances.', 'Located in a peaceful neighborhood with schools nearby.', 'Available'),
(4, 2, 1500, 2, 1, 0, 'A luxurious 2-bedroom cabin house with a large garden and great views.', 2018, '2025-01-01', 1, NULL, 0, 'Partially furnished with basic furniture.', 'Close to shopping malls and public transportation.', 'Available'),
(5, 3, 1200, 2, 1, 1, 'Spacious 2-bedroom house with a private large garden.', 2020, '2025-01-01', 2, 1, 1, 'Fully furnished with basic appliances.', 'Located in a family-friendly neighborhood.', 'Available'),
(6, 4, 2200, 3, 2, 1, 'A grand 6-bedroom villa with ocean views and luxurious interiors.', 2021, '2025-01-01', 1, 1, 1, 'Fully furnished with premium furniture and appliances.', 'Situated in an exclusive gated community.', 'Available'),
(7, 5, 1300, 3, 2, 1, 'Cozy 3-bedroom apartment with modern finishes and great city views.', 2019, '2025-01-01', 2, NULL, 0, 'Partially furnished with basic appliances.', 'Located near shopping centers and public transport.', 'Available'),
(8, 6, 900, 2, 1, 0, 'Affordable 2-bedroom apartment perfect for young professionals.', 2017, '2025-01-01', 1, NULL, 1, 'Unfurnished.', 'Located in a busy commercial area.', 'Available'),
(9, 7, 1600, 3, 2, 1, 'Stylish 4-bedroom townhouse with a modern kitchen and a private garage.', 2016, '2025-01-21', 1, NULL, 1, 'Fully furnished with contemporary designs.', 'Located close to the city center and schools.', 'Available'),
(10, 8, 1100, 3, 2, 0, 'Charming 3-bedroom house with a cozy garden and a beautiful backyard.', 2014, '2025-01-22', 2, NULL, 1, 'Partially furnished with garden furniture.', 'Quiet neighborhood with parks and recreational areas.', 'Available'),
(11, 9, 1400, 4, 3, 1, 'Large 4-bedroom house with a basement and a beautiful front yard.', 2017, '2025-01-01', 2, 1, 1, 'Fully furnished with a mix of modern and classic furniture.', 'Situated in a suburban neighborhood with good schools.', 'Available'),
(12, 10, 950, 2, 1, 1, 'Compact 2-bedroom apartment with a balcony and scenic views.', 2019, '2025-01-01', 1, NULL, 0, 'Furnished with essentials.', 'Located in the heart of the city with easy access to transport.', 'Available');

-- --------------------------------------------------------

--
-- Table structure for table `rental_applications`
--

CREATE TABLE `rental_applications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `property_id` int(11) NOT NULL,
  `tenant_name` varchar(255) NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `current_address` text NOT NULL,
  `income_proof` varchar(255) NOT NULL,
  `job_details` text NOT NULL,
  `start_date` date NOT NULL,
  `supporting_docs` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `rent_amount` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Triggers `rental_applications`
--
DELIMITER $$
CREATE TRIGGER `after_rental_application_approved` AFTER UPDATE ON `rental_applications` FOR EACH ROW BEGIN
    -- Semak jika status berubah kepada 'approved'
    IF NEW.status = 'approved' AND OLD.status != 'approved' THEN
        -- Masukkan data ke dalam monthly_rentals
        INSERT INTO `monthly_rentals` (`user_id`, `property_id`, `monthly_rent`, `start_date`, `status`)
        SELECT 
            (SELECT id FROM users WHERE name = NEW.tenant_name LIMIT 1), -- Cari user_id berdasarkan tenant_name
            NEW.property_id, 
            (SELECT price FROM properties WHERE id = NEW.property_id), 
            CURDATE(),
            'active';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','tenant') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'Fasya', 'fasya@admin.com', 'fasya', 'admin', '2025-01-16 14:55:21');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `property_id` (`property_id`);

--
-- Indexes for table `maintenance_requests`
--
ALTER TABLE `maintenance_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `property_id` (`property_id`);

--
-- Indexes for table `monthly_rentals`
--
ALTER TABLE `monthly_rentals`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `application_id` (`application_id`);

--
-- Indexes for table `properties`
--
ALTER TABLE `properties`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `property_details`
--
ALTER TABLE `property_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `property_id` (`property_id`);

--
-- Indexes for table `rental_applications`
--
ALTER TABLE `rental_applications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `maintenance_requests`
--
ALTER TABLE `maintenance_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `monthly_rentals`
--
ALTER TABLE `monthly_rentals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `properties`
--
ALTER TABLE `properties`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `property_details`
--
ALTER TABLE `property_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `rental_applications`
--
ALTER TABLE `rental_applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `maintenance_requests`
--
ALTER TABLE `maintenance_requests`
  ADD CONSTRAINT `maintenance_requests_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `maintenance_requests_ibfk_2` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`application_id`) REFERENCES `rental_applications` (`id`);

--
-- Constraints for table `property_details`
--
ALTER TABLE `property_details`
  ADD CONSTRAINT `property_details_ibfk_1` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`);

--
-- Constraints for table `rental_applications`
--
ALTER TABLE `rental_applications`
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
