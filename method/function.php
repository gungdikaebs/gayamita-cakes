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
