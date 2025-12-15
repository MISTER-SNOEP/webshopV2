<!-- Cart page -->
<!-- Some examples are already in database -->
<!-- ToDo - delete cart items-->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart | Mister Snoep</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Oswald:wght@300&family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Playwrite+DE+SAS:wght@100..400&display=swap');
        body {
            background-color: #f3f4f6;
            color: black;
            font-family: "Open Sans";
            margin: 0 10% 0 10%;
        }
        hr::not(#dass) {
            margin: 0 5% 1% 5%;
        }
        .cart {
            background-color: #e1e3e6;
            height: 70%;
            margin: auto;
            padding: 5px;
            border-radius: 5px;
            box-shadow: 5px 5px 10px #c1c3ca,
                        5px 5px 20px #c1c3ca;
        }
        .inside {
            color: black;
        }
        table {
            text-align: left;
        }
        img {
            width: 100px;
            height: 100px;
        }
        button {
            width: fit-content;
            border: none;
            border-radius: 15px;
            background-color: gray;
            right: auto;
            font-size: 25px;
        }

    </style>
</head>
<body>
    <header>
        <center>
        <?php include 'header.php'; ?>
        <hr>
    </header>
    <section class="cart">
        <h1 id="cartHead">Your cart</h1>
        <div class="inside">
            <?php

            function connection() {
                $conn = new mysqli("localhost", "admin", "admin", "candyshop");
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }
                return $conn;
            }

            $conn = connection();

            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_item'])) {
                $cart_id = (int)$_POST['cart_id'];
                $product_id = (int)$_POST['id'];
                $deleteStmt = $conn->prepare("DELETE FROM cart_items WHERE cart_id = ? AND id = ?");
                $deleteStmt->bind_param("ii", $cart_id, $product_id);
                $deleteStmt->execute();
                $deleteStmt->close();

                // Redirect (PRG), чтобы избежать повторного удаления при обновлении
                header("Location: cart.php");
                exit;
            }


            $user_id = 1; // Needs to be changed to dynamic user ID after login implementation!

            $cartQuery = $conn->prepare("
                SELECT cart_id 
                FROM cart 
                WHERE user_id = ?
            ");
            $cartQuery->bind_param("i", $user_id);
            $cartQuery->execute();
            $cartResult = $cartQuery->get_result();

            if ($cartResult->num_rows === 0) {
                echo "<p>Your cart is empty.</p>";
                exit;
            }

            $cart = $cartResult->fetch_assoc();
            $cart_id = $cart['cart_id'];

            $query = $conn->prepare("
                SELECT
                ci.id, 
                ci.product_id,
                ci.unit_price,
                ci.quantity,
                p.name AS product_name
                FROM cart_items ci
                JOIN products p ON ci.product_id = p.product_id
                WHERE ci.cart_id = ?

            ");
            $query->bind_param("i", $cart_id);
            $query->execute();
            $result = $query->get_result();

            echo '<table style="width:100%; border-collapse:collapse; margin:5px;">
                    <thead>
                    <tr style="border-bottom:2px solid;">
                        <th>Item</th>
                        <th style="text-align:center;">Price</th>
                        <th style="text-align:center;">Quantity</th>
                    </tr>
                    </thead>
                    <tbody>';

            while ($row = $result->fetch_assoc()) {
                echo '<tr>
                        <td>' . htmlspecialchars($row['product_name']) . '</td>
                        <td style="text-align:center;">€' . number_format($row['unit_price'], 2) . '</td>
                        <td style="text-align:center;">' . $row['quantity'] . '</td>
                        <td style="text-align:center;">
                        <form method="post" action="cart.php" style="display:inline;">
                            <input type="hidden" name="cart_id" value="' . $cart_id . '">
                            <input type="hidden" name="id" value="' . $row['id'] . '">
                            <button type="submit" name="delete_item">Delete</button>
                        </form>
                        </td>
                    </tr>';
            }

            echo '</tbody></table><hr>';

            $totalQuery = $conn->prepare("
                SELECT SUM(unit_price * quantity) AS cart_total
                FROM cart_items
                WHERE cart_id = ?
            ");
            $totalQuery->bind_param("i", $cart_id);
            $totalQuery->execute();
            $totalResult = $totalQuery->get_result();
            $total = $totalResult->fetch_assoc()['cart_total'] ?? 0;

            echo "<h3>Total: €" . number_format($total, 2) . "</h3>";
            echo '<button>Checkout</button>';
            ?>


        </div>
    </section>
</body>

</html>
