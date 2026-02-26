<?php
session_start();
include '../includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    //  Warenkorb initialisieren
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    
    $pizzaName = $_POST['pizza_name'] ?? '';
    $sizeName = $_POST['size'] ?? '';
    $selectedToppings = $_POST['toppings'] ?? [];
    $note = $_POST['note'] ?? '';
    $quantity = 1;

    // Pizzadaten aus Datenbank holen
    $stmtPizza = $conn->prepare("SELECT id, base_price, img FROM pizzas WHERE name=?");
    $stmtPizza->bind_param("s", $pizzaName);
    $stmtPizza->execute();
    $resultPizza = $stmtPizza->get_result();
    $pizza = $resultPizza->fetch_assoc();
    $pizzaId = $pizza['id'];
    $basePrice = (float)($pizza['base_price'] ?? 0);
    $pizzaImg = $pizza['img'] ?? 'default.jpg';

    // Größe aus Datenbank holen
    $stmtSize = $conn->prepare("SELECT id, price FROM sizes WHERE name=?");
    $stmtSize->bind_param("s", $sizeName);
    $stmtSize->execute();
    $resultSize = $stmtSize->get_result();
    $size = $resultSize->fetch_assoc();
    $sizeId = $size['id'];
    $sizePrice = (float)($size['price'] ?? 0);

    // Toppings-Preis berechnen
    $toppingPrice = 0;
    $toppingIds = [];
    foreach ($selectedToppings as $topName) {
        $stmtTop = $conn->prepare("SELECT id, price FROM toppings WHERE name=?");
        $stmtTop->bind_param("s", $topName);
        $stmtTop->execute();
        $resultTop = $stmtTop->get_result();
        $top = $resultTop->fetch_assoc();
        if ($top) {
            $toppingPrice += (float)$top['price'];
            $toppingIds[] = $top['id'];
        }
    }

    $totalPrice = ($basePrice + $sizePrice + $toppingPrice) * $quantity;

    // Gleiche Pizza? Wenn ja, dann Quantity erhöhen
    $found = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['pizza_id'] === $pizzaId &&
            $item['size_id'] === $sizeId &&
            $item['toppings'] === $toppingIds &&
            $item['note'] === $note
        ) {
            $item['quantity'] += $quantity;
            $found = true;
            break;
        }
    }

    if (!$found) {
        $_SESSION['cart'][] = [
            'pizza_id' => $pizzaId,
            'pizza_name' => $pizzaName,
            'size_id' => $sizeId,
            'size_name' => $sizeName,
            'toppings' => $toppingIds, 
            'note' => $note,
            'quantity' => $quantity,
            'unit_price' => $totalPrice,
            'img' => $pizzaImg
        ];
    }

    // Zähler erhöhen
    $_SESSION['cart_count'] = array_sum(array_column($_SESSION['cart'], 'quantity'));

    header("Location: cart.php");
    exit;
}
?>
