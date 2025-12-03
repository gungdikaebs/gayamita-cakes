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


<div class="container mx-auto px-4 py-8 min-h-screen">
    <h1 class="text-3xl font-bold mb-6">Keranjang Belanja</h1>

    <?php if (empty($items)): ?>
        <div class="bg-white p-6 rounded-lg shadow text-center">
            <p class="text-gray-500">Keranjang Anda kosong.</p>
            <a href="index.php?page=produk" class="text-primary font-semibold">Lihat Produk</a>
        </div>
    <?php else: ?>
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border px-4 py-2 text-left">Produk</th>
                        <th class="border px-4 py-2 text-center">Jumlah</th>
                        <th class="border px-4 py-2 text-right">Harga</th>
                        <th class="border px-4 py-2 text-right">Subtotal</th>
                        <th class="border px-4 py-2 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $item): ?>
                        <?php $subtotal = $item['quantity'] * $item['price_snapshot']; ?>
                        <?php $total += $subtotal; ?>
                        <tr>
                            <td class="border px-4 py-2">
                                <div class="flex items-center">
                                    <img src="assets/<?php echo htmlspecialchars($item['image']); ?>"
                                        alt="<?php echo htmlspecialchars($item['image']); ?>?>" class="w-16 h-16 object-cover rounded-md mr-4">
                                    <span><?php echo htmlspecialchars($item['nama']); ?></span>

                                </div>
                            </td>
                            <td class="border px-4 py-2 text-center">
                                <form action="index.php?page=keranjang" method="POST" class="inline">
                                    <input type="hidden" name="action" value="update">
                                    <input type="hidden" name="cart_item_id" value="<?php echo $item['cart_item_id']; ?>">
                                    <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1" class="w-16 text-center border rounded">
                                    <button type="submit" class="ml-2 bg-primary text-white px-2 py-1 rounded">Update</button>
                                </form>
                            </td>
                            <td class="border px-4 py-2 text-right"><?php echo rupiah($item['price_snapshot']); ?></td>
                            <td class="border px-4 py-2 text-right"><?php echo rupiah($subtotal); ?></td>
                            <td class="border px-4 py-2 text-center">
                                <form action="index.php?page=keranjang" method="POST" class="inline">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="cart_item_id" value="<?php echo $item['cart_item_id']; ?>">
                                    <button type="submit" class="bg-red-500 text-white px-2 py-1 rounded">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr class="bg-gray-100">
                        <td colspan="3" class="border px-4 py-2 text-right font-bold">Total</td>
                        <td colspan="2" class="border px-4 py-2 text-right font-bold"><?php echo rupiah($total); ?></td>
                    </tr>
                </tfoot>
            </table>
            <div class="p-4 flex justify-between items-center">
                <a href="index.php?page=produk" class="bg-gray-200 text-gray-700 px-4 py-2 rounded">Lanjut Belanja</a>
                <a href="index.php?page=checkout" class="bg-primary text-white px-4 py-2 rounded">Checkout</a>
            </div>
        </div>
    <?php endif; ?>
</div>