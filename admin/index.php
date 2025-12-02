<?php
// filepath: c:\laragon\www\gayamita-cakes\admin\index.php
session_start();
require_once '../method/function.php';
if (!is_admin_logged_in()) {
    header('Location: login.php');
    exit;
}

$stats = get_dashboard_stats();
$recent_orders = get_all_orders(10, 0);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Admin Gayamita Cakes</title>
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
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-800 mb-2">Dashboard</h1>
            <p class="text-gray-600">Selamat datang kembali, <?= $_SESSION['admin_nama'] ?>!</p>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Orders -->
            <div class="bg-white rounded-2xl shadow-md p-6 hover:shadow-xl transition-all hover:-translate-y-1 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-purple-50 rounded-full -mr-16 -mt-16"></div>
                <div class="relative">
                    <div class="w-14 h-14 bg-gradient-to-br from-purple-500 to-purple-700 rounded-xl flex items-center justify-center text-white text-2xl mb-4 shadow-lg">
                        <i class="fas fa-shopping-bag"></i>
                    </div>
                    <p class="text-gray-500 text-sm font-semibold uppercase tracking-wide mb-2">Total Pesanan</p>
                    <h3 class="text-3xl font-bold text-gray-800 mb-2"><?= number_format($stats['total_orders']) ?></h3>
                    <p class="text-sm text-green-500 flex items-center gap-1">
                        <i class="fas fa-arrow-up"></i>
                        <span>Semua waktu</span>
                    </p>
                </div>
            </div>

            <!-- Pending Orders -->
            <div class="bg-white rounded-2xl shadow-md p-6 hover:shadow-xl transition-all hover:-translate-y-1 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-pink-50 rounded-full -mr-16 -mt-16"></div>
                <div class="relative">
                    <div class="w-14 h-14 bg-gradient-to-br from-pink-500 to-red-500 rounded-xl flex items-center justify-center text-white text-2xl mb-4 shadow-lg">
                        <i class="fas fa-clock"></i>
                    </div>
                    <p class="text-gray-500 text-sm font-semibold uppercase tracking-wide mb-2">Pesanan Pending</p>
                    <h3 class="text-3xl font-bold text-gray-800 mb-2"><?= number_format($stats['pending_orders']) ?></h3>
                    <p class="text-sm text-orange-500 flex items-center gap-1">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>Perlu ditindaklanjuti</span>
                    </p>
                </div>
            </div>

            <!-- Completed Orders -->
            <div class="bg-white rounded-2xl shadow-md p-6 hover:shadow-xl transition-all hover:-translate-y-1 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-green-50 rounded-full -mr-16 -mt-16"></div>
                <div class="relative">
                    <div class="w-14 h-14 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center text-white text-2xl mb-4 shadow-lg">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <p class="text-gray-500 text-sm font-semibold uppercase tracking-wide mb-2">Pesanan Selesai</p>
                    <h3 class="text-3xl font-bold text-gray-800 mb-2"><?= number_format($stats['completed_orders']) ?></h3>
                    <p class="text-sm text-green-500 flex items-center gap-1">
                        <i class="fas fa-arrow-up"></i>
                        <span>Total selesai</span>
                    </p>
                </div>
            </div>

            <!-- Total Revenue -->
            <div class="bg-white rounded-2xl shadow-md p-6 hover:shadow-xl transition-all hover:-translate-y-1 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-yellow-50 rounded-full -mr-16 -mt-16"></div>
                <div class="relative">
                    <div class="w-14 h-14 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-xl flex items-center justify-center text-amber-900 text-2xl mb-4 shadow-lg">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <p class="text-gray-500 text-sm font-semibold uppercase tracking-wide mb-2">Total Pendapatan</p>
                    <h3 class="text-2xl font-bold text-gray-800 mb-2">Rp <?= number_format($stats['total_revenue'], 0, ',', '.') ?></h3>
                    <p class="text-sm text-green-500 flex items-center gap-1">
                        <i class="fas fa-arrow-up"></i>
                        <span>Dari pesanan selesai</span>
                    </p>
                </div>
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="bg-white rounded-2xl shadow-md overflow-hidden">
            <div class="flex justify-between items-center p-6 border-b border-gray-100">
                <h2 class="text-xl font-bold text-gray-800 flex items-center gap-3">
                    <i class="fas fa-shopping-bag text-amber-600"></i>
                    Pesanan Terbaru
                </h2>
                <a href="orders.php" class="bg-gradient-to-r from-amber-900 to-orange-700 text-white px-5 py-2 rounded-xl font-semibold text-sm hover:shadow-lg hover:-translate-y-0.5 transition-all inline-flex items-center gap-2">
                    Lihat Semua
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>

            <?php if (count($recent_orders) > 0): ?>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">No. Order</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama Customer</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Total</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <?php foreach ($recent_orders as $order): ?>
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <span class="font-semibold text-amber-900">#<?= $order['order_number'] ?></span>
                                    </td>
                                    <td class="px-6 py-4 text-gray-800"><?= htmlspecialchars($order['nama_lengkap']) ?></td>
                                    <td class="px-6 py-4">
                                        <span class="font-bold text-gray-800">Rp <?= number_format($order['total'], 0, ',', '.') ?></span>
                                    </td>
                                    <td class="px-6 py-4">
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
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold <?= $class ?>">
                                            <?= ucfirst($order['status']) ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-gray-600 text-sm"><?= date('d M Y H:i', strtotime($order['created_at'])) ?></td>
                                    <td class="px-6 py-4">
                                        <a href="order-detail.php?id=<?= $order['id'] ?>"
                                            class="bg-amber-900 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-orange-700 transition-colors inline-flex items-center gap-2">
                                            <i class="fas fa-eye"></i>
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-20">
                    <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-xl text-gray-600 font-semibold mb-2">Belum ada pesanan</h3>
                    <p class="text-gray-400">Pesanan akan muncul di sini setelah customer melakukan pemesanan</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>