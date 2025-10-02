<?php
require_once  'method/function.php';

// Konfigurasi pagination
$per_page = 8;
$page = isset($_GET['p']) ? max(1, intval($_GET['p'])) : 1;
$offset = ($page - 1) * $per_page;

// Ambil data & total produk
$total_products = get_products_total();
$total_pages = ceil($total_products / $per_page);
$products = get_products_paginated($per_page, $offset);
?>

<main>
    <section>
        <div class="relative w-full h-screen bg-fixed bg-cover bg-center overflow-hidden flex items-center justify-center" style="background-image:url('assets/images/cake-bg-3.png');">
            <div class="absolute inset-0 bg-black/60"></div>
            <div class="relative z-10 text-center px-4">
                <h1 class="text-3xl sm:text-4xl md:text-6xl leading-tight font-extrabold text-white mb-4">Ayo Lihat Lihat Produk Yang Ada Di <br> GAYAMITA</h1>
            </div>
        </div>
    </section>
    <div class="mx-auto max-w-7xl px-2 sm:px-6 lg:px-8 py-20">
        <h2 class="text-3xl font-bold mb-8 text-center">Produk Gayamita Cake</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
            <?php foreach ($products as $product): ?>
                <a href="index.php?page=detail-produk&id=<?php echo $product['id']; ?>" class="block bg-white rounded-lg shadow-lg overflow-hidden hover:scale-105 transition-all">
                    <img src="<?php echo (strpos($product['image'], 'http') === 0) ? $product['image'] : 'assets/' . $product['image']; ?>"
                        alt="<?php echo htmlspecialchars($product['nama']); ?>" class="w-full h-48 object-cover">
                    <div class="p-4">
                        <h3 class="text-xl font-semibold mb-2"><?php echo htmlspecialchars($product['nama']); ?></h3>
                        <p class="text-gray-600 mb-4"><?php echo htmlspecialchars($product['deskripsi']); ?></p>
                        <span class="text-gray-800 font-bold text-lg">Rp <?php echo number_format($product['harga'], 0, ',', '.'); ?></span>
                        <div class="text-yellow-400 mt-2">
                            <?php
                            $stars = floor($product['rating']);
                            for ($i = 0; $i < $stars; $i++) echo '★';
                            for ($i = $stars; $i < 5; $i++) echo '☆';
                            echo ' (' . $product['rating'] . ')';
                            ?>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <div class="flex justify-center items-center mt-8 space-x-2">
            <?php if ($page > 1): ?>
                <a href="index.php?page=produk&p=<?php echo $page - 1; ?>" class="px-3 py-2 bg-gray-200 rounded hover:bg-gray-300">&laquo; Prev</a>
            <?php endif; ?>
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="index.php?page=produk&p=<?php echo $i; ?>" class="px-3 py-2 rounded <?php echo ($i == $page) ? 'bg-primary text-white' : 'bg-gray-200 hover:bg-gray-300'; ?>">
                    <?php echo $i; ?>
                </a>
            <?php endfor; ?>
            <?php if ($page < $total_pages): ?>
                <a href="index.php?page=produk&p=<?php echo $page + 1; ?>" class="px-3 py-2 bg-gray-200 rounded hover:bg-gray-300">Next &raquo;</a>
            <?php endif; ?>
        </div>
    </div>
</main>