<?php
// filepath: c:\laragon\www\gayamita-cakes\admin\orders.php
session_start();
require_once '../method/function.php';

if (!is_admin_logged_in()) {
    header('Location: login.php');
    exit;
}

// Pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 20;
$offset = ($page - 1) * $limit;

// Filter status
$filter_status = isset($_GET['status']) ? $_GET['status'] : null;

// Get orders
$orders = get_all_orders($limit, $offset, $filter_status);
$total_orders = get_total_orders($filter_status);
$total_pages = ceil($total_orders / $limit);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pesanan - Admin Gayamita Cakes</title>
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

    <div class="ml-72 p-8 min-h-screen">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="text-4xl font-bold text-gray-800">Kelola Pesanan</h1>
        </div>

        <!-- Filter Section -->
        <div class="bg-white rounded-2xl shadow-md p-6 mb-6 mt-8">
            <div class="flex flex-wrap items-center gap-3">
                <label class="text-gray-700 font-semibold text-sm">Filter Status:</label>

                <a href="orders.php"
                    class="px-5 py-2 rounded-xl border-2 font-medium text-sm transition-all <?= !$filter_status ? 'bg-gradient-to-r from-amber-900 to-orange-700 text-white border-amber-900' : 'border-gray-300 text-gray-700 hover:border-amber-900 hover:text-amber-900' ?>">
                    Semua
                </a>

                <a href="orders.php?status=pending"
                    class="px-5 py-2 rounded-xl border-2 font-medium text-sm transition-all <?= $filter_status == 'pending' ? 'bg-gradient-to-r from-amber-900 to-orange-700 text-white border-amber-900' : 'border-gray-300 text-gray-700 hover:border-amber-900 hover:text-amber-900' ?>">
                    Pending
                </a>

                <a href="orders.php?status=dikonfirmasi"
                    class="px-5 py-2 rounded-xl border-2 font-medium text-sm transition-all <?= $filter_status == 'dikonfirmasi' ? 'bg-gradient-to-r from-amber-900 to-orange-700 text-white border-amber-900' : 'border-gray-300 text-gray-700 hover:border-amber-900 hover:text-amber-900' ?>">
                    Dikonfirmasi
                </a>

                <a href="orders.php?status=diproses"
                    class="px-5 py-2 rounded-xl border-2 font-medium text-sm transition-all <?= $filter_status == 'diproses' ? 'bg-gradient-to-r from-amber-900 to-orange-700 text-white border-amber-900' : 'border-gray-300 text-gray-700 hover:border-amber-900 hover:text-amber-900' ?>">
                    Diproses
                </a>

                <a href="orders.php?status=dikirim"
                    class="px-5 py-2 rounded-xl border-2 font-medium text-sm transition-all <?= $filter_status == 'dikirim' ? 'bg-gradient-to-r from-amber-900 to-orange-700 text-white border-amber-900' : 'border-gray-300 text-gray-700 hover:border-amber-900 hover:text-amber-900' ?>">
                    Dikirim
                </a>

                <a href="orders.php?status=selesai"
                    class="px-5 py-2 rounded-xl border-2 font-medium text-sm transition-all <?= $filter_status == 'selesai' ? 'bg-gradient-to-r from-amber-900 to-orange-700 text-white border-amber-900' : 'border-gray-300 text-gray-700 hover:border-amber-900 hover:text-amber-900' ?>">
                    Selesai
                </a>
            </div>
        </div>

        <!-- Orders Table -->
        <div class="bg-white rounded-2xl shadow-md overflow-hidden">
            <?php if (count($orders) > 0): ?>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">No. Order</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Customer</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Total</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <?php foreach ($orders as $order): ?>
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-5">
                                        <span class="font-bold text-amber-900 text-base">#<?= $order['order_number'] ?></span>
                                    </td>
                                    <td class="px-6 py-5">
                                        <div class="flex flex-col gap-1">
                                            <span class="font-semibold text-gray-800"><?= htmlspecialchars($order['nama_lengkap']) ?></span>
                                            <span class="text-xs text-gray-500 flex items-center gap-1">
                                                <i class="fas fa-phone"></i>
                                                <?= htmlspecialchars($order['no_telepon']) ?>
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5">
                                        <span class="font-bold text-gray-800">Rp <?= number_format($order['total'], 0, ',', '.') ?></span>
                                    </td>
                                    <td class="px-6 py-5">
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
                                        <span class="px-4 py-2 rounded-full text-xs font-semibold inline-block min-w-[100px] text-center <?= $class ?>">
                                            <?= ucfirst($order['status']) ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-5 text-gray-600"><?= date('d M Y, H:i', strtotime($order['created_at'])) ?></td>
                                    <td class="px-6 py-5">
                                        <a href="order-detail.php?id=<?= $order['id'] ?>"
                                            class="bg-gradient-to-r from-amber-900 to-orange-700 text-white px-5 py-2 rounded-xl text-sm font-medium hover:shadow-lg hover:-translate-y-0.5 transition-all inline-flex items-center gap-2">
                                            <i class="fas fa-eye"></i>
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <?php if ($total_pages > 1): ?>
                    <div class="flex justify-center items-center gap-2 py-6 border-t border-gray-100">
                        <?php if ($page > 1): ?>
                            <a href="?page=<?= $page - 1 ?><?= $filter_status ? '&status=' . $filter_status : '' ?>"
                                class="px-4 py-2 rounded-lg text-gray-700 hover:bg-gray-100 transition-colors">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <a href="?page=<?= $i ?><?= $filter_status ? '&status=' . $filter_status : '' ?>"
                                class="px-4 py-2 rounded-lg font-medium transition-all <?= $i == $page ? 'bg-gradient-to-r from-amber-900 to-orange-700 text-white' : 'text-gray-700 hover:bg-gray-100' ?>">
                                <?= $i ?>
                            </a>
                        <?php endfor; ?>

                        <?php if ($page < $total_pages): ?>
                            <a href="?page=<?= $page + 1 ?><?= $filter_status ? '&status=' . $filter_status : '' ?>"
                                class="px-4 py-2 rounded-lg text-gray-700 hover:bg-gray-100 transition-colors">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="text-center py-20">
                    <i class="fas fa-shopping-bag text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-xl text-gray-600 font-semibold mb-2">Tidak ada pesanan</h3>
                    <p class="text-gray-400">Pesanan dengan status ini belum ada</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>