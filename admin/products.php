<?php
// filepath: c:\laragon\www\gayamita-cakes\admin\products.php
session_start();
require_once '../method/function.php';

if (!is_admin_logged_in()) {
    header('Location: login.php');
    exit;
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    if (delete_product($id)) {
        $_SESSION['success'] = 'Produk berhasil dihapus!';
    } else {
        $_SESSION['error'] = 'Gagal menghapus produk!';
    }
    header('Location: products.php');
    exit;
}

// Pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 12;
$offset = ($page - 1) * $limit;

$products = get_all_products_admin($limit, $offset);
$total_products = get_total_products();
$total_pages = ceil($total_products / $limit);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Produk - Admin Gayamita Cakes</title>
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
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-4xl font-bold text-gray-800 mb-2">Kelola Produk</h1>
                <p class="text-gray-600">Total <?= $total_products ?> produk</p>
            </div>
            <a href="product-add.php" class="bg-gradient-to-r from-amber-900 to-orange-700 text-white px-6 py-3 rounded-xl font-semibold shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all inline-flex items-center gap-2">
                <i class="fas fa-plus-circle"></i>
                Tambah Produk
            </a>
        </div>

        <!-- Alert Messages -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="bg-green-100 text-green-800 px-5 py-4 rounded-xl mb-6 flex items-center gap-3 shadow-md">
                <i class="fas fa-check-circle text-xl"></i>
                <span class="font-medium"><?= $_SESSION['success'] ?></span>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="bg-red-100 text-red-800 px-5 py-4 rounded-xl mb-6 flex items-center gap-3 shadow-md">
                <i class="fas fa-exclamation-circle text-xl"></i>
                <span class="font-medium"><?= $_SESSION['error'] ?></span>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <!-- Products Grid -->
        <?php if (count($products) > 0): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
                <?php foreach ($products as $product): ?>
                    <div class="bg-white rounded-2xl shadow-md overflow-hidden hover:shadow-xl transition-all group">
                        <div class="relative overflow-hidden h-56">
                            <img src="../assets/<?= htmlspecialchars($product['image']) ?>"
                                alt="<?= htmlspecialchars($product['nama']) ?>"
                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                            <div class="absolute top-3 right-3 flex gap-2">
                                <div class="bg-amber-900 text-white px-3 py-1 rounded-full text-xs font-bold flex items-center gap-1">
                                    <i class="fas fa-star"></i>
                                    <?= $product['rating'] ?>
                                </div>
                            </div>
                        </div>

                        <div class="p-5">
                            <h3 class="font-bold text-gray-800 mb-2 text-lg"><?= htmlspecialchars($product['nama']) ?></h3>
                            <p class="text-gray-600 text-sm mb-3 line-clamp-2"><?= htmlspecialchars($product['deskripsi']) ?></p>

                            <div class="mb-4">
                                <span class="text-amber-900 font-bold text-xl">Rp <?= number_format($product['harga'], 0, ',', '.') ?></span>
                            </div>

                            <div class="flex gap-2">
                                <a href="product-edit.php?id=<?= $product['id'] ?>"
                                    class="flex-1 bg-blue-600 text-white py-2.5 rounded-lg text-sm font-semibold hover:bg-blue-700 transition-colors inline-flex items-center justify-center gap-2">
                                    <i class="fas fa-edit"></i>
                                    Edit
                                </a>
                                <a href="products.php?delete=<?= $product['id'] ?>"
                                    onclick="return confirm('Yakin ingin menghapus produk <?= htmlspecialchars($product['nama']) ?>?')"
                                    class="flex-1 bg-red-600 text-white py-2.5 rounded-lg text-sm font-semibold hover:bg-red-700 transition-colors inline-flex items-center justify-center gap-2">
                                    <i class="fas fa-trash"></i>
                                    Hapus
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Pagination -->
            <?php if ($total_pages > 1): ?>
                <div class="flex justify-center items-center gap-2">
                    <?php if ($page > 1): ?>
                        <a href="?page=<?= $page - 1 ?>" class="px-4 py-2 rounded-lg text-gray-700 hover:bg-white hover:shadow-md transition-all">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <a href="?page=<?= $i ?>"
                            class="px-4 py-2 rounded-lg font-medium transition-all <?= $i == $page ? 'bg-gradient-to-r from-amber-900 to-orange-700 text-white shadow-lg' : 'text-gray-700 hover:bg-white hover:shadow-md' ?>">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>

                    <?php if ($page < $total_pages): ?>
                        <a href="?page=<?= $page + 1 ?>" class="px-4 py-2 rounded-lg text-gray-700 hover:bg-white hover:shadow-md transition-all">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="bg-white rounded-2xl shadow-md p-20 text-center">
                <i class="fas fa-birthday-cake text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-xl text-gray-600 font-semibold mb-2">Belum ada produk</h3>
                <p class="text-gray-400 mb-6">Mulai tambahkan produk pertama Anda</p>
                <a href="product-add.php" class="bg-gradient-to-r from-amber-900 to-orange-700 text-white px-6 py-3 rounded-xl font-semibold inline-flex items-center gap-2 hover:shadow-lg transition-all">
                    <i class="fas fa-plus-circle"></i>
                    Tambah Produk
                </a>
            </div>
        <?php endif; ?>
    </div>
</body>

</html>