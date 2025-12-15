<!-- FUCKING HELL WITH PHP AND MYSQLI - PRODUCT PAGE WITH ADD TO CART AND REVIEWS -->
<?php

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
ini_set('display_errors', 1);
error_reporting(E_ALL);

require 'db.example.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['adding'])) {
    $product_id = (int)($_POST['product_id'] ?? 0);
    if ($product_id <= 0) {
        die("Invalid product ID");
    }

    $cart_id = 1; // Needs to be changed to dynamic cart ID after login implementation!
    $quantity = 1;

    $priceStmt = $mysqli->prepare("SELECT price FROM products WHERE product_id = ?");
    $priceStmt->bind_param("i", $product_id);
    $priceStmt->execute();
    $priceRow = $priceStmt->get_result()->fetch_assoc();
    $priceStmt->close();

    if (!$priceRow) {
        die("Product not found");
    }

    $unit_price = (float)$priceRow['price'];

    $stmt = $mysqli->prepare("
        INSERT INTO cart_items (cart_id, product_id, quantity, unit_price)
        VALUES (?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE quantity = quantity + VALUES(quantity)
    ");
    $stmt->bind_param("iiid", $cart_id, $product_id, $quantity, $unit_price);
    $stmt->execute();
    $stmt->close();

    header("Location: product.php?id=" . $product_id . "&added=1");
    exit;
}


include 'header.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('Product ID is missing.');
}
$product_id = (int) $_GET['id'];

$stmt = $mysqli->prepare("SELECT * FROM products WHERE product_id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$product) {
    die('Product not found.');
}

$errors = $_GET['errors'] ?? '';
$added = $_GET['added'] ?? '';
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($product['name']); ?> - Candy Shop</title>
</head>
<body>

<h1><?php echo htmlspecialchars($product['name']); ?></h1>
<p>Prijs: <?php echo number_format((float)$product['price'], 2); ?> €</p>

<form action="product.php?id=<?php echo $product_id; ?>" method="post">
    <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
    <button type="submit" name="adding" id="adding">Add to Cart</button>
</form>

<?php if ($added): ?>
    <div style="background:#e0ffe0; padding:10px; max-width:600px; margin-top:10px;">
        <p>Product added to cart!</p>
    </div>
<?php endif; ?>

<?php if ($errors): ?>
    <div style="background:#ffe0e0; padding:10px; max-width:600px; margin-top:10px;">
        <p><?php echo htmlspecialchars($errors); ?></p>
    </div>
<?php endif; ?>

<hr>
<h2>Reviews</h2>

<?php
include 'reviews_list.php';
?>
<hr>

<h3>Schrijf een review</h3>

<form method="post" action="reviews_add.php" style="max-width:600px;">
    <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">

    <p>
        <label>Naam</label><br>
        <input type="text" name="user_name" required style="width:100%; padding:8px;">
    </p>

    <p>
        <label>Rating</label><br>
        <select name="rating" required style="width:100%; padding:8px;">
            <option value="">Kies...</option>
            <option value="5">5 - Geweldig</option>
            <option value="4">4 - Goed</option>
            <option value="3">3 - Oké</option>
            <option value="2">2 - Matig</option>
            <option value="1">1 - Slecht</option>
        </select>
    </p>

    <p>
        <label>Comment</label><br>
        <textarea name="comment" rows="4" required style="width:100%; padding:8px;"></textarea>
    </p>

    <button type="submit" name="add_review" style="padding:10px 20px; cursor:pointer;">
        Review plaatsen
    </button>
</form>
</body>
</html>