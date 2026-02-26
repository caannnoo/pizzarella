<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['index'])) {
    $index = (int)$_POST['index'];

    if (isset($_SESSION['cart'][$index])) {
        // Position löschem
        unset($_SESSION['cart'][$index]);
        $_SESSION['cart'] = array_values($_SESSION['cart']);
    }

    // Zähler aktualisieren
    $_SESSION['cart_count'] = count($_SESSION['cart']);
}

// Zum Warenkorb
header("Location: cart.php");
exit;
