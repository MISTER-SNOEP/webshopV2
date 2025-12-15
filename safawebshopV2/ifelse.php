<?php 
function toevoegen() {
    require_once "auth.php";
    isAdmin(); 
        if (isAdmin()===True) {
            echo "<form method='get' action=index.php>
            <button type='submit'>Product toevoegen</button>
            </form>";
        }
}
function gwnverdw(): bool{ 
    require_once "auth.php";
    require_once "db.php";
    $pdo = connectDB();
    isAdmin(); 
        if (isAdmin()===True) {
            echo "<p> Ben je zeker dat je deze product wil verdwijderen?</p>
            <div class='display-flex gap-10'>
            <a href= '?verdwijderen'><button type='button' onclick='history.back()'>Nee</button>
            </div>";
            if (isset($_GET['ver1dwijderen'])) {
                echo "<p>Product is verwijderd</p>";
                $stmt = $pdo->prepare($verdw);
        }
    }
}
function verdwijderen() {
    require_once "auth.php";
    isAdmin(); 
        if (isAdmin()===True) {
            echo "<form method='get' action=verwijderpagina.php>
            <button type='submit'>Product verwijderen</button>
            </form>";
        }
}
function aanpaas() {
    require_once "auth.php";
    require_once "db.php";
    $pdo = connectDB();
    isAdmin(); 
        if (isAdmin()===True) {
            $new_price = $_POST['price'];
            $new_name = $_POST['name'];
            echo "<form method='POST' action=verandertpagina.php>
            <label>Nieuwe naam:</label>
            <input type='text' name='name' value='name' required>
            <label>Nieuwe prijs:</label>
            <input type='number' name='price' value='price' required>
            <button type='submit'>Product aanpassen</button>
            </form>";
            $stmt = $pdo->prepare($aanpassing);
        }
}
function aanpassen() {
    require_once "auth.php";
    isAdmin(); 
        if (isAdmin()===True) {
            echo "<form method='get' action=verandertpagina.php>
            <button type='submit'>Product aanpassen</button>
            </form>";
        }

}