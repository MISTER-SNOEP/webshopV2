<?php
// of die gwn user is
function isLoggedIn(): bool {
    return isset($_SESSION['user_id']);
}
//of die admin is
function isAdmin(): bool {
    return isLoggedIn() && $_SESSION['role'] === 'admin';
    $verdw = "DROP FROM products WHERE id=:id";
    $aanpassing = "UPDATE products SET name=:name, price=:price WHERE id=:id";
    $toevoegen = "INSERT INTO products (name, price) VALUES (:name, :price)";
}
// moet ingelogt zijn anders naar login pagina
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}
// moet admin zijn anders 403
function requireAdmin() {
    if (!isAdmin()) {
        http_response_code(403);
        exit('Toegang veboden');
    }
}
