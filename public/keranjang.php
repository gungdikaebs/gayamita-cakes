<?php
require_once __DIR__ . '/../method/function.php';

// Tangani aksi berdasarkan metode POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = isset($_POST['action']) ? $_POST['action'] : '';
    if ($action === 'add') {
        $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
        $quantity = isset($_POST['quantity']) ? max(1, intval($_POST['quantity'])) : 1;

        if ($product_id > 0) {
            add_to_cart($product_id, $quantity);
        }
    }
    if ($action === 'update') {
        $cart_item_id = isset($_POST['cart_item_id']) ? intval($_POST['cart_item_id']) : 0;
        $quantity = isset($_POST['quantity']) ? max(1, intval($_POST['quantity'])) : 1;

        if ($cart_item_id > 0) {
            update_cart_item($cart_item_id, $quantity);
        }
    }

    if ($action === 'delete') {
        $cart_item_id = isset($_POST['cart_item_id']) ? intval($_POST['cart_item_id']) : 0;

        if ($cart_item_id > 0) {
            remove_from_cart($cart_item_id);
        }
    }
}



// Ambil isi keranjang
$items = get_cart_items();
$total = 0;

// Fungsi untuk format harga
function rupiah($angka)
{
    return 'Rp ' . number_format($angka, 0, ',', '.');
}
?>

<style>
    body {
        font-family: 'Poppins', sans-serif;
    }
</style>

<div class="min-h-screen py-12 bg-gray-50">
    <div class="container mx-auto px-4 max-w-7xl">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-800 mb-2 flex items-center gap-3">
                <i class="fas fa-shopping-cart "></i>
                Keranjang Belanja
            </h1>
            <p class="text-gray-600">Kelola pesanan Anda sebelum checkout</p>
        </div>

        <?php if (empty($items)): ?>
            <!-- Empty Cart State -->
            <div class="bg-white rounded-3xl shadow-2xl p-20 text-center backdrop-blur-sm bg-white/90">
                <div class="mb-8 animate-bounce">
                    <div class="w-32 h-32 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full mx-auto flex items-center justify-center">
                        <i class="fas fa-shopping-cart text-gray-400 text-6xl"></i>
                    </div>
                </div>
                <h2 class="text-3xl font-bold text-gray-800 mb-4">Keranjang Anda Kosong</h2>
                <p class="text-gray-500 mb-10 text-lg">Belum ada produk di keranjang. Yuk, mulai belanja sekarang!</p>
                <a href="index.php?page=produk"
                    class="inline-flex items-center gap-3 bg-primary text-gray-900 px-10 py-5 rounded-2xl font-bold text-lg shadow-xl hover:brightness-95 hover:shadow-2xl transition-all duration-300">
                    <i class="fas fa-shopping-bag text-xl"></i>
                    Mulai Belanja
                </a>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Cart Items -->
                <div class="lg:col-span-2 space-y-4">
                    <?php foreach ($items as $item): ?>
                        <?php $subtotal = $item['quantity'] * $item['price_snapshot']; ?>
                        <?php $total += $subtotal; ?>

                        <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 group">
                            <div class="p-6">
                                <div class="flex flex-col md:flex-row gap-6">
                                    <!-- Product Image -->
                                    <div class="flex-shrink-0">
                                        <div class="w-24 h-24 rounded-xl overflow-hidden bg-gray-100 group-hover:scale-105 transition-transform duration-300">
                                            <img src="assets/<?php echo htmlspecialchars($item['image']); ?>"
                                                alt="<?php echo htmlspecialchars($item['nama']); ?>"
                                                class="w-full h-full object-cover">
                                        </div>
                                    </div>

                                    <!-- Product Details -->
                                    <div class="flex-grow">
                                        <h3 class="text-xl font-bold text-gray-800 mb-2"><?php echo htmlspecialchars($item['nama']); ?></h3>
                                        <p class="text-primary font-semibold text-lg mb-4"><?php echo rupiah($item['price_snapshot']); ?></p>

                                        <!-- Quantity Control -->
                                        <div class="flex flex-wrap items-center gap-4">
                                            <div class="flex items-center gap-2">
                                                <span class="text-sm text-gray-600 font-medium">Jumlah:</span>
                                                <form action="index.php?page=keranjang" method="POST" class="flex items-center gap-2">
                                                    <input type="hidden" name="action" value="update">
                                                    <input type="hidden" name="cart_item_id" value="<?php echo $item['cart_item_id']; ?>">
                                                    <input type="number"
                                                        name="quantity"
                                                        value="<?php echo $item['quantity']; ?>"
                                                        min="1"
                                                        class="w-20 text-center border-2 border-gray-300 rounded-lg py-2 font-semibold focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all">
                                                    <button type="submit"
                                                        class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-4 py-2 rounded-lg font-semibold hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300 flex items-center gap-2">
                                                        <i class="fas fa-sync-alt text-xs"></i>
                                                        Update
                                                    </button>
                                                </form>
                                            </div>

                                            <!-- Delete Button -->
                                            <form action="index.php?page=keranjang" method="POST" class="ml-auto">
                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="cart_item_id" value="<?php echo $item['cart_item_id']; ?>">
                                                <button type="submit"
                                                    onclick="return confirm('Yakin ingin menghapus produk ini?')"
                                                    class="bg-gradient-to-r from-red-500 to-red-600 text-white px-4 py-2 rounded-lg font-semibold hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300 flex items-center gap-2">
                                                    <i class="fas fa-trash-alt"></i>
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>

                                        <!-- Subtotal -->
                                        <div class="mt-4 pt-4 border-t border-gray-100">
                                            <div class="flex justify-between items-center">
                                                <span class="text-gray-600 font-medium">Subtotal:</span>
                                                <span class="text-2xl font-bold text-gray-800"><?php echo rupiah($subtotal); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Order Summary Sidebar -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-xl p-8 sticky top-24">
                        <h2 class="text-2xl font-bold text-gray-800 mb-6 pb-4 border-b-2 border-gray-100">Ringkasan Pesanan</h2>

                        <!-- Summary Details -->
                        <div class="space-y-4 mb-6">
                            <div class="flex justify-between items-center text-gray-600">
                                <span>Subtotal Produk</span>
                                <span class="font-semibold"><?php echo rupiah($total); ?></span>
                            </div>
                            <div class="flex justify-between items-center text-gray-600">
                                <span>Jumlah Item</span>
                                <span class="font-semibold"><?php echo count($items); ?> item</span>
                            </div>
                        </div>

                        <!-- Total -->
                        <div class="pt-6 border-t-2 border-gray-100 mb-6">
                            <div class="flex justify-between items-center">
                                <span class="text-lg font-bold text-gray-600">Total Pembayaran</span>
                                <span class="text-lg font-bold text-gray-800"><?php echo rupiah($total); ?></span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="space-y-3">
                            <a href="index.php?page=checkout"
                                class="block w-full bg-slate-900 text-white text-center py-2 rounded-xl font-bold text-lg hover:brightness-95 shadow-lg hover:shadow-xl transition-all duration-300">
                                <i class="fas fa-check-circle mr-2"></i>
                                Lanjut ke Checkout
                            </a>
                            <a href="index.php?page=produk"
                                class="block w-full bg-gray-100 text-gray-700 text-center py-2 rounded-xl font-semibold hover:bg-gray-200 transition-all duration-300">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Lanjut Belanja
                            </a>
                        </div>

                        <!-- Info Box -->
                        <div class="mt-6 bg-primary/10 rounded-xl p-4 border-2 border-primary/30">
                            <div class="flex items-start gap-3">
                                <i class="fas fa-info-circle text-primary mt-1"></i>
                                <div class="text-sm text-gray-700">
                                    <p class="font-semibold mb-1">Catatan:</p>
                                    <p>Harga dapat berubah sewaktu-waktu. Pastikan untuk melakukan pembayaran secepatnya.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Font Awesome (jika belum ada) -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">