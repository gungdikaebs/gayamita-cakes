<?php
// filepath: c:\laragon\www\gayamita-cakes\admin\product-add.php
session_start();
require_once '../method/function.php';

if (!is_admin_logged_in()) {
    header('Location: login.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama']);
    $deskripsi = trim($_POST['deskripsi']);
    $harga = (int)$_POST['harga'];

    // Validate
    if (empty($nama) || empty($deskripsi) || $harga <= 0) {
        $error = 'Semua field harus diisi dengan benar!';
    } elseif (!isset($_FILES['image']) || $_FILES['image']['error'] === UPLOAD_ERR_NO_FILE) {
        $error = 'Gambar produk harus diupload!';
    } else {
        // Upload image
        $upload_result = upload_product_image($_FILES['image']);

        if ($upload_result['success']) {
            $data = [
                'nama' => $nama,
                'deskripsi' => $deskripsi,
                'harga' => $harga,
                'image' => $upload_result['path'],
            ];

            if (create_product($data)) {
                $_SESSION['success'] = 'Produk berhasil ditambahkan!';
                header('Location: products.php');
                exit;
            } else {
                $error = 'Gagal menambahkan produk!';
            }
        } else {
            $error = $upload_result['message'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk - Admin Gayamita Cakes</title>
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
            <h1 class="text-4xl font-bold text-gray-800">Tambah Produk Baru</h1>
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
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Form -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-md p-8">
                    <form method="POST" enctype="multipart/form-data" class="space-y-6">
                        <!-- Nama Produk -->
                        <div>
                            <label class="block text-gray-800 font-semibold mb-2 flex items-center gap-2">
                                <i class="fas fa-tag text-amber-600"></i>
                                Nama Produk
                            </label>
                            <input type="text" name="nama"
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-amber-600 focus:ring-4 focus:ring-amber-100 outline-none transition-all"
                                placeholder="Contoh: Chocolate Cake"
                                value="<?= isset($_POST['nama']) ? htmlspecialchars($_POST['nama']) : '' ?>"
                                required>
                        </div>

                        <!-- Harga  -->
                        <div class="grid grid-cols-1 gap-4">
                            <!-- Harga -->
                            <div>
                                <label class="block text-gray-800 font-semibold mb-2 flex items-center gap-2">
                                    <i class="fas fa-money-bill-wave text-amber-600"></i>
                                    Harga (Rp)
                                </label>
                                <input type="number" name="harga"
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-amber-600 focus:ring-4 focus:ring-amber-100 outline-none transition-all"
                                    placeholder="150000"
                                    value="<?= isset($_POST['harga']) ? $_POST['harga'] : '' ?>"
                                    required>
                            </div>


                        </div>

                        <!-- Deskripsi -->
                        <div>
                            <label class="block text-gray-800 font-semibold mb-2 flex items-center gap-2">
                                <i class="fas fa-align-left text-amber-600"></i>
                                Deskripsi
                            </label>
                            <textarea name="deskripsi" rows="5"
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-amber-600 focus:ring-4 focus:ring-amber-100 outline-none transition-all resize-none"
                                placeholder="Deskripsi lengkap produk..."
                                required><?= isset($_POST['deskripsi']) ? htmlspecialchars($_POST['deskripsi']) : '' ?></textarea>
                        </div>

                        <!-- Image Upload -->
                        <div>
                            <label class="block text-gray-800 font-semibold mb-2 flex items-center gap-2">
                                <i class="fas fa-image text-amber-600"></i>
                                Gambar Produk
                            </label>
                            <div class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-amber-600 transition-colors">
                                <input type="file" name="image" id="image" accept="image/*" class="hidden" required onchange="previewImage(event)">
                                <label for="image" class="cursor-pointer">
                                    <div id="preview-container" class="mb-4 hidden">
                                        <img id="preview-image" class="max-w-full max-h-64 mx-auto rounded-xl shadow-lg">
                                    </div>
                                    <div id="upload-placeholder">
                                        <i class="fas fa-cloud-upload-alt text-6xl text-gray-400 mb-4 block"></i>
                                        <p class="text-gray-600 font-semibold mb-2">Klik untuk upload gambar</p>
                                        <p class="text-gray-400 text-sm">PNG, JPG, GIF, WEBP (Max 5MB)</p>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex gap-4 pt-4">
                            <button type="submit"
                                class="flex-1 bg-gradient-to-r from-amber-900 to-orange-700 text-white py-4 rounded-xl font-bold text-lg hover:shadow-xl hover:-translate-y-1 transition-all flex items-center justify-center gap-3">
                                <i class="fas fa-save text-xl"></i>
                                Simpan Produk
                            </button>
                            <a href="products.php"
                                class="flex-1 bg-gray-200 text-gray-700 py-4 rounded-xl font-bold text-lg hover:bg-gray-300 transition-colors flex items-center justify-center gap-3">
                                <i class="fas fa-times text-xl"></i>
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Right Column: Tips -->
            <div class="space-y-6">
                <!-- Tips Card -->
                <div class="bg-gradient-to-br from-amber-50 to-orange-50 rounded-2xl shadow-md p-6 border-2 border-amber-200">
                    <h3 class="text-lg font-bold text-amber-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-lightbulb"></i>
                        Tips Tambah Produk
                    </h3>
                    <ul class="space-y-3 text-sm text-gray-700">
                        <li class="flex items-start gap-2">
                            <i class="fas fa-check-circle text-green-600 mt-0.5"></i>
                            <span>Gunakan <strong>nama yang menarik</strong> dan mudah diingat</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="fas fa-check-circle text-green-600 mt-0.5"></i>
                            <span>Upload <strong>foto berkualitas tinggi</strong> dengan pencahayaan baik</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="fas fa-check-circle text-green-600 mt-0.5"></i>
                            <span>Tulis <strong>deskripsi lengkap</strong> dengan detail bahan dan rasa</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="fas fa-check-circle text-green-600 mt-0.5"></i>
                            <span>Pastikan <strong>harga sesuai</strong> dengan kualitas produk</span>
                        </li>

                    </ul>
                </div>

                <!-- Image Guidelines Card -->
                <div class="bg-white rounded-2xl shadow-md p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <i class="fas fa-camera text-amber-600"></i>
                        Panduan Foto Produk
                    </h3>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-center gap-2">
                            <span class="w-2 h-2 bg-amber-500 rounded-full"></span>
                            <span>Format: JPG, PNG, GIF, WEBP</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="w-2 h-2 bg-amber-500 rounded-full"></span>
                            <span>Ukuran maksimal: 5MB</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="w-2 h-2 bg-amber-500 rounded-full"></span>
                            <span>Resolusi: Min 800x800px</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="w-2 h-2 bg-amber-500 rounded-full"></span>
                            <span>Background: Putih/Netral</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="w-2 h-2 bg-amber-500 rounded-full"></span>
                            <span>Fokus pada produk utama</span>
                        </li>
                    </ul>
                </div>

                <!-- Quick Stats Card -->
                <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl shadow-md p-6 border-2 border-purple-200">
                    <h3 class="text-lg font-bold text-purple-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-chart-line"></i>
                        Statistik Cepat
                    </h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-white/50 backdrop-blur-sm rounded-xl p-3 text-center">
                            <p class="text-2xl font-bold text-purple-900"><?= get_total_products() ?></p>
                            <p class="text-xs text-gray-600">Total Produk</p>
                        </div>
                        <div class="bg-white/50 backdrop-blur-sm rounded-xl p-3 text-center">
                            <p class="text-2xl font-bold text-purple-900"><?= get_total_products() + 1 ?></p>
                            <p class="text-xs text-gray-600">Setelah Ditambah</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function previewImage(event) {
            const file = event.target.files[0];
            if (file) {
                // Validate file size
                if (file.size > 5000000) {
                    alert('Ukuran file terlalu besar! Maksimal 5MB');
                    event.target.value = '';
                    return;
                }

                // Validate file type
                const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                if (!allowedTypes.includes(file.type)) {
                    alert('Format file tidak didukung! Gunakan JPG, PNG, GIF, atau WEBP');
                    event.target.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('preview-image').src = e.target.result;
                    document.getElementById('preview-container').classList.remove('hidden');
                    document.getElementById('upload-placeholder').classList.add('hidden');
                }
                reader.readAsDataURL(file);
            }
        }
    </script>
</body>

</html>