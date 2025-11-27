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

    // Redirect jika produk tidak ditemukan
    if (!$product) {
        header('Location: index.php?page=produk');
        exit;
    }
    ?>

    <div class="mx-auto px-4 py-20 max-w-6xl">
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden grid grid-cols-1 md:grid-cols-2 gap-6 p-6">
            <!-- Gambar Produk -->
            <div class="flex items-center justify-center bg-gradient-to-br from-primary/10 to-white rounded-lg p-6">
                <img src="<?php echo (strpos($product['image'], 'http') === 0) ? $product['image'] : 'assets/' . $product['image']; ?>"
                    alt="<?php echo htmlspecialchars($product['nama']); ?>" class="object-contain max-h-96 rounded-md shadow-md transition-transform hover:scale-105">
            </div>

            <!-- Detail Produk -->
            <div class="flex flex-col justify-between">
                <div>
                    <h1 class="text-3xl md:text-4xl font-extrabold text-gray-800 mb-2"><?php echo htmlspecialchars($product['nama']); ?></h1>

                    <div class="flex items-center gap-4 mb-4">
                        <div class="text-2xl md:text-3xl font-bold text-gray-900">Rp <?php echo number_format($product['harga'], 0, ',', '.'); ?></div>
                        <div class="flex items-center text-sm text-gray-500 bg-gray-50 px-3 py-1 rounded-full shadow-sm">
                            <span class="text-yellow-400 mr-2">
                                <?php
                                $stars = floor($product['rating']);
                                for ($i = 0; $i < $stars; $i++) echo '★';
                                for ($i = $stars; $i < 5; $i++) echo '☆';
                                ?>
                            </span>
                            <span>(<?php echo $product['rating']; ?>)</span>
                        </div>
                    </div>

                    <p class="text-gray-700 leading-relaxed mb-6"><?php echo nl2br(htmlspecialchars($product['deskripsi'])); ?></p>
                </div>

                <!-- Kontrol Quantity + Tombol -->
                <div>
                    <div class="flex items-center gap-4">
                        <!-- Quantity selector -->
                        <div class="flex items-center bg-gray-50 rounded-lg px-2 py-1 shadow-sm">
                            <button type="button" id="qty-decrease" class="px-3 py-1 text-xl text-gray-600 hover:text-gray-900">−</button>
                            <input id="qty-input" name="quantity" type="number" min="1" value="1" class="w-16 text-center bg-transparent outline-none font-medium">
                            <button type="button" id="qty-increase" class="px-3 py-1 text-xl text-gray-600 hover:text-gray-900">+</button>
                        </div>

                        <!-- Total price live -->
                        <div id="total-preview" class="ml-auto text-right">
                            <div class="text-sm text-gray-500">Total</div>
                            <div id="total-price" class="text-xl font-bold text-primary">Rp <?php echo number_format($product['harga'], 0, ',', '.'); ?></div>
                        </div>
                    </div>

                    <div class="mt-4 flex gap-3">
                        <!-- Form fallback (submit normal bila JS disabled) -->
                        <form id="add-to-cart-form" action="index.php?page=keranjang" method="POST" class="flex-1">
                            <input type="hidden" name="action" value="add">
                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                            <input type="hidden" name="quantity" id="quantity-hidden" value="1">
                            <button type="submit" class="w-full flex items-center justify-center gap-2 bg-primary text-white px-5 py-3 rounded-lg font-semibold shadow-lg hover:brightness-95 transition">
                                <!-- Cart icon -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.35 2.7A1 1 0 007.55 17h12.9a1 1 0 00.9-1.3L20 13M7 13V6h13" />
                                </svg>
                                Tambah ke Keranjang
                            </button>
                        </form>

                        <a id="buy-now" href="https://wa.me/62881037714200?text=<?php echo urlencode('Halo, saya ingin memesan produk: ' . $product['nama'] . ' dengan harga Rp ' . number_format($product['harga'], 0, ',', '.') . '.'); ?>"
                            class="flex-1 inline-flex items-center justify-center gap-2 border border-transparent bg-white text-gray-900 px-5 py-3 rounded-lg font-semibold shadow hover:shadow-md transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 11.5a8.38 8.38 0 01-.9 3.8 8.5 8.5 0 01-7.6 4.7 8.38 8.38 0 01-3.8-.9L3 21l1.9-5.7A8.38 8.38 0 014 11.5 8.5 8.5 0 1112.5 20" />
                            </svg>
                            Pesan Sekarang
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
    // Update quantity-hidden saat pengguna mengubah jumlah
    const qtyInput = document.getElementById('qty-input');
    const qtyHidden = document.getElementById('quantity-hidden');
    const totalPrice = document.getElementById('total-price');
    const productPrice = <?php echo $product['harga']; ?>;

    document.getElementById('qty-decrease').addEventListener('click', () => {
        if (qtyInput.value > 1) {
            qtyInput.value--;
            qtyHidden.value = qtyInput.value;
            totalPrice.textContent = 'Rp ' + (qtyInput.value * productPrice).toLocaleString('id-ID');
        }
    });

    document.getElementById('qty-increase').addEventListener('click', () => {
        qtyInput.value++;
        qtyHidden.value = qtyInput.value;
        totalPrice.textContent = 'Rp ' + (qtyInput.value * productPrice).toLocaleString('id-ID');
    });

    qtyInput.addEventListener('input', () => {
        qtyHidden.value = qtyInput.value;
        totalPrice.textContent = 'Rp ' + (qtyInput.value * productPrice).toLocaleString('id-ID');
    });
</script>