<?php
require_once __DIR__ . '/../method/function.php';

$order_number = isset($_GET['order']) ? $_GET['order'] : '';
$order = null;
$order_items = [];

if ($order_number) {
    $order = get_order_by_number($order_number);
    if ($order) {
        $order_items = get_order_items($order['id']);
    }
}

// Jika order tidak ditemukan, redirect menggunakan JavaScript
if (!$order) {
    echo '<script>window.location.href = "index.php?page=beranda";</script>';
    exit;
}

// Fungsi format harga
function rupiah($angka)
{
    return 'Rp ' . number_format($angka, 0, ',', '.');
}
?>

<main class="bg-gray-50 min-h-screen py-8">
    <div class="container mx-auto px-4 max-w-4xl">
        <!-- Success Icon -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-green-100 rounded-full mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Pesanan Berhasil!</h1>
            <p class="text-gray-600">Terima kasih telah memesan di Gayamita Cakes</p>
        </div>

        <!-- Order Info -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex justify-between items-center mb-4 pb-4 border-b">
                <div>
                    <div class="text-sm text-gray-500">Nomor Pesanan</div>
                    <div class="text-xl font-bold text-primary"><?php echo htmlspecialchars($order['order_number']); ?></div>
                </div>
                <div class="text-right">
                    <div class="text-sm text-gray-500">Status</div>
                    <div class="inline-block px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                        <?php echo ucfirst($order['status']); ?>
                    </div>
                </div>
            </div>

            <!-- Detail Pemesan -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <h3 class="font-semibold text-gray-800 mb-2">Data Pemesan</h3>
                    <div class="text-sm text-gray-600 space-y-1">
                        <p><strong>Nama:</strong> <?php echo htmlspecialchars($order['nama_lengkap']); ?></p>
                        <p><strong>Email:</strong> <?php echo htmlspecialchars($order['email']); ?></p>
                        <p><strong>Telepon:</strong> <?php echo htmlspecialchars($order['no_telepon']); ?></p>
                    </div>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800 mb-2">Alamat Pengiriman</h3>
                    <div class="text-sm text-gray-600">
                        <p><?php echo nl2br(htmlspecialchars($order['alamat'])); ?></p>
                        <p><?php echo htmlspecialchars($order['kota']); ?>, <?php echo htmlspecialchars($order['kode_pos']); ?></p>
                    </div>
                </div>
            </div>

            <!-- Daftar Item -->
            <h3 class="font-semibold text-gray-800 mb-3">Detail Pesanan</h3>
            <div class="space-y-3 mb-4">
                <?php foreach ($order_items as $item): ?>
                    <div class="flex items-center gap-4 p-3 bg-gray-50 rounded-lg">
                        <img src="<?php echo (strpos($item['product_image'], 'http') === 0) ? $item['product_image'] : 'assets/' . $item['product_image']; ?>"
                            alt="<?php echo htmlspecialchars($item['product_name']); ?>"
                            class="w-16 h-16 object-cover rounded-lg">
                        <div class="flex-1">
                            <div class="font-medium"><?php echo htmlspecialchars($item['product_name']); ?></div>
                            <div class="text-sm text-gray-500"><?php echo $item['quantity']; ?> x <?php echo rupiah($item['price']); ?></div>
                        </div>
                        <div class="font-semibold"><?php echo rupiah($item['subtotal']); ?></div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Total -->
            <div class="border-t pt-4">
                <div class="flex justify-between text-lg font-bold">
                    <span>Total Pembayaran</span>
                    <span class="text-primary"><?php echo rupiah($order['total']); ?></span>
                </div>
            </div>
        </div>

        <!-- Instruksi Pembayaran -->
        <?php if ($order['metode_pembayaran'] === 'transfer'): ?>
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
                <h3 class="font-semibold text-blue-800 mb-3">Instruksi Pembayaran (Transfer)</h3>
                <div class="text-sm text-blue-700 space-y-2">
                    <p>Silakan transfer ke rekening berikut:</p>
                    <div class="bg-white p-4 rounded-lg">
                        <p><strong>Bank BCA</strong></p>
                        <p>No. Rekening: <span class="font-mono font-bold">1234567890</span></p>
                        <p>Atas Nama: <span class="font-bold">Gayamita Cakes</span></p>
                    </div>
                    <p>Total yang harus dibayar: <strong class="text-lg"><?php echo rupiah($order['total']); ?></strong></p>
                    <p class="text-xs">Pesanan akan diproses setelah pembayaran dikonfirmasi.</p>
                </div>
            </div>
        <?php else: ?>
            <div class="bg-green-50 border border-green-200 rounded-lg p-6 mb-6">
                <h3 class="font-semibold text-green-800 mb-3">Instruksi Pembayaran (COD)</h3>
                <div class="text-sm text-green-700 space-y-2">
                    <p>Pesanan Anda akan dikirim ke alamat yang tertera.</p>
                    <p>Silakan siapkan uang tunai sebesar <strong class="text-lg"><?php echo rupiah($order['total']); ?></strong> saat pesanan tiba.</p>
                </div>
            </div>
        <?php endif; ?>

        <!-- Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="index.php?page=produk" class="inline-flex items-center justify-center px-6 py-3 bg-primary text-white rounded-lg font-semibold hover:brightness-95 transition">
                Lanjut Belanja
            </a>
            <a href="https://wa.me/62881037714200?text=<?php echo urlencode('Halo, saya ingin konfirmasi pesanan dengan nomor: ' . $order['order_number']); ?>"
                class="inline-flex items-center justify-center px-6 py-3 bg-green-500 text-white rounded-lg font-semibold hover:bg-green-600 transition gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z" />
                </svg>
                Konfirmasi via WhatsApp
            </a>
        </div>
    </div>
</main>