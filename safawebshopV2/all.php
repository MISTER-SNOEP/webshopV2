<!-- All products page -->
<!-- Displays all products with link to detailed product page --> 
<?php
require 'db.example.php';

$sql = "
SELECT
    p.product_id,
    p.name,
    p.price,
    AVG(r.rating) AS avg_rating,
    COUNT(r.review_id) AS reviews_count
FROM products p
LEFT JOIN reviews r ON r.product_id = p.product_id
GROUP BY p.product_id, p.name, p.price
ORDER BY p.product_id DESC
";

$result = $mysqli->query($sql);
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>all | Mister Snoep</title>
</head> 
<body>



<header>
    <center>
    <?php include 'header.php'; ?>
    </center>
    <hr>
</header>

<main class="page">
  <h1>Catalog</h1>

  <section class="catalog-grid">
    
    <?php while ($row = $result->fetch_assoc()): ?>

      <?php // lets make it 4star if no rewiews
        $id = (int)$row['product_id'];
        $name = $row['name'];
        $price = (float)$row['price'];

        if ((int)$row['reviews_count'] > 0) {
          $stars = (int)round($row['avg_rating']);
        } else {
          $stars = 4;
        }


        if ($stars < 1) $stars = 1;
        if ($stars > 5) $stars = 5;


        $img = "images/products/" . $id . ".jpg";
      ?>

      <div class="product-card" onclick="window.location='product.php?id=<?php echo $id; ?>'">
        <div class="product-imgwrap">
          <img src="<?php echo $img; ?>"
               alt="<?php echo htmlspecialchars($name); ?>"
               onerror="this.src='webshopV2/savawebshopV2/images/products/placeholder.jpg';">
        </div>

        <div class="product-info">
          <div class="product-name"><?php echo htmlspecialchars($name); ?></div>

          <div class="product-meta">
            <span>Candies</span>
            <span class="product-price">€<?php echo number_format($price, 2); ?></span>
          </div>

          <div class="product-rating">
            <?php for ($i = 1; $i <= 5; $i++): ?>
              <span class="star <?php if ($i <= $stars) echo 'filled'; ?>">★</span>
            <?php endfor; ?>
          </div>
        </div>

        <form class="add-to-cart" method="post" action="cart.php" onclick="event.stopPropagation();">
          <input type="hidden" name="product_id" value="<?php echo $id; ?>">
          <button class="add-btn" type="submit" name="add_to_cart">To cart</button>
        </form>
      </div>

        <?php endwhile; ?>
    </section>
</main>
</body>
</html>