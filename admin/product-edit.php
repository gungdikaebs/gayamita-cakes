<?php
// filepath: c:\laragon\www\gayamita-cakes\admin\product-edit.php
session_start();
require_once '../method/function.php';

if (!is_admin_logged_in()) {
    header('Location: login.php');
    exit;
}

$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$product = get_product_by_id($product_id);

if (!$product) {
    $_SESSION['error'] = 'Produk tidak ditemukan!';
    header('Location: products.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama']);
    $deskripsi = trim($_POST['deskripsi']);
    $harga = (int)$_POST['harga'];
    $rating = (int)$_POST['rating'];

    // Validate
    if (empty($nama) || empty($deskripsi) || $harga <= 0) {
        $error = 'Semua field harus diisi dengan benar!';
    } else {
        // Check if new image uploaded
        $image_path = $product['image']; // Keep old image by default

        if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
            // Upload new image
            $upload_result = upload_product_image($_FILES['image']);

            if ($upload_result['success']) {
                // Delete old image
                $old_image = __DIR__ . '/../assets/' . $product['image'];
                if (file_exists($old_image)) {
                    unlink($old_image);
                }
                $image_path = $upload_result['path'];
            } else {
                $error = $upload_result['message'];
            }
        }

        if (empty($error)) {
            $data = [
                'nama' => $nama,
                'deskripsi' => $deskripsi,
                'harga' => $harga,
                'image' => $image_path,
                'rating' => $rating
            ];

            if (update_product($product_id, $data)) {
                $_SESSION['success'] = 'Produk berhasil diupdate!';
                header('Location: products.php');
                exit;
            } else {
                $error = 'Gagal mengupdate produk!';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk - Admin Gayamita Cakes</title>
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
                <h1 class="text-4xl font-bold text-gray-800 mb-2">Edit Produk</h1>
                <p class="text-gray-600"><?= htmlspecialchars($product['nama']) ?></p>
            </div>
            <a href="products.php" class="bg-white text-amber-900 px-6 py-3 rounded-xl font-semibold shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all inline-flex items-center gap-2">
                <i class="fas fa-arrow-left"></i>
                Kembali
            </a>
        </div>

        <!-- Error Alert -->
        <?php if ($error): ?>
            <div class="bg-red-100 text-red-800 px-5 py-4 rounded-xl mb-6 flex items-center gap-3 shadow-md">
                <i class="fas fa-exclamation-circle text-xl"></i>
                <span class="font-medium"><?= $error ?></span>
            </div>
        <?php endif; ?>

        <!-- Form -->
        <div class="bg-white rounded-2xl shadow-md p-8 max-w-3xl">
            <form method="POST" enctype="multipart/form-data" class="space-y-6">
                <!-- Nama Produk -->
                <div>
                    <label class="block text-gray-800 font-semibold mb-2">Nama Produk</label>
                    <input type="text" name="nama"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-amber-600 focus:ring-4 focus:ring-amber-100 outline-none transition-all"
                        placeholder="Contoh: Chocolate Cake"
                        value="<?= htmlspecialchars($product['nama']) ?>"
                        required>
                </div>

                <!-- Harga -->
                <div>
                    <label class="block text-gray-800 font-semibold mb-2">Harga (Rp)</label>
                    <input type="number" name="harga"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-amber-600 focus:ring-4 focus:ring-amber-100 outline-none transition-all"
                        placeholder="150000"
                        value="<?= $product['harga'] ?>"
                        required>
                </div>

                <!-- Rating -->
                <div>
                    <label class="block text-gray-800 font-semibold mb-2">Rating (1-5)</label>
                    <select name="rating"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-amber-600 focus:ring-4 focus:ring-amber-100 outline-none transition-all"
                        required>
                        <option value="">Pilih Rating</option>
                        <option value="1" <?= $product['rating'] == 1 ? 'selected' : '' ?>>⭐ 1</option>
                        <option value="2" <?= $product['rating'] == 2 ? 'selected' : '' ?>>⭐⭐ 2</option>
                        <option value="3" <?= $product['rating'] == 3 ? 'selected' : '' ?>>⭐⭐⭐ 3</option>
                        <option value="4" <?= $product['rating'] == 4 ? 'selected' : '' ?>>⭐⭐⭐⭐ 4</option>
                        <option value="5" <?= $product['rating'] == 5 ? 'selected' : '' ?>>⭐⭐⭐⭐⭐ 5</option>
                    </select>
                </div>

                <!-- Deskripsi -->
                <div>
                    <label class="block text-gray-800 font-semibold mb-2">Deskripsi</label>
                    <textarea name="deskripsi" rows="4"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-amber-600 focus:ring-4 focus:ring-amber-100 outline-none transition-all"
                        placeholder="Deskripsi lengkap produk..."
                        required><?= htmlspecialchars($product['deskripsi']) ?></textarea>
                </div>

                <!-- Current Image Preview -->
                <div>
                    <label class="block text-gray-800 font-semibold mb-2">Gambar Saat Ini</label>
                    <div class="mb-4">
                        <img src="../public/assets/<?= htmlspecialchars($product['image']) ?>"
                            alt="<?= htmlspecialchars($product['nama']) ?>"
                            class="w-48 h-48 object-cover rounded-xl shadow-lg">
                    </div>
                </div>

                <!-- Image Upload -->
                <div>
                    <label class="block text-gray-800 font-semibold mb-2">Upload Gambar Baru (Opsional)</label>
                    <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-amber-600 transition-colors">
                        <input type="file" name="image" id="image" accept="image/*" class="hidden" onchange="previewImage(event)">
                        <label for="image" class="cursor-pointer">
                            <div id="preview-container" class="mb-4 hidden">
                                <img id="preview-image" class="max-w-xs mx-auto rounded-lg shadow-md">
                            </div>
                            <i class="fas fa-cloud-upload-alt text-5xl text-gray-400 mb-3"></i>
                            <p class="text-gray-600 font-medium mb-1">Klik untuk upload gambar baru</p>
                            <p class="text-gray-400 text-sm">PNG, JPG, GIF, WEBP (Max 5MB)</p>
                            <p class="text-amber-600 text-xs mt-2">* Kosongkan jika tidak ingin mengubah gambar</p>
                        </label>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex gap-4 pt-4">
                    <button type="submit"
                        class="flex-1 bg-gradient-to-r from-amber-900 to-orange-700 text-white py-3 rounded-xl font-semibold hover:shadow-xl hover:-translate-y-1 transition-all flex items-center justify-center gap-2">
                        <i class="fas fa-save"></i>
                        Update Produk
                    </button>
                    <a href="products.php"
                        class="flex-1 bg-gray-200 text-gray-700 py-3 rounded-xl font-semibold hover:bg-gray-300 transition-colors flex items-center justify-center gap-2">
                        <i class="fas fa-times"></i>
                        Batal
                    </a>
                </div>
            </form>

            <!-- Delete Button (Separate) -->
            <div class="mt-8 pt-8 border-t-2 border-gray-100">
                <h3 class="text-lg font-bold text-gray-800 mb-3 flex items-center gap-2">
                    <i class="fas fa-exclamation-triangle text-red-600"></i>
                    Danger Zone
                </h3>
                <p class="text-gray-600 mb-4">Hapus produk ini secara permanen. Tindakan ini tidak dapat dibatalkan.</p>
                <a href="products.php?delete=<?= $product['id'] ?>"
                    onclick="return confirm('Yakin ingin menghapus produk <?= htmlspecialchars($product['nama']) ?>? Tindakan ini tidak dapat dibatalkan!')"
                    class="bg-red-600 text-white px-6 py-3 rounded-xl font-semibold hover:bg-red-700 transition-colors inline-flex items-center gap-2">
                    <i class="fas fa-trash-alt"></i>
                    Hapus Produk Ini
                </a>
            </div>
        </div>
    </div>

    <script>
        function previewImage(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('preview-image').src = e.target.result;
                    document.getElementById('preview-container').classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            }
        }
    </script>
</body>

</html>