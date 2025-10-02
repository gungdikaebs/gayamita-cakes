<?php
require_once 'method/function.php'; // pastikan path benar

if (isset($_POST['submit_review'])) {
    $nama = trim($_POST['nama']);
    $rating = intval($_POST['rating']);
    $deskripsi = trim($_POST['deskripsi']);

    if ($nama && $rating && $deskripsi) {
        if (create_store_review($nama, $rating, $deskripsi)) {
            $review_msg = "Review berhasil ditambahkan!";
        } else {
            $review_msg = "Gagal menambahkan review.";
        }
    } else {
        $review_msg = "Semua field wajib diisi!";
    }
}
?>

<main>
    <section class="py-20 bg-gradient-to-br from-pink-100 via-yellow-50 to-pink-200 min-h-[60vh]">
        <div class="mx-auto max-w-3xl px-4 sm:px-8 lg:px-12 py-12 rounded-xl shadow-lg bg-white/80 backdrop-blur-md border border-pink-200">
            <a href="index.php" class="inline-block mb-6 text-pink-600 hover:text-pink-800 font-semibold transition ">← Kembali ke Menu</a>
            <h2 class="text-3xl font-extrabold mb-8 text-center text-pink-700 drop-shadow">Ulasan Anda</h2>
            <form method="POST" action="" class="space-y-6">
                <div>
                    <label for="nama" class="block font-semibold mb-2 text-pink-700">Nama</label>
                    <input type="text" name="nama" id="nama" required class="w-full border border-pink-300 px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-400 transition" placeholder="Nama Anda">
                </div>
                <div>
                    <label for="rating" class="block font-semibold mb-2 text-pink-700">Rating</label>
                    <select name="rating" id="rating" required class="w-full border border-pink-300 px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-400 transition">
                        <option value="">Pilih rating</option>
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <option value="<?= $i; ?>"><?= str_repeat('★', $i); ?> <?= $i; ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div>
                    <label for="deskripsi" class="block font-semibold mb-2 text-pink-700">Deskripsi</label>
                    <textarea name="deskripsi" id="deskripsi" rows="3" required class="w-full border border-pink-300 px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-400 transition" placeholder="Tulis ulasan Anda..."></textarea>
                </div>
                <button type="submit" name="submit_review" class="w-full bg-pink-500 hover:bg-pink-600 text-white px-6 py-3 rounded-lg font-bold shadow transition">Kirim Review</button>
            </form>
            <?php
            if (isset($review_msg)) {
                echo "<div class='mt-6 text-center font-semibold text-pink-700 bg-pink-100 border border-pink-300 rounded-lg py-3 px-4 shadow'>$review_msg</div>";
            }
            ?>
        </div>
    </section>
</main>