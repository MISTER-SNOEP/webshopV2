<?php
require 'db.example.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['add_review'])) {
    die('Invalid request.');
}

$product_id = (int) ($_POST['product_id'] ?? 0);
$user_id    = 1; // In a real application, retrieve this from the session or authentication context
$user_name  = trim($_POST['user_name'] ?? '');
$rating     = (int) ($_POST['rating'] ?? 0);
$comment    = trim($_POST['comment'] ?? '');

if (
    $product_id <= 0 ||
    $user_id <= 0 ||
    $user_name === '' ||
    $comment === '' ||
    $rating < 1 ||
    $rating > 5
) {
    header("Location: product.php?id={$product_id}&errors=" . urlencode("Vul alle velden correct in."));
    exit;
}

$insert_stmt = $mysqli->prepare("
    INSERT INTO reviews (product_id, user_id, user_name, rating, comment)
    VALUES (?, ?, ?, ?, ?)
");

$insert_stmt->bind_param(
    "iisis",
    $product_id,
    $user_id,
    $user_name,
    $rating,
    $comment
);

$insert_stmt->execute();

header("Location: product.php?id=" . $product_id);
exit;
