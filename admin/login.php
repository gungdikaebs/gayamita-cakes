<?php
// filepath: c:\laragon\www\gayamita-cakes\admin\login.php
session_start();
require_once '../method/function.php';

if (is_admin_logged_in()) {
    header('Location: index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (admin_login($username, $password)) {
        header('Location: index.php');
        exit;
    } else {
        $error = 'Username atau password salah!';
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Gayamita Cakes</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>

<body class="bg-gradient-to-br from-amber-900 via-orange-800 to-amber-700 min-h-screen flex items-center justify-center p-5">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md p-10">
        <!-- Logo Section -->
        <div class="text-center mb-10">
            <div class="w-20 h-20 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-full inline-flex items-center justify-center text-4xl mb-5 shadow-lg">
                🎂
            </div>
            <h1 class="text-3xl font-bold text-amber-900 mb-2">Admin Panel</h1>
            <p class="text-gray-500 text-sm">Gayamita Cakes Management</p>
        </div>

        <!-- Error Message -->
        <?php if ($error): ?>
            <div class="bg-red-100 text-red-700 px-4 py-3 rounded-lg mb-6 flex items-center gap-3">
                <i class="fas fa-exclamation-circle"></i>
                <span><?= $error ?></span>
            </div>
        <?php endif; ?>

        <!-- Login Form -->
        <form method="POST">
            <div class="mb-6">
                <label class="block text-gray-700 font-semibold mb-2 text-sm">Username</label>
                <div class="relative">
                    <i class="fas fa-user absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="text" name="username"
                        class="w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:border-amber-600 focus:ring-4 focus:ring-amber-100 outline-none transition-all"
                        placeholder="Masukkan username" required autofocus>
                </div>
            </div>

            <div class="mb-8">
                <label class="block text-gray-700 font-semibold mb-2 text-sm">Password</label>
                <div class="relative">
                    <i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="password" name="password"
                        class="w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:border-amber-600 focus:ring-4 focus:ring-amber-100 outline-none transition-all"
                        placeholder="Masukkan password" required>
                </div>
            </div>

            <button type="submit"
                class="w-full bg-gradient-to-r from-amber-900 to-orange-700 text-white py-3 rounded-xl font-semibold hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex items-center justify-center gap-2">
                <i class="fas fa-sign-in-alt"></i>
                Masuk ke Dashboard
            </button>
        </form>

        <!-- Back Link -->
        <div class="text-center mt-6">
            <a href="../" class="text-orange-700 hover:text-amber-900 text-sm font-medium inline-flex items-center gap-2 hover:gap-3 transition-all">
                <i class="fas fa-arrow-left"></i>
                Kembali ke Website
            </a>
        </div>
    </div>
</body>

</html>