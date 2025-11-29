<?php
require_once __DIR__ . '/../config/connection.php';
$database = new Database();
$conn = $database->getConnection();

// Ambil semua produk
function get_products_paginated($limit, $offset)
{
    global $conn;
    $sql = "SELECT * FROM products LIMIT :limit OFFSET :offset";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $products = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $products[] = $row;
    }
    return $products;
}

function get_products_total()
{
    global $conn;
    $sql = "SELECT COUNT(*) as total FROM products";
    $stmt = $conn->query($sql);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['total'];
}

function get_best_seller_products($limit = 4)
{
    global $conn;
    $sql = "SELECT * FROM products ORDER BY rating DESC LIMIT :limit";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();

    $products = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $products[] = $row;
    }
    return $products;
}
// function get_product_by_id($id)
// {
//     global $conn;
//     $sql = "SELECT * FROM products WHERE id = :id";
//     $stmt = $conn->prepare($sql);
//     $stmt->bindParam(':id', $id, PDO::PARAM_INT);
//     $stmt->execute();

//     return $stmt->fetch(PDO::FETCH_ASSOC);
// }


function get_store_reviews($limit = null)
{
    global $conn;
    $sql = "SELECT * FROM reviews ORDER BY id DESC";
    if ($limit) {
        $sql .= " LIMIT :limit";
    }
    $stmt = $conn->prepare($sql);
    if ($limit) {
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    }
    $stmt->execute();

    $reviews = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $reviews[] = $row;
    }
    return $reviews;
}

function create_store_review($nama, $rating, $deskripsi)
{
    global $conn;
    $sql = "INSERT INTO reviews (nama, rating, deskripsi) VALUES (:nama, :rating, :deskripsi)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':nama', $nama);
    $stmt->bindParam(':rating', $rating, PDO::PARAM_INT);
    $stmt->bindParam(':deskripsi', $deskripsi);
    return $stmt->execute();
}


// Tambah produk ke keranjang
function get_session_id()
{
    if (!isset($_SESSION));
    return session_id();
}

// Ambil atau buat keranjang berdasarkan session ID
function get_or_create_cart_by_session($session_id = null)
{
    global $conn;
    if (!$session_id) $session_id = get_session_id();

    $sql = "SELECT id FROM carts WHERE session_id = :sid LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':sid', $session_id);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) return (int)$row['id'];

    $sql = "INSERT INTO carts (session_id) VALUES (:sid)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':sid', $session_id);
    $stmt->execute();
    return (int)$conn->lastInsertId();
}

// Tambah produk ke keranjang
function add_to_cart($product_id, $quantity = 1)
{
    global $conn;
    $session_id = get_session_id();
    $cart_id = get_or_create_cart_by_session($session_id);

    // Ambil harga produk
    $sql = "SELECT harga FROM products WHERE id = :pid LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':pid', $product_id, PDO::PARAM_INT);
    $stmt->execute();
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        error_log("Produk tidak ditemukan: ID $product_id");
        return false;
    }

    $price = (int)$product['harga'];

    // Cek apakah produk sudah ada di keranjang
    $sql = "SELECT id, quantity FROM cart_items WHERE cart_id = :cart_id AND product_id = :pid LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':cart_id', $cart_id, PDO::PARAM_INT);
    $stmt->bindParam(':pid', $product_id, PDO::PARAM_INT);
    $stmt->execute();
    $item = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($item) {
        // Jika sudah ada, update jumlahnya
        $new_quantity = $item['quantity'] + $quantity;
        $sql = "UPDATE cart_items SET quantity = :quantity WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':quantity', $new_quantity, PDO::PARAM_INT);
        $stmt->bindParam(':id', $item['id'], PDO::PARAM_INT);
        $result = $stmt->execute();
        error_log("Update keranjang: " . ($result ? "Berhasil" : "Gagal"));
        return $result;
    } else {
        // Jika belum ada, tambahkan item baru
        $sql = "INSERT INTO cart_items (cart_id, product_id, quantity, price_snapshot) VALUES (:cart_id, :pid, :quantity, :price)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':cart_id', $cart_id, PDO::PARAM_INT);
        $stmt->bindParam(':pid', $product_id, PDO::PARAM_INT);
        $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
        $stmt->bindParam(':price', $price, PDO::PARAM_INT);
        $result = $stmt->execute();
        error_log("Tambah ke keranjang: " . ($result ? "Berhasil" : "Gagal"));
        return $result;
    }
}

// Ambil isi keranjang
function get_cart_items()
{
    global $conn;
    $session_id = get_session_id();
    $cart_id = get_or_create_cart_by_session($session_id);

    $sql = "SELECT ci.id as cart_item_id, ci.product_id, ci.quantity, ci.price_snapshot, p.nama, p.image
            FROM cart_items ci
            JOIN products p ON ci.product_id = p.id
            WHERE ci.cart_id = :cart_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':cart_id', $cart_id, PDO::PARAM_INT);
    $stmt->execute();
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $items ?: []; // Kembalikan array kosong jika tidak ada item
}

function update_cart_item($cart_item_id, $quantity)
{
    global $conn;

    // Pastikan jumlah minimal adalah 1
    $quantity = max(1, intval($quantity));

    // Query untuk memperbarui jumlah item di keranjang
    $sql = "UPDATE cart_items SET quantity = :quantity WHERE id = :cart_item_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
    $stmt->bindParam(':cart_item_id', $cart_item_id, PDO::PARAM_INT);

    return $stmt->execute();
}

// Hapus item dari keranjang
function remove_from_cart($cart_item_id)
{
    global $conn;
    $sql = "DELETE FROM cart_items WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $cart_item_id, PDO::PARAM_INT);
    return $stmt->execute();
}

// Kosongkan keranjang
function clear_cart()
{
    global $conn;
    $session_id = get_session_id();
    $cart_id = get_or_create_cart_by_session($session_id);

    $sql = "DELETE FROM cart_items WHERE cart_id = :cart_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':cart_id', $cart_id, PDO::PARAM_INT);
    return $stmt->execute();
}

// ==================== ORDER FUNCTIONS ====================

// Generate nomor order unik
function generate_order_number()
{
    $prefix = 'GMC';
    $date = date('Ymd');
    $random = strtoupper(substr(md5(uniqid()), 0, 6));
    return $prefix . $date . $random;
}

// Buat order baru (tanpa ongkir)
function create_order($data)
{
    global $conn;
    $session_id = get_session_id();
    $order_number = generate_order_number();

    // Ambil item dari keranjang
    $cart_items = get_cart_items();

    if (empty($cart_items)) {
        return false;
    }

    // Hitung total
    $total = 0;
    foreach ($cart_items as $item) {
        $total += $item['quantity'] * $item['price_snapshot'];
    }

    try {
        $conn->beginTransaction();

        // Insert order
        $sql = "INSERT INTO orders (order_number, session_id, nama_lengkap, email, no_telepon, alamat, kota, kode_pos, catatan, metode_pembayaran, total) 
                VALUES (:order_number, :session_id, :nama_lengkap, :email, :no_telepon, :alamat, :kota, :kode_pos, :catatan, :metode_pembayaran, :total)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':order_number', $order_number);
        $stmt->bindParam(':session_id', $session_id);
        $stmt->bindParam(':nama_lengkap', $data['nama_lengkap']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':no_telepon', $data['no_telepon']);
        $stmt->bindParam(':alamat', $data['alamat']);
        $stmt->bindParam(':kota', $data['kota']);
        $stmt->bindParam(':kode_pos', $data['kode_pos']);
        $stmt->bindParam(':catatan', $data['catatan']);
        $stmt->bindParam(':metode_pembayaran', $data['metode_pembayaran']);
        $stmt->bindParam(':total', $total, PDO::PARAM_INT);
        $stmt->execute();

        $order_id = $conn->lastInsertId();

        // Insert order items
        $sql = "INSERT INTO order_items (order_id, product_id, product_name, product_image, quantity, price, subtotal) 
                VALUES (:order_id, :product_id, :product_name, :product_image, :quantity, :price, :subtotal)";
        $stmt = $conn->prepare($sql);

        foreach ($cart_items as $item) {
            $item_subtotal = $item['quantity'] * $item['price_snapshot'];
            $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
            $stmt->bindParam(':product_id', $item['product_id'], PDO::PARAM_INT);
            $stmt->bindParam(':product_name', $item['nama']);
            $stmt->bindParam(':product_image', $item['image']);
            $stmt->bindParam(':quantity', $item['quantity'], PDO::PARAM_INT);
            $stmt->bindParam(':price', $item['price_snapshot'], PDO::PARAM_INT);
            $stmt->bindParam(':subtotal', $item_subtotal, PDO::PARAM_INT);
            $stmt->execute();
        }

        // Kosongkan keranjang setelah order berhasil
        clear_cart();

        $conn->commit();

        return $order_number;
    } catch (Exception $e) {
        $conn->rollBack();
        error_log("Error creating order: " . $e->getMessage());
        return false;
    }
}

// Ambil order berdasarkan order number
function get_order_by_number($order_number)
{
    global $conn;
    $sql = "SELECT * FROM orders WHERE order_number = :order_number LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':order_number', $order_number);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Ambil item order berdasarkan order ID
function get_order_items($order_id)
{
    global $conn;
    $sql = "SELECT * FROM order_items WHERE order_id = :order_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Hitung total harga keranjang
function get_cart_total()
{
    $items = get_cart_items();
    $total = 0;
    foreach ($items as $item) {
        $total += $item['quantity'] * $item['price_snapshot'];
    }
    return $total;
}

// Hitung jumlah item di keranjang
function get_cart_item_count()
{
    $items = get_cart_items();
    $count = 0;
    foreach ($items as $item) {
        $count += (int)$item['quantity'];
    }
    return $count;
}
