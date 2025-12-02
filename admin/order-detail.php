<?php
// filepath: c:\laragon\www\gayamita-cakes\admin\order-detail.php
session_start();
require_once '../method/function.php';

if (!is_admin_logged_in()) {
    header('Location: login.php');
    exit;
}

$order_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Helper function
function get_order_by_id($id)
{
    global $conn;
    $sql = "SELECT * FROM orders WHERE id = :id LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

$order = get_order_by_id($order_id);

if (!$order) {
    header('Location: orders.php');
    exit;
}

$order_items = get_order_items($order['id']);

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $new_status = $_POST['status'];
    if (update_order_status($order['id'], $new_status)) {
        $_SESSION['success'] = 'Status pesanan berhasil diupdate!';
        header('Location: order-detail.php?id=' . $order['id']);
        exit;
    } else {
        $error = 'Gagal mengupdate status!';
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pesanan #<?= $order['order_number'] ?> - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-50">
    <?php include 'components/sidebar.php'; ?>

    <!-- Main Content -->
    <div class="ml-72 p-8 min-h-screen">
        <!-- Page Header -->
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-4xl font-bold text-gray-800">Detail Pesanan</h1>
            <a href="orders.php" class="bg-white text-amber-900 px-6 py-3 rounded-xl font-semibold shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all inline-flex items-center gap-2">
                <i class="fas fa-arrow-left"></i>
                Kembali
            </a>
        </div>

        <!-- Success Alert -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="bg-green-100 text-green-800 px-5 py-4 rounded-xl mb-6 flex items-center gap-3 shadow-md">
                <i class="fas fa-check-circle text-xl"></i>
                <span class="font-medium"><?= $_SESSION['success'] ?></span>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Order Details -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Customer Info -->
                <div class="bg-white rounded-2xl shadow-md overflow-hidden">
                    <div class="flex justify-between items-center p-6 border-b border-gray-100">
                        <h2 class="text-xl font-bold text-gray-800 flex items-center gap-3">
                            <i class="fas fa-user text-amber-600"></i>
                            Informasi Customer
                        </h2>
                        <span class="bg-gradient-to-r from-amber-900 to-orange-700 text-white px-5 py-2 rounded-full text-sm font-semibold">
                            #<?= $order['order_number'] ?>
                        </span>
                    </div>

                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-xs text-gray-500 font-semibold uppercase tracking-wide mb-2">Nama Lengkap</p>
                            <p class="text-gray-800 font-semibold flex items-center gap-2">
                                <i class="fas fa-user-circle text-orange-600"></i>
                                <?= htmlspecialchars($order['nama_lengkap']) ?>
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-500 font-semibold uppercase tracking-wide mb-2">No. Telepon</p>
                            <p class="text-gray-800 font-semibold flex items-center gap-2">
                                <i class="fas fa-phone text-orange-600"></i>
                                <?= htmlspecialchars($order['no_telepon']) ?>
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-500 font-semibold uppercase tracking-wide mb-2">Email</p>
                            <p class="text-gray-800 font-semibold flex items-center gap-2">
                                <i class="fas fa-envelope text-orange-600"></i>
                                <?= htmlspecialchars($order['email']) ?>
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-500 font-semibold uppercase tracking-wide mb-2">Kota & Kode Pos</p>
                            <p class="text-gray-800 font-semibold flex items-center gap-2">
                                <i class="fas fa-city text-orange-600"></i>
                                <?= htmlspecialchars($order['kota']) ?>, <?= htmlspecialchars($order['kode_pos']) ?>
                            </p>
                        </div>

                        <div class="md:col-span-2">
                            <p class="text-xs text-gray-500 font-semibold uppercase tracking-wide mb-2">Alamat Lengkap</p>
                            <p class="text-gray-800 font-semibold flex items-start gap-2">
                                <i class="fas fa-map-marker-alt text-orange-600 mt-1"></i>
                                <span><?= nl2br(htmlspecialchars($order['alamat'])) ?></span>
                            </p>
                        </div>

                        <?php if (!empty($order['catatan'])): ?>
                            <div class="md:col-span-2">
                                <p class="text-xs text-gray-500 font-semibold uppercase tracking-wide mb-2">Catatan</p>
                                <p class="text-gray-800 font-semibold flex items-start gap-2">
                                    <i class="fas fa-sticky-note text-orange-600 mt-1"></i>
                                    <span><?= nl2br(htmlspecialchars($order['catatan'])) ?></span>
                                </p>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- WhatsApp Button -->
                    <div class="p-6 bg-gradient-to-r from-green-500 to-emerald-600">
                        <div class="text-center">
                            <h3 class="text-white font-bold mb-3">Hubungi Customer</h3>
                            <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $order['no_telepon']) ?>"
                                target="_blank"
                                class="bg-white text-green-600 px-6 py-3 rounded-xl font-semibold inline-flex items-center gap-2 hover:shadow-xl hover:scale-105 transition-all">
                                <i class="fab fa-whatsapp text-xl"></i>
                                Chat via WhatsApp
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="bg-white rounded-2xl shadow-md overflow-hidden">
                    <div class="p-6 border-b border-gray-100">
                        <h2 class="text-xl font-bold text-gray-800 flex items-center gap-3">
                            <i class="fas fa-shopping-bag text-amber-600"></i>
                            Item Pesanan
                        </h2>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Produk</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Harga</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Jumlah</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <?php foreach ($order_items as $item): ?>
                                    <tr>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-4">
                                                <img src="<?= htmlspecialchars($item['product_image']) ?>"
                                                    alt="<?= htmlspecialchars($item['product_name']) ?>"
                                                    class="w-16 h-16 rounded-xl object-cover shadow-md">
                                                <span class="font-semibold text-gray-800"><?= htmlspecialchars($item['product_name']) ?></span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-gray-700">Rp <?= number_format($item['price'], 0, ',', '.') ?></td>
                                        <td class="px-6 py-4 text-gray-700"><?= $item['quantity'] ?>x</td>
                                        <td class="px-6 py-4">
                                            <span class="font-bold text-gray-800">Rp <?= number_format($item['subtotal'], 0, ',', '.') ?></span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Total -->
                    <div class="p-6 bg-gray-50">
                        <div class="flex justify-between items-center text-xl font-bold text-amber-900">
                            <span>Total Pembayaran</span>
                            <span>Rp <?= number_format($order['total'], 0, ',', '.') ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Status & Actions -->
            <div class="space-y-6">
                <div class="bg-white rounded-2xl shadow-md overflow-hidden">
                    <div class="p-6 border-b border-gray-100">
                        <h2 class="text-xl font-bold text-gray-800 flex items-center gap-3">
                            <i class="fas fa-info-circle text-amber-600"></i>
                            Status Pesanan
                        </h2>
                    </div>

                    <div class="p-6 space-y-6">
                        <div>
                            <p class="text-xs text-gray-500 font-semibold uppercase tracking-wide mb-3">Status Saat Ini</p>
                            <?php
                            $status_classes = [
                                'pending' => 'bg-yellow-100 text-yellow-800',
                                'dikonfirmasi' => 'bg-blue-100 text-blue-800',
                                'diproses' => 'bg-green-100 text-green-800',
                                'dikirim' => 'bg-indigo-100 text-indigo-800',
                                'selesai' => 'bg-green-100 text-green-800',
                                'dibatalkan' => 'bg-red-100 text-red-800'
                            ];
                            $class = $status_classes[$order['status']] ?? 'bg-gray-100 text-gray-800';
                            ?>
                            <span class="px-5 py-3 rounded-xl text-sm font-bold inline-block <?= $class ?>">
                                <?= ucfirst($order['status']) ?>
                            </span>
                        </div>

                        <div>
                            <p class="text-xs text-gray-500 font-semibold uppercase tracking-wide mb-2">Tanggal Order</p>
                            <p class="text-gray-800 font-semibold flex items-center gap-2">
                                <i class="fas fa-calendar text-orange-600"></i>
                                <?= date('d M Y, H:i', strtotime($order['created_at'])) ?>
                            </p>
                        </div>

                        <!-- Update Status Form -->
                        <form method="POST" class="bg-gray-50 rounded-xl p-5 space-y-4">
                            <label class="block text-gray-800 font-semibold text-sm">Update Status Pesanan</label>
                            <select name="status"
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-amber-600 focus:ring-4 focus:ring-amber-100 outline-none transition-all font-semibold text-gray-800"
                                required>
                                <option value="pending" <?= $order['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="dikonfirmasi" <?= $order['status'] == 'dikonfirmasi' ? 'selected' : '' ?>>Dikonfirmasi</option>
                                <option value="diproses" <?= $order['status'] == 'diproses' ? 'selected' : '' ?>>Diproses</option>
                                <option value="dikirim" <?= $order['status'] == 'dikirim' ? 'selected' : '' ?>>Dikirim</option>
                                <option value="selesai" <?= $order['status'] == 'selesai' ? 'selected' : '' ?>>Selesai</option>
                                <option value="dibatalkan" <?= $order['status'] == 'dibatalkan' ? 'selected' : '' ?>>Dibatalkan</option>
                            </select>

                            <button type="submit" name="update_status"
                                class="w-full bg-gradient-to-r from-amber-900 to-orange-700 text-white py-3 rounded-xl font-semibold hover:shadow-xl hover:-translate-y-1 transition-all flex items-center justify-center gap-2">
                                <i class="fas fa-check-circle"></i>
                                Update Status
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>