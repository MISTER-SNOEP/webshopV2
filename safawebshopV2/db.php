
<?php 
function connectDB(): PDO {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "webschop1";

    $dsn = "mysql:host=$servername;dbname=$database;charset=utf8mb4";

    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

    return $pdo;
}
?>