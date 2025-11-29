<?php
require_once __DIR__ . '/../method/function.php';

// Handle "Beli Langsung" dari detail produk
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['redirect']) && $_POST['redirect'] === 'checkout') {
    $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    $quantity = isset($_POST['quantity']) ? max(1, intval($_POST['quantity'])) : 1;

    if ($product_id > 0) {
        add_to_cart($product_id, $quantity);
    }
}

// Ambil isi keranjang
$items = get_cart_items();
$total = get_cart_total();

// Jika keranjang kosong, redirect ke halaman produk menggunakan JavaScript
if (empty($items)) {
    echo '<script>window.location.href = "index.php?page=produk";</script>';
    exit;
}

// Handle form submission untuk checkout
$errors = [];
$redirect_url = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nama_lengkap'])) {
    // Validasi input
    $nama_lengkap = isset($_POST['nama_lengkap']) ? trim($_POST['nama_lengkap']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $no_telepon = isset($_POST['no_telepon']) ? trim($_POST['no_telepon']) : '';
    $alamat = isset($_POST['alamat']) ? trim($_POST['alamat']) : '';
    $kota = isset($_POST['kota']) ? trim($_POST['kota']) : '';
    $kode_pos = isset($_POST['kode_pos']) ? trim($_POST['kode_pos']) : '';
    $catatan = isset($_POST['catatan']) ? trim($_POST['catatan']) : '';
    $metode_pembayaran = isset($_POST['metode_pembayaran']) ? $_POST['metode_pembayaran'] : 'transfer';

    // Validasi
    if (empty($nama_lengkap)) $errors[] = 'Nama lengkap harus diisi.';
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Email tidak valid.';
    if (empty($no_telepon)) $errors[] = 'Nomor telepon harus diisi.';
    if (empty($alamat)) $errors[] = 'Alamat harus diisi.';
    if (empty($kota)) $errors[] = 'Kota harus diisi.';
    if (empty($kode_pos)) $errors[] = 'Kode pos harus diisi.';
    if (!in_array($metode_pembayaran, ['transfer', 'cod'])) $errors[] = 'Metode pembayaran tidak valid.';

    if (empty($errors)) {
        $data = [
            'nama_lengkap' => $nama_lengkap,
            'email' => $email,
            'no_telepon' => $no_telepon,
            'alamat' => $alamat,
            'kota' => $kota,
            'kode_pos' => $kode_pos,
            'catatan' => $catatan,
            'metode_pembayaran' => $metode_pembayaran
        ];

        $order_number = create_order($data);

        if ($order_number) {
            // Gunakan JavaScript untuk redirect
            echo '<script>window.location.href = "index.php?page=konfirmasi-order&order=' . $order_number . '";</script>';
            exit;
        } else {
            $errors[] = 'Terjadi kesalahan saat membuat pesanan. Silakan coba lagi.';
        }
    }
}

// Fungsi format harga
function rupiah($angka)
{
    return 'Rp ' . number_format($angka, 0, ',', '.');
}
?>

<main class="bg-gray-50 min-h-screen py-8">
    <div class="container mx-auto px-4 max-w-6xl">
        <h1 class="text-3xl font-bold mb-8 text-center">Checkout</h1>

        <!-- Error Messages -->
        <?php if (!empty($errors)): ?>
            <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                <ul class="list-disc list-inside">
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="index.php?page=checkout" method="POST">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Form Data Pemesan -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                        <h2 class="text-xl font-semibold mb-4 pb-2 border-b">Data Pemesan</h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="md:col-span-2">
                                <label for="nama_lengkap" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap *</label>
                                <input type="text" id="nama_lengkap" name="nama_lengkap" required
                                    value="<?php echo isset($_POST['nama_lengkap']) ? htmlspecialchars($_POST['nama_lengkap']) : ''; ?>"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                                <input type="email" id="email" name="email" required
                                    value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                            </div>

                            <div>
                                <label for="no_telepon" class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon *</label>
                                <input type="tel" id="no_telepon" name="no_telepon" required
                                    value="<?php echo isset($_POST['no_telepon']) ? htmlspecialchars($_POST['no_telepon']) : ''; ?>"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                            </div>

                            <div class="md:col-span-2">
                                <label for="alamat" class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap *</label>
                                <textarea id="alamat" name="alamat" rows="3" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"><?php echo isset($_POST['alamat']) ? htmlspecialchars($_POST['alamat']) : ''; ?></textarea>
                            </div>

                            <div>
                                <label for="kota" class="block text-sm font-medium text-gray-700 mb-1">Kota *</label>
                                <input type="text" id="kota" name="kota" required
                                    value="<?php echo isset($_POST['kota']) ? htmlspecialchars($_POST['kota']) : ''; ?>"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                            </div>

                            <div>
                                <label for="kode_pos" class="block text-sm font-medium text-gray-700 mb-1">Kode Pos *</label>
                                <input type="text" id="kode_pos" name="kode_pos" required
                                    value="<?php echo isset($_POST['kode_pos']) ? htmlspecialchars($_POST['kode_pos']) : ''; ?>"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                            </div>

                            <div class="md:col-span-2">
                                <label for="catatan" class="block text-sm font-medium text-gray-700 mb-1">Catatan (Opsional)</label>
                                <textarea id="catatan" name="catatan" rows="2"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                                    placeholder="Contoh: Tolong kirim sore hari, jangan terlalu manis, dll."><?php echo isset($_POST['catatan']) ? htmlspecialchars($_POST['catatan']) : ''; ?></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Metode Pembayaran -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold mb-4 pb-2 border-b">Metode Pembayaran</h2>

                        <div class="space-y-4">
                            <!-- Transfer Bank -->
                            <label class="flex items-start p-4 border rounded-lg cursor-pointer hover:bg-gray-50 transition">
                                <input type="radio" name="metode_pembayaran" value="transfer" class="mt-1" checked>
                                <div class="ml-4">
                                    <div class="font-semibold text-gray-800">Transfer Bank</div>
                                    <div class="text-sm text-gray-600">Pembayaran melalui transfer ke rekening bank kami.</div>
                                </div>
                            </label>

                            <!-- COD -->
                            <label class="flex items-start p-4 border rounded-lg cursor-pointer hover:bg-gray-50 transition">
                                <input type="radio" name="metode_pembayaran" value="cod" class="mt-1">
                                <div class="ml-4">
                                    <div class="font-semibold text-gray-800">COD (Bayar di Tempat)</div>
                                    <div class="text-sm text-gray-600">Bayar langsung saat pesanan sampai.</div>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Ringkasan Pesanan -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-md p-6 sticky top-24">
                        <h2 class="text-xl font-semibold mb-4 pb-2 border-b">Ringkasan Pesanan</h2>

                        <div class="space-y-4 mb-4 max-h-64 overflow-y-auto">
                            <?php foreach ($items as $item): ?>
                                <div class="flex items-center gap-3">
                                    <img src="<?php echo (strpos($item['image'], 'http') === 0) ? $item['image'] : 'assets/' . $item['image']; ?>"
                                        alt="<?php echo htmlspecialchars($item['nama']); ?>"
                                        class="w-16 h-16 object-cover rounded-lg">
                                    <div class="flex-1">
                                        <div class="text-sm font-medium"><?php echo htmlspecialchars($item['nama']); ?></div>
                                        <div class="text-xs text-gray-500"><?php echo $item['quantity']; ?> x <?php echo rupiah($item['price_snapshot']); ?></div>
                                    </div>
                                    <div class="text-sm font-semibold"><?php echo rupiah($item['quantity'] * $item['price_snapshot']); ?></div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="border-t pt-4">
                            <div class="flex justify-between text-lg font-bold">
                                <span>Total</span>
                                <span class="text-primary"><?php echo rupiah($total); ?></span>
                            </div>
                        </div>

                        <button type="submit" class="w-full mt-6 bg-primary text-white py-3 px-6 rounded-lg font-semibold hover:brightness-95 transition flex items-center justify-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Buat Pesanan
                        </button>

                        <a href="index.php?page=keranjang" class="block text-center mt-4 text-sm text-gray-600 hover:text-primary">
                            ← Kembali ke Keranjang
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</main>