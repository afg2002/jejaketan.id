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
	(9, 'user@user.com', '$2a$12$UzvYfeXCPqJiUPwRfbK9G.6wRwQbUP8VwfuY2EP9nXF52iSntYcNC', 'Afghan EP', 'Bro', '0851568238645', 'user', '2023-07-03 21:57:31', NULL);

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
	(5, 1, NULL),
	(6, NULL, NULL),
	(7, 9, NULL);

  
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
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table jejaketan.Product: ~4 rows (approximately)
INSERT INTO `Product` (`product_id`, `product_name`, `category`, `description`, `price`, `image_url`, `is_featured`, `created_at`, `updated_at`) VALUES
	(55, 'KIDS Jaket Sweat Blouson', 'Kids\' Jacket', 'Jaket Blouson Anak berukuran pendek yang rapi dengan potongan loose-fit. Cocok untuk berbagai outfit.', 400000.00, '64a2d7bc08a21_goods_31_461413.webp', 'Y', NULL, '2023-07-05 18:38:36'),
	(56, 'AIRism Jaket Proteksi Sinar UV Retsleting', 'Men\'s Jacket', 'Hoodie yang lembut dan nyaman. Sweater kasual yang melindungi Anda dari sinar UV. Dengan UPF 40.', 399000.00, '64a2d8291d692_idgoods_43_455412.avif', 'Y', NULL, NULL),
	(57, 'Jaket Parka Saku Proteksi Sinar UV (Motif)', 'Women\'s Jacket', 'Jaket hoodie Wanita dengan fitur water-repellent untuk membuat Anda tetap kering saat hujan ringan. Dengan fitur UV Protection yang cocok untuk bepergian ke luar rumah. UPF40.', 599000.00, '64a2d88934d7b_idgoods_62_461343.avif', 'Y', NULL, '2023-07-03 21:18:08'),
	(58, 'Obermain Pakaian Jaket Pria Borland In Olive', 'Men\'s Jacket', 'Desain jaket ini sangat cocok untuk pria yang ingin tampil trendi dan sporty. Dengan aksen detail yang terperinci, jaket ini menambahkan sentuhan gaya yang khas. Anda dapat memadukannya dengan celana jeans atau celana pendek untuk menciptakan penampilan yang kasual namun tetap stylish.', 499500.00, '64a31f85c5d66_644bfb9b30da6ea5db14892c1f8611d5073d2e8da48dc5195ff0bf762c34adcc.jpeg', 'N', NULL, '2023-07-04 02:23:49');


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
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table jejaketan.Orders: ~9 rows (approximately)
INSERT INTO `Orders` (`order_id`, `user_id`, `order_date`, `is_paid`, `status`) VALUES
	(20, 1, '2023-07-05 08:55:49', 'Y', 'Dikirim'),
	(22, 9, '2023-07-05 09:03:01', 'N', 'Diproses'),
	(23, 1, '2023-07-05 09:36:26', 'Y', 'Selesai'),
	(24, 1, '2023-07-05 10:45:15', 'N', 'Diproses'),
	(25, 1, '2023-07-05 11:05:20', 'N', 'Diproses'),
	(26, 1, '2023-07-05 11:36:25', 'N', 'Diproses'),
	(27, 1, '2023-07-05 11:36:50', 'N', 'Diproses'),
	(28, 1, '2023-07-05 11:37:38', 'N', 'Diproses'),
	(29, 1, '2023-07-05 15:29:11', 'N', 'Diproses');

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
  CONSTRAINT `OrderItem_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `Product` (`product_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table jejaketan.OrderItem: ~11 rows (approximately)
INSERT INTO `OrderItem` (`order_item_id`, `order_id`, `product_id`, `quantity`, `subtotal`) VALUES
	(21, 20, 55, 1, 400000.00),
	(22, 20, 56, 1, 399000.00),
	(24, 22, 55, 1, 400000.00),
	(25, 23, 55, 1, 400000.00),
	(26, 24, 55, 1, 400000.00),
	(27, 25, 55, 1, 400000.00),
	(28, 25, 58, 1, 499500.00),
	(29, 26, 55, 1, 400000.00),
	(30, 27, 55, 1, 400000.00),
	(31, 28, 58, 1, 499500.00),
	(32, 29, 55, 1, 400000.00);

-- Dumping structure for table jejaketan.Payment
CREATE TABLE IF NOT EXISTS `Payment` (
  `payment_id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`payment_id`),
  KEY `order_id` (`order_id`),
  CONSTRAINT `Payment_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `Orders` (`order_id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table jejaketan.Payment: ~0 rows (approximately)

-- Dumping structure for table jejaketan.PaymentMethod
CREATE TABLE IF NOT EXISTS `PaymentMethod` (
  `payment_id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`payment_id`),
  KEY `PaymentMethod_ibfk_1` (`order_id`),
  CONSTRAINT `PaymentMethod_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `Orders` (`order_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table jejaketan.PaymentMethod: ~9 rows (approximately)
INSERT INTO `PaymentMethod` (`payment_id`, `order_id`, `payment_method`) VALUES
	(18, 20, 'bank_bca'),
	(20, 22, 'bank_mandiri'),
	(21, 23, 'bank_mandiri'),
	(22, 24, 'ewallet_gopay'),
	(23, 25, 'bank_bca'),
	(24, 26, 'bank_bni'),
	(25, 27, 'bank_mandiri'),
	(26, 28, 'bank_mandiri'),
	(27, 29, 'bank_mandiri');

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
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table jejaketan.ShippingDetails: ~9 rows (approximately)
INSERT INTO `ShippingDetails` (`shipping_id`, `order_id`, `full_name`, `email`, `address`, `phone_number`) VALUES
	(18, 20, 'John Bromoka1', 'admin@example.com', '123 Main St, City', '123456789'),
	(20, 22, 'Afghan EP', 'afghanekapangestu@gmail.com', 'Bro', '0851568238645'),
	(21, 23, 'John Bromoka1', 'admin@example.com', '123 Main St, City', '123456789'),
	(22, 24, 'John Bromoka1', 'admin@example.com', '123 Main St, City', '123456789'),
	(23, 25, 'John Bromoka1', 'admin@example.com', '123 Main St, City', '123456789'),
	(24, 26, 'John Bromoka1', 'admin@example.com', '123 Main St, City', '123456789'),
	(25, 27, 'John Bromoka1', 'admin@example.com', '123 Main St, City', '123456789'),
	(26, 28, 'John Bromoka1', 'admin@example.com', '123 Main St, City', '123456789'),
	(27, 29, 'John Bromoka1', 'admin@example.com', '123 Main St, City', '123456789');



/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
