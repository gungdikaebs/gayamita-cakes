<?php
$page = isset($_GET['page']) ? $_GET['page'] : 'beranda';

// Daftar file yang boleh di-include
$allowed_pages = [
    'beranda' => 'beranda.php',
    'tentang-kami' => 'tentang-kami.php',
    'produk' => 'produk.php',
    'kontak' => 'kontak.php',
    'ulasan' => 'ulasan.php',
    'detail-produk' => 'detail-produk.php'
];

// Tentukan file mana yang akan di-include
$file_to_include = isset($allowed_pages[$page]) ? $allowed_pages[$page] : $allowed_pages['beranda'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="./assets/css/output.css">
    <title>Gayamita Cakes</title>
</head>

<body>
    <div class="w-full">
        <?php require 'public/component/navbar.php'; ?>
        <?php require 'public/' . $file_to_include; ?>
        <?php require 'public/component/footer.php'; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindplus/elements@1" type="module"></script>
    <script src="assets/js/navbar-scroll.js" type="module"></script>
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>

</body>

</html>