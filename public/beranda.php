    <main>
        <div class="relative w-full h-screen bg-fixed bg-cover bg-center overflow-hidden flex items-center justify-center" style="background-image:url('assets/images/cake-bg.jpg');">
            <div class="absolute inset-0 bg-black/40"></div>
            <div class="relative z-10 text-center px-4">
                <h1 class="text-3xl sm:text-4xl md:text-6xl font-extrabold text-white mb-4">Selamat Datang di Gayamita Cake</h1>
                <p class="text-lg sm:text-xl md:text-2xl text-white mb-8">Setiap gigitan kue adalah pesan cinta—manis, lembut, dan penuh makna
                    untuk hari-hari yang lebih indah.</p>
            </div>
        </div>
        <!-- Layanan -->
        <div class=" mx-auto max-w-7xl px-2 sm:px-6 lg:px-8 py-20">
            <h2 class="text-3xl font-bold text-center mb-8">Layanan</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-18">
                <div class="bg-white rounded-lg shadow-lg p-6 text-center hover:shadow-primary/40 hover:scale-105 transition-all">
                    <box-icon name='package' size='lg'></box-icon>
                    <h3 class="text-xl font-semibold mb-2">Self Pick Up</h3>
                    <p class="text-gray-600">Ambil pesanan Anda langsung di toko kami dengan mudah dan cepat.</p>
                </div>
                <div class="bg-white rounded-lg shadow-lg p-10 text-center hover:shadow-primary/40 hover:scale-105 transition-all">
                    <box-icon name='car' size='lg'></box-icon>
                    <h3 class="text-xl font-semibold mb-2">Delivery</h3>
                    <p class="text-gray-600">Kami mengantarkan kue lezat langsung ke depan pintu Anda.</p>
                </div>
            </div>
        </div>
        <!-- End Layanan -->

        <!-- Best Seller -->
        <section class="mx-auto max-w-7xl px-2 sm:px-6 lg:px-8 py-20">
            <h2 class="text-3xl font-bold mb-8 text-center">Best Seller</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                <?php
                require_once __DIR__ . '/../method/function.php';
                $best_sellers = get_best_seller_products();
                foreach ($best_sellers as $product) : ?>
                    <a href=" index.php?page=detail-produk&id=<?php echo $product['id']; ?>">
                        <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:scale-105 transition-all">
                            <img src="<?php echo 'assets/' . $product['image']; ?>" alt="<?php echo htmlspecialchars($product['nama']); ?>" class="w-full h-48 object-cover">
                            <div class="p-4">
                                <h3 class="text-xl font-semibold mb-2"><?php echo htmlspecialchars($product['nama']); ?></h3>
                                <p class="text-gray-600 mb-4"><?php echo htmlspecialchars($product['deskripsi']); ?></p>
                                <span class="text-gray-800 font-bold text-lg">Rp <?php echo number_format($product['harga'], 0, ',', '.'); ?> </span>
                                <div class="text-yellow-400 mt-2">
                                    <?php
                                    $stars = floor($product['rating']);
                                    for ($i = 0; $i < $stars; $i++) echo '★';
                                    for ($i = $stars; $i < 5; $i++) echo '☆';
                                    echo ' (' . $product['rating'] . ')';
                                    ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    </a>
            </div>
        </section>
        <!-- End Best Seller -->

        <!-- Testimoni -->
        <section class="mx-auto max-w-7xl px-2 sm:px-6 lg:px-8 py-20">
            <h2 class="text-3xl font-bold mb-8 text-center">Testimoni</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <?php
                require_once __DIR__ . '/../method/function.php';
                $store_reviews = get_store_reviews(6); // tampilkan 6    testimoni terbaru
                foreach ($store_reviews as $review): ?>
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <div class="flex justify-between items-end">
                            <div class="flex items-center">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/7/7c/Profile_avatar_placeholder_large.png"
                                    alt="User" class="w-12 h-12 rounded-full mr-4">
                                <h4 class="font-semibold"><?php echo htmlspecialchars($review['nama']); ?></h4>
                            </div>
                            <!-- rating -->
                            <div class="flex items-center mb-2">
                                <?php
                                for ($i = 0; $i < $review['rating']; $i++) {
                                    echo "<box-icon type='solid' name='star' color='#fbbf24' size='sm'></box-icon>";
                                }
                                for ($i = $review['rating']; $i < 5; $i++) {
                                    echo "<box-icon name='star' color='#e5e7eb' size='sm'></box-icon>";
                                }
                                ?>
                            </div>
                        </div>
                        <p class="text-gray-600 text-justify mb-4 mt-4">
                            "<?php echo htmlspecialchars($review['deskripsi']); ?>"
                        </p>

                    </div>
                <?php endforeach; ?>

            </div>
            <div class="mt-8 flex justify-center">
                <a class="mx-auto px-6 py-2 bg-primary font-bold text-gray-700 rounded-lg hover:text-primary hover:bg-gray-600 transition-colors" href="index.php?page=ulasan">Ulasan</a>
            </div>
        </section>
        <!-- End Testimoni -->
    </main>