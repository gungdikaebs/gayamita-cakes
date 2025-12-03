-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 03, 2025 at 01:32 PM
-- Server version: 8.0.30
-- PHP Version: 8.3.6

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
-- Table structure for table `admin_users`
--

CREATE TABLE `admin_users` (
  `id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_users`
--

INSERT INTO `admin_users` (`id`, `username`, `password`, `nama_lengkap`, `email`, `created_at`) VALUES
(1, 'admin', '$2y$10$5E/IKkn0A0pJ2Pab7KSPyeh7p0B18w6YmsYYk7d1kkm0AkBox8pei', 'Administrator', 'admin@gayamitacakes.com', '2025-12-02 15:06:09');

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `id` int NOT NULL,
  `session_id` varchar(128) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `carts`
--

INSERT INTO `carts` (`id`, `session_id`, `created_at`, `updated_at`) VALUES
(1, '', '2025-12-02 23:21:25', '2025-12-02 23:21:25');

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `id` int NOT NULL,
  `cart_id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `price_snapshot` int NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart_items`
--

INSERT INTO `cart_items` (`id`, `cart_id`, `product_id`, `quantity`, `price_snapshot`, `created_at`, `updated_at`) VALUES
(2, 1, 6, 6, 30000, '2025-12-03 20:44:30', '2025-12-03 20:48:23'),
(3, 1, 3, 8, 160000, '2025-12-03 20:48:54', '2025-12-03 21:08:28'),
(4, 1, 7, 7, 40000, '2025-12-03 21:08:56', '2025-12-03 21:10:48');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int NOT NULL,
  `order_number` varchar(50) NOT NULL,
  `session_id` varchar(128) NOT NULL,
  `nama_lengkap` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `no_telepon` varchar(20) NOT NULL,
  `alamat` text NOT NULL,
  `kota` varchar(100) NOT NULL,
  `kode_pos` varchar(10) NOT NULL,
  `catatan` text,
  `metode_pembayaran` enum('transfer','cod') NOT NULL DEFAULT 'transfer',
  `status` enum('pending','dikonfirmasi','diproses','dikirim','selesai','dibatalkan') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'pending',
  `total` int NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `order_number`, `session_id`, `nama_lengkap`, `email`, `no_telepon`, `alamat`, `kota`, `kode_pos`, `catatan`, `metode_pembayaran`, `status`, `total`, `created_at`, `updated_at`) VALUES
(1, 'GMC2025120211D52B', '', 'Reprehenderit non repellendus esse culpa eaque repudiandae.', 'your.email+fakedata95845@gmail.com', '765-950-0598', 'Aliquam id hic.', 'Saepe repudiandae reiciendis ducimus enim.', '56248-3031', 'Arlie Bergstrom', 'transfer', 'diproses', 170000, '2025-12-02 23:21:50', '2025-12-02 23:26:24');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int NOT NULL,
  `order_id` int NOT NULL,
  `product_id` int NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_image` varchar(255) DEFAULT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `price` int NOT NULL DEFAULT '0',
  `subtotal` int NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_name`, `product_image`, `quantity`, `price`, `subtotal`, `created_at`) VALUES
(1, 1, 2, 'Cheese Cake', 'images/product/cheesecake.png', 1, 170000, 170000, '2025-12-02 23:21:50');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int NOT NULL,
  `nama` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `harga` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_general_ci NOT NULL,
  `rating` decimal(10,0) DEFAULT NULL,
  `image` varchar(100) COLLATE utf8mb4_general_ci NOT NULL
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
  `nama` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_general_ci NOT NULL
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
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `session_idx` (`session_id`);

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cart_idx` (`cart_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_number` (`order_number`),
  ADD KEY `session_idx` (`session_id`),
  ADD KEY `order_number_idx` (`order_number`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_idx` (`order_id`);

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
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `fk_cart_items_cart` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `fk_order_items_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
