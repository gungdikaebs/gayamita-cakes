<?php
if (!is_admin_logged_in()) {
    header('Location: login.php');
    exit;
}

$current_page = basename($_SERVER['PHP_SELF']);
?>
<div class="fixed left-0 top-0 h-screen w-72 bg-gradient-to-b from-amber-900 via-orange-800 to-amber-700 shadow-2xl z-50">
    <!-- Logo -->
    <div class="text-center py-8 border-b border-white/10">
        <div class="w-16 h-16 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-full inline-flex items-center justify-center text-3xl mb-4 shadow-lg">
            🎂
        </div>
        <h2 class="text-white text-xl font-bold">Gayamita Cakes</h2>
        <p class="text-white/70 text-xs mt-1">Admin Panel</p>
    </div>

    <!-- Menu -->
    <nav class="py-8 px-4">
        <a href="index.php" class="flex items-center gap-4 px-6 py-3 mb-2 text-white/80 hover:bg-white/10 rounded-xl transition-all group <?= $current_page == 'index.php' ? 'bg-white/20 text-white' : '' ?>">
            <i class="fas fa-home w-6 text-lg"></i>
            <span class="font-medium">Dashboard</span>
        </a>

        <a href="orders.php" class="flex items-center gap-4 px-6 py-3 mb-2 text-white/80 hover:bg-white/10 rounded-xl transition-all group <?= $current_page == 'orders.php' ? 'bg-white/20 text-white' : '' ?>">
            <i class="fas fa-shopping-bag w-6 text-lg"></i>
            <span class="font-medium">Kelola Pesanan</span>
        </a>

        <a href="order-detail.php" class="flex items-center gap-4 px-6 py-3 mb-2 text-white/80 hover:bg-white/10 rounded-xl transition-all group <?= strpos($current_page, 'order-detail') !== false ? 'bg-white/20 text-white' : '' ?>">
            <i class="fas fa-file-invoice w-6 text-lg"></i>
            <span class="font-medium">Detail Pesanan</span>
        </a>
    </nav>

    <!-- Footer -->
    <div class="absolute bottom-8 left-0 right-0 px-6">
        <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 mb-4">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-full flex items-center justify-center text-amber-900 font-bold text-lg">
                    <?= strtoupper(substr($_SESSION['admin_nama'], 0, 1)) ?>
                </div>
                <div>
                    <h4 class="text-white text-sm font-semibold"><?= $_SESSION['admin_nama'] ?></h4>
                    <p class="text-white/60 text-xs">Administrator</p>
                </div>
            </div>
        </div>

        <a href="logout.php" class="w-full bg-white/10 hover:bg-white/20 border-2 border-white/20 text-white py-3 px-4 rounded-xl font-semibold flex items-center justify-center gap-2 transition-all">
            <i class="fas fa-sign-out-alt"></i>
            Keluar
        </a>
    </div>
</div>