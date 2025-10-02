<main>
    <?php
    require_once 'method/function.php';

    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    $product = null;

    // Function ambil satu produk berdasarkan id
    function fetch_product_by_id($id)
    {
        global $conn;
        $sql = "SELECT * FROM products WHERE id = :id LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    if ($id) {
        $product = fetch_product_by_id($id);
    }
    ?>

    <div class="mx-auto px-4 py-20 max-w-4xl">
        <?php if ($product): ?>
            <div class=" bg-white rounded-xl shadow-2xl overflow-hidden flex flex-col md:flex-row transition hover:shadow-primary/40">
                <div class="md:w-1/2 w-full flex items-center justify-center bg-gradient-to-br from-primary/10 to-white">
                    <img src="<?php echo (strpos($product['image'], 'http') === 0) ? $product['image'] : 'assets/' . $product['image']; ?>"
                        alt="<?php echo htmlspecialchars($product['nama']); ?>" class=" object-cover rounded-lg shadow-lg border border-gray-100">
                </div>
                <div class="p-8 flex-1 flex flex-col justify-between">
                    <div>
                        <h2 class="text-4xl font-extrabold mb-3 text-gray-800"><?php echo htmlspecialchars($product['nama']); ?></h2>
                        <span class="text-2xl font-bold text-gray-700 block mb-2">Rp <?php echo number_format($product['harga'], 0, ',', '.'); ?></span>
                        <div class="flex items-center mb-3">
                            <div class="text-yellow-400 text-xl mr-2">
                                <?php
                                $stars = floor($product['rating']);
                                for ($i = 0; $i < $stars; $i++) echo '★';
                                for ($i = $stars; $i < 5; $i++) echo '☆';
                                ?>
                            </div>
                            <span class="text-gray-600">(<?php echo $product['rating']; ?>)</span>
                        </div>
                        <p class="text-gray-700 mb-6 leading-relaxed"><?php echo nl2br(htmlspecialchars($product['deskripsi'])); ?></p>
                    </div>

                    <a href="https://wa.me/62881037714200?text=<?php echo urlencode('Halo, saya ingin memesan produk: ' . $product['nama'] . ' dengan harga Rp ' . number_format($product['harga'], 0, ',', '.') . '.'); ?>" class="w-full bg-gray-200 text-gray-900 px-6 py-3 rounded-lg font-bold shadow-lg hover:bg-primary/80 transition duration-200 flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.35 2.7a1 1 0 00.9 1.3h12.2a1 1 0 00.9-1.3L17 13M7 13V6h13" />
                        </svg>
                        Pesan Sekarang
                    </a>
                </div>
            </div>
        <?php else: ?>
            <div class="text-center py-12 text-gray-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-16 w-16 mb-4 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6a2 2 0 012-2h2a2 2 0 012 2v6m-6 0h6" />
                </svg>
                Produk tidak ditemukan.
            </div>
        <?php endif; ?>
    </div>
</main>