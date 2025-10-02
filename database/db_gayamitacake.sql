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

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
