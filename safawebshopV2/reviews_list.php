<?php
$reviews_stmt = $mysqli->prepare("
    SELECT user_name, rating, comment, created_at
    FROM reviews
    WHERE product_id = ?
    ORDER BY created_at DESC
");
$reviews_stmt->bind_param("i", $product_id);
$reviews_stmt->execute();
$reviews_result = $reviews_stmt->get_result();
?>

<?php if ($reviews_result->num_rows > 0): ?>
    <?php while ($review = $reviews_result->fetch_assoc()): ?>
        <div style="border:1px solid #ddd; padding:10px; margin:10px 0; max-width:600px;">
            <strong><?php echo htmlspecialchars($review['user_name']); ?></strong>
            <span style="margin-left:10px;">
                <?php echo str_repeat('★', (int)$review['rating']); ?>
                <?php echo str_repeat('☆', 5 - (int)$review['rating']); ?>
            </span>
            <p><?php echo nl2br(htmlspecialchars($review['comment'])); ?></p>
            <small><?php echo htmlspecialchars($review['created_at']); ?></small>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <p>Er zijn nog geen reviews voor dit product.</p>
<?php endif; ?>
