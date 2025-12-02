-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Oct 02, 2025 at 10:23 AM
-- Server version: 8.0.30
-- PHP Version: 8.2.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_gayamitacake`
--

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int NOT NULL,
  `nama` varchar(100) NOT NULL,
  `harga` varchar(20) NOT NULL,
  `deskripsi` text NOT NULL,
  `rating` decimal(10,0) NOT NULL,
  `image` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `nama`, `harga`, `deskripsi`, `rating`, `image`) VALUES
(1, 'Chocolate Cake', '150000', 'Delicious chocolate cake with rich chocolate frosting.', 5, 'images/product/chocolatecake.png'),
(2, 'Cheese Cake', '170000', 'Creamy cheese cake with a crunchy base.', 5, 'images/product/cheesecake.png'),
(3, 'Red Velvet Cake', '160000', 'Classic red velvet cake with cream cheese frosting', 5, 'images/product/redvelvetcake.png'),
(4, 'Cheese Cake', '170000', 'Creamy cheese cake with a crunchy base.', 5, 'images/product/cheesecake.png'),
(5, 'Red Velvet Cake', '160000', 'Classic red velvet cake with cream cheese frosting', 5, 'images/product/redvelvetcake.png'),
(6, 'Vanilla Cupcake', '30000', 'Light and fluffy vanilla cupcake with buttercream', 4, 'images/product/vanilla-cupcake.png'),
(7, 'Lemon Tart', '40000', 'Tangy lemon tart with a buttery crust.', 4, 'images/product/lemon-tart.png'),
(8, 'Fruit Cake', '180000', 'Moist fruit cake loaded with dried fruits and nuts.', 5, 'images/product/fruit-cake.png'),
(9, 'Black Forest Cake', '200000', 'Rich chocolate cake layered with cherries and whipped cream.', 5, 'images/product/black-forest.png'),
(10, 'Pouch Cake', '80000', 'Cake Mantap', 5, 'images/product/pouch-cake.png');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int NOT NULL,
  `rating` decimal(10,0) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `deskripsi` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `rating`, `nama`, `deskripsi`) VALUES
(1, 4, 'Andi', 'Kue ulang tahun yang saya pesan dari Gayamita Cake benar-benar luar biasa! Rasanya lezat dan tampilannya sangat cantik. Pelayanan yang ramah membuat pengalaman saya semakin menyenangkan. Saya pasti akan memesan lagi!'),
(2, 4, 'siti', 'Saya sangat puas dengan kue yang saya beli di Gayamita Cake. Kue tartnya sangat lembut dan manisnya pas. Selain itu, stafnya sangat membantu dan memberikan rekomendasi yang tepat. Terima kasih Gayamita Cake!'),
(3, 5, 'Andi', 'Kue ulang tahun yang saya pesan dari Gayamita Cake benar-benar luar biasa! Rasanya lezat dan tampilannya sangat cantik. Pelayanan yang ramah membuat pengalaman saya semakin menyenangkan. Saya pasti akan memesan lagi!'),
(4, 5, 'siti', 'Saya sangat puas dengan kue yang saya beli di Gayamita Cake. Kue tartnya sangat lembut dan manisnya pas. Selain itu, stafnya sangat membantu dan memberikan rekomendasi yang tepat. Terima kasih Gayamita Cake!'),
(5, 4, 'Jovi', 'Test'),
(6, 4, 'Eden', 'Keren'),
(7, 5, 'sandhika', 'mantap gila');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

CREATE TABLE IF NOT EXISTS `carts` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `session_id` VARCHAR(128) NOT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `session_idx` (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabel cart_items: menyimpan item di dalam keranjang
CREATE TABLE IF NOT EXISTS `cart_items` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `cart_id` INT NOT NULL,
  `product_id` INT NOT NULL,
  `quantity` INT NOT NULL DEFAULT 1,
  `price_snapshot` INT NOT NULL DEFAULT 0,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `cart_idx` (`cart_id`),
  CONSTRAINT `fk_cart_items_cart` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabel untuk menyimpan data pesanan
CREATE TABLE IF NOT EXISTS `orders` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `order_number` VARCHAR(50) NOT NULL UNIQUE,
    `session_id` VARCHAR(128) NOT NULL,
    `nama_lengkap` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `no_telepon` VARCHAR(20) NOT NULL,
    `alamat` TEXT NOT NULL,
    `kota` VARCHAR(100) NOT NULL,
    `kode_pos` VARCHAR(10) NOT NULL,
    `catatan` TEXT NULL,
    `metode_pembayaran` ENUM('transfer', 'cod') NOT NULL DEFAULT 'transfer',
    `status` ENUM('pending', 'dikonfirmasi', 'diproses', 'dikirim', 'selesai', 'dibatalkan') NOT NULL DEFAULT 'pending',
    `total` INT NOT NULL DEFAULT 0,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `session_idx` (`session_id`),
    KEY `order_number_idx` (`order_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabel untuk menyimpan item pesanan
CREATE TABLE IF NOT EXISTS `orders` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `order_number` VARCHAR(50) NOT NULL UNIQUE,
    `session_id` VARCHAR(128) NOT NULL,
    `nama_lengkap` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `no_telepon` VARCHAR(20) NOT NULL,
    `alamat` TEXT NOT NULL,
    `kota` VARCHAR(100) NOT NULL,
    `kode_pos` VARCHAR(10) NOT NULL,
    `catatan` TEXT NULL,
    `metode_pembayaran` ENUM('transfer', 'cod') NOT NULL DEFAULT 'transfer',
    `status` ENUM('pending', 'dikonfirmasi', 'diproses', 'dikirim', 'selesai', 'dibatalkan') NOT NULL DEFAULT 'pending',
    `total` INT NOT NULL DEFAULT 0,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `session_idx` (`session_id`),
    KEY `order_number_idx` (`order_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabel untuk menyimpan item pesanan
CREATE TABLE IF NOT EXISTS `order_items` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `order_id` INT NOT NULL,
    `product_id` INT NOT NULL,
    `product_name` VARCHAR(255) NOT NULL,
    `product_image` VARCHAR(255) NULL,
    `quantity` INT NOT NULL DEFAULT 1,
    `price` INT NOT NULL DEFAULT 0,
    `subtotal` INT NOT NULL DEFAULT 0,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `order_idx` (`order_id`),
    CONSTRAINT `fk_order_items_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabel admin users
CREATE TABLE IF NOT EXISTS admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    nama_lengkap VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert default admin (username: admin, password: admin123)
INSERT INTO admin_users (username, password, nama_lengkap, email) 
VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', 'admin@gayamitacakes.com');


CREATE TABLE IF NOT EXISTS admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    nama_lengkap VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert default admin (username: admin, password: admin123)
INSERT INTO admin_users (username, password, nama_lengkap, email) 
VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', 'admin@gayamitacakes.com');



/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
