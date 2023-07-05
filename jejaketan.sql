-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.4.27-MariaDB - Source distribution
-- Server OS:                    Linux
-- HeidiSQL Version:             12.5.0.6677
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for jejaketan
CREATE DATABASE IF NOT EXISTS `jejaketan` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;
USE `jejaketan`;

-- Dumping structure for table jejaketan.User
CREATE TABLE IF NOT EXISTS `User` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `address` varchar(200) DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table jejaketan.User: ~2 rows (approximately)
INSERT INTO `User` (`user_id`, `email`, `password`, `full_name`, `address`, `phone_number`, `role`, `created_at`, `updated_at`) VALUES
    (1, 'admin@admin.com', '$2a$12$fL.zPXVrGBkqdHNuLW9TteaVknidcvR7Vu8w/66YU0fwsJI0asqZy', 'John Bromoka1', '123 Main St, City', '123456789', 'admin', NULL, '2023-07-03 17:03:02'),
    (2, 'user@user.com', '$2a$12$UzvYfeXCPqJiUPwRfbK9G.6wRwQbUP8VwfuY2EP9nXF52iSntYcNC', 'Nama Pengguna', 'Alamat Pengguna', '987654321', 'user', NULL, '2023-07-03 17:03:02');


-- Dumping structure for table jejaketan.Cart
CREATE TABLE IF NOT EXISTS `Cart` (
  `cart_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`cart_id`),
  KEY `Cart_ibfk_1` (`user_id`),
  CONSTRAINT `Cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `User` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table jejaketan.Cart: ~3 rows (approximately)
INSERT INTO `Cart` (`cart_id`, `user_id`, `created_at`) VALUES
	(5, 1, NULL);

  
-- Dumping structure for table jejaketan.Product
CREATE TABLE IF NOT EXISTS `Product` (
  `product_id` int(11) NOT NULL AUTO_INCREMENT,
  `product_name` varchar(100) NOT NULL,
  `category` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `image_url` varchar(200) DEFAULT NULL,
  `is_featured` enum('Y','N') DEFAULT 'N',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=66 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table jejaketan.Product: ~3 rows (approximately)
INSERT INTO `Product` (`product_id`, `product_name`, `category`, `description`, `price`, `image_url`, `is_featured`, `created_at`, `updated_at`) VALUES
	(59, 'Jaket Parka Reversibel (Water-Repellent)', 'Men\'s Jacket', 'Jaket hoodie Pria dengan desain 2 gaya (reversibel). Terdapat lapisan water-repellent untuk perlindungan di berbagai cuaca.', 599000.00, '64a59a731403e_idgoods_69_453850.avif', 'Y', NULL, '2023-07-05 23:46:19'),
	(60, 'Jaket Blouson Pendek Utilitas', 'Men\'s Jacket', 'Jaket blouson Pria dengan kerah berbahan korduroi dan lapisan dalam beraksen motif kotak. Lengan raglan untuk fit relax.', 999000.00, '64a59aad677b0_idgoods_37_459592.avif', 'Y', NULL, NULL),
	(62, 'Jacket AirSense (Ultra Light)', 'Women\'s Jacket', 'Jas fungsional dengan teknologi UV Protection. Nyaman dikenakan seperti cardigan. Miliki juga koleksi Celana AirSense (Ultra Light) dan padankan sebagai setelan.', 599000.00, '64a5a03fd6bb2_idgoods_69_456073.avif', 'Y', NULL, NULL),
	(65, 'AIRism Jaket Proteksi Sinar UV Retsleting', 'Women\'s Jacket', 'test', 399000.00, '64a5a0e25c29a_idgoods_62_461343.avif', 'N', NULL, '2023-07-05 23:57:24');


-- Dumping structure for table jejaketan.CartItem
CREATE TABLE IF NOT EXISTS `CartItem` (
  `cart_item_id` int(11) NOT NULL AUTO_INCREMENT,
  `cart_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`cart_item_id`),
  KEY `CartItem_ibfk_1` (`cart_id`),
  KEY `CartItem_ibfk_2` (`product_id`),
  CONSTRAINT `CartItem_ibfk_1` FOREIGN KEY (`cart_id`) REFERENCES `Cart` (`cart_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `CartItem_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `Product` (`product_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table jejaketan.CartItem: ~0 rows (approximately)


-- Dumping structure for table jejaketan.Orders
CREATE TABLE IF NOT EXISTS `Orders` (
  `order_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_paid` enum('Y','N') NOT NULL DEFAULT 'N',
  `status` enum('Diproses','Dikirim','Selesai') DEFAULT 'Diproses',
  PRIMARY KEY (`order_id`),
  KEY `Orders_ibfk_1` (`user_id`),
  CONSTRAINT `Orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `User` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table jejaketan.Orders: ~0 rows (approximately)
INSERT INTO `Orders` (`order_id`, `user_id`, `order_date`, `is_paid`, `status`) VALUES
	(32, 1, '2023-07-05 16:39:30', 'Y', 'Diproses'),
	(33, 1, '2023-07-05 16:42:11', 'N', 'Diproses');


-- Dumping structure for table jejaketan.OrderItem
CREATE TABLE IF NOT EXISTS `OrderItem` (
  `order_item_id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `subtotal` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`order_item_id`),
  KEY `OrderItem_ibfk_1` (`order_id`),
  KEY `OrderItem_ibfk_2` (`product_id`),
  CONSTRAINT `OrderItem_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `Orders` (`order_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `OrderItem_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `Product` (`product_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table jejaketan.OrderItem: ~11 rows (approximately)
INSERT INTO `OrderItem` (`order_item_id`, `order_id`, `product_id`, `quantity`, `subtotal`) VALUES
	(35, 32, 59, 1, 599000.00),
	(36, 33, 59, 1, 599000.00);

-- Dumping structure for table jejaketan.Payment
CREATE TABLE IF NOT EXISTS `Payment` (
  `payment_id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`payment_id`),
  KEY `order_id` (`order_id`),
  CONSTRAINT `Payment_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `Orders` (`order_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table jejaketan.Payment: ~0 rows (approximately)
INSERT INTO `Payment` (`payment_id`, `order_id`, `image_url`, `created_at`) VALUES
	(36, 32, 'proof_64a59cc57fbb9.webp', '2023-07-05 16:39:33'),
	(37, 32, 'proof_64a59d4df0b1a.webp', '2023-07-05 16:41:49');

-- Dumping structure for table jejaketan.PaymentMethod
CREATE TABLE IF NOT EXISTS `PaymentMethod` (
  `payment_id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`payment_id`),
  KEY `PaymentMethod_ibfk_1` (`order_id`),
  CONSTRAINT `PaymentMethod_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `Orders` (`order_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table jejaketan.PaymentMethod: ~9 rows (approximately)
INSERT INTO `PaymentMethod` (`payment_id`, `order_id`, `payment_method`) VALUES
	(30, 32, 'bank_mandiri'),
	(31, 33, 'bank_mandiri');

-- Dumping structure for table jejaketan.ShippingDetails
CREATE TABLE IF NOT EXISTS `ShippingDetails` (
  `shipping_id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) DEFAULT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `address` varchar(200) DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`shipping_id`),
  KEY `ShippingDetails_ibfk_1` (`order_id`),
  CONSTRAINT `ShippingDetails_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `Orders` (`order_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table jejaketan.ShippingDetails: ~0 rows (approximately)
INSERT INTO `ShippingDetails` (`shipping_id`, `order_id`, `full_name`, `email`, `address`, `phone_number`) VALUES
	(30, 32, 'John Bromoka1', 'admin@admin.com', '123 Main St, City', '123456789'),
	(31, 33, 'John Bromoka1', 'admin@admin.com', '123 Main St, City', '123456789');



/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
