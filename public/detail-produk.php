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

    <div class="mx-auto px-4 py-20 max-w-5xl min-h-screen">
        <?php if ($product): ?>
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
                                <input id="qty-input" type="number" min="1" value="1" class="w-16 text-center bg-transparent outline-none font-medium">
                                <button type="button" id="qty-increase" class="px-3 py-1 text-xl text-gray-600 hover:text-gray-900">+</button>
                            </div>

                            <!-- Total price live -->
                            <div id="total-preview" class="ml-auto text-right">
                                <div class="text-sm text-gray-500">Total</div>
                                <div id="total-price" class="text-xl font-bold text-primary">Rp <?php echo number_format($product['harga'], 0, ',', '.'); ?></div>
                            </div>
                        </div>

                        <div class="mt-4 flex gap-3">
                            <!-- Form Tambah ke Keranjang -->
                            <form id="add-to-cart-form" action="index.php?page=keranjang" method="POST" class="flex-1">
                                <input type="hidden" name="action" value="add">
                                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                <input type="hidden" name="quantity" id="quantity-hidden" value="1">
                                <button type="submit" class="w-full flex items-center justify-center gap-2 bg-gray-100 text-gray-800 px-5 py-3 rounded-lg font-semibold shadow hover:bg-gray-200 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.35 2.7A1 1 0 007.55 17h12.9a1 1 0 00.9-1.3L20 13M7 13V6h13" />
                                    </svg>
                                    Tambah ke Keranjang
                                </button>
                            </form>

                            <!-- Form Beli Langsung (ke Checkout) -->
                            <form id="buy-now-form" action="index.php?page=checkout" method="POST" class="flex-1">
                                <input type="hidden" name="action" value="add">
                                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                <input type="hidden" name="quantity" id="quantity-hidden-buy" value="1">
                                <input type="hidden" name="redirect" value="checkout">
                                <button type="submit" class="w-full flex items-center justify-center gap-2 bg-slate-900 text-white px-5 py-3 rounded-lg font-semibold shadow-lg hover:brightness-95 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Beli Langsung
                                </button>
                            </form>
                        </div>

                        <!-- Tombol WhatsApp -->
                        <div class="mt-3">
                            <a id="buy-now-wa" href="https://wa.me/62881037714200?text=<?php echo urlencode('Halo, saya ingin memesan produk: ' . $product['nama'] . ' dengan harga Rp ' . number_format($product['harga'], 0, ',', '.') . '.'); ?>"
                                class="w-full inline-flex items-center justify-center gap-2 border border-green-500 bg-white text-green-600 px-5 py-3 rounded-lg font-semibold shadow hover:bg-green-50 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z" />
                                </svg>
                                Pesan via WhatsApp
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="text-center py-12 text-gray-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-16 w-16 mb-4 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6a2 2 0 012-2h2a2 2 0 012 2v6m-6 0h6" />
                </svg>
                <p>Produk tidak ditemukan.</p>
                <a href="index.php?page=produk" class="text-primary font-semibold mt-2 inline-block">← Kembali ke Produk</a>
            </div>
        <?php endif; ?>
    </div>
</main>

<script>
    const qtyInput = document.getElementById('qty-input');
    const qtyHidden = document.getElementById('quantity-hidden');
    const qtyHiddenBuy = document.getElementById('quantity-hidden-buy');
    const totalPrice = document.getElementById('total-price');
    const productPrice = <?php echo $product ? $product['harga'] : 0; ?>;

    function updateQuantity() {
        const qty = Math.max(1, parseInt(qtyInput.value) || 1);
        qtyInput.value = qty;
        qtyHidden.value = qty;
        qtyHiddenBuy.value = qty;
        totalPrice.textContent = 'Rp ' + (qty * productPrice).toLocaleString('id-ID');
    }

    document.getElementById('qty-decrease').addEventListener('click', () => {
        if (parseInt(qtyInput.value) > 1) {
            qtyInput.value = parseInt(qtyInput.value) - 1;
            updateQuantity();
        }
    });

    document.getElementById('qty-increase').addEventListener('click', () => {
        qtyInput.value = parseInt(qtyInput.value) + 1;
        updateQuantity();
    });

    qtyInput.addEventListener('input', updateQuantity);
</script>