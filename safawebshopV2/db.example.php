<?php
// DATABASE WITH EXAMPLES WILL BE ALSO PUSHED TO GITHUB
$servername = "localhost";
$username = "admin";
$password = "admin";
$dbname = "candyshop";

$mysqli = new mysqli($servername, $username, $password, $dbname);

if ($mysqli->connect_error) {
    die("Database connection failed");
}

$mysqli->set_charset("utf8mb4");