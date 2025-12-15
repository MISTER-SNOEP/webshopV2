<?php 
session_start();
require_once "auth.php";
require_once "ifelse.php";
requireLogin();
if (isAdmin()) {
    echo "<p>Welcome, admin " . htmlspecialchars($_SESSION['username']) . " " .  verdwijderen() . "!" . "</p>";
} else {
    echo "<p>Welcome, " . htmlspecialchars($_SESSION['username']) . "!</p>";
}