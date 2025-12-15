<!-- All products page -->
<!-- Displays all products with link to detailed product page --> 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>all | Mister Snoep</title>
    <style>
        .items_show {
            display: flex;
            margin: 0 10% 0 10%;
            flex-flow: row wrap;
            justify-content: space-around;
            width: 50%;
        }
        .item {
            background-color: #e1e3e6;
            height: 150px;
            width: 200px;
            margin: 10px;
            padding: 5px;
            border-radius: 5px;
            box-shadow: 5px 5px 10px #c1c3ca,
                        5px 5px 20px #c1c3ca;
        }
    </style>
</head>
<body>
    <header>
        <center>
        <?php include 'header.php'; ?>
        <hr>
    </header>
    <?php
    
    $servername = "localhost";
    $username = "admin";
    $password = "admin";
    $dbname = "candyshop";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $query = "SELECT * FROM products";
    $result = $conn->query($query);

    echo "<div class='items_show'>";

    foreach ($result as $row) {
        echo "<div class='item'>";
        echo "<h2>" . $row['name'] . "</h2>";
        echo "<p>Price: $" . $row['price'] . "</p>";
        echo "<p>Quantity: " . $row['stock'] . "</p>";
        echo "<button><a href='product.php?id=" . $row['product_id'] . "'>Info</a></button>";
        echo "</div>";
    }

    echo "</div>";

    ?>
</body>
</html>