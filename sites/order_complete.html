<?php
session_start();
include '../includes/db_connect.php';

// Warenkorb holen
$cartItems = $_SESSION['cart'] ?? [];
$totalCartPrice = 0;

// User holen
$userId = $_SESSION['user_id'] ?? null;


if (!$userId || empty($cartItems)) {
    header("Location: cart.php");
    exit;
}

function getPizzaImage($name) {
    $basePath = "../img/pizzas/";
    if (file_exists($basePath . $name . ".png")) return $basePath . $name . ".png";
    if (file_exists($basePath . $name . ".jpg")) return $basePath . $name . ".jpg";
    return $basePath . "default.png";
}

function getToppingNames($toppingIds, $conn) {
    $names = [];
    if (!empty($toppingIds)) {
        foreach ($toppingIds as $id) {
            $res = $conn->query("SELECT name FROM toppings WHERE id=$id");
            if ($row = $res->fetch_assoc()) {
                $names[] = $row['name'];
            }
        }
    }
    return implode(', ', $names);
}

// Lieferadresse aus DB holen
$deliveryAddress = [
    'firstName' => 'Unbekannt',
    'lastName' => '',
    'street' => '',
    'city' => ''
];

$stmt = $conn->prepare("SELECT firstName, lastName, street, zip, city FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $deliveryAddress = $row;
}
$stmt->close();

// Gesamtpreis berechnen
foreach ($cartItems as $item) {
    $totalCartPrice += ($item['unit_price'] ?? 0) * ($item['quantity'] ?? 1);
}


// Bestellung  speichern
$stmtOrder = $conn->prepare("INSERT INTO orders (user_id, total_price) VALUES (?, ?)");
$stmtOrder->bind_param("id", $userId, $totalCartPrice);
$stmtOrder->execute();
$orderId = $stmtOrder->insert_id;
$stmtOrder->close();

foreach ($cartItems as $item) {
    $pizzaId = $item['pizza_id'];
    $sizeId = $item['size_id'];
    $quantity = $item['quantity'];
    $unitPrice = $item['unit_price'];
    $note = $item['note'];

    $stmtItem = $conn->prepare("INSERT INTO order_items (order_id, pizza_id, size_id, note, quantity, price) VALUES (?,?,?,?,?,?)");
    $stmtItem->bind_param("iiisid", $orderId, $pizzaId, $sizeId, $note, $quantity, $unitPrice);
    $stmtItem->execute();
    $orderItemId = $stmtItem->insert_id;
    $stmtItem->close();

    if (!empty($item['toppings'])) {
        foreach ($item['toppings'] as $toppingId) {
            $stmtTop = $conn->prepare("INSERT INTO order_item_toppings (order_item_id, topping_id) VALUES (?,?)");
            $stmtTop->bind_param("ii", $orderItemId, $toppingId);
            $stmtTop->execute();
            $stmtTop->close();
        }
    }
}

// Warenkorb leeren und Counter zurücksetzen
$_SESSION['cart'] = [];
$_SESSION['cart_count'] = 0;
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Bestellung abgeschlossen</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/general.css">
</head>
<body>
<?php include '../includes/header.php'; ?>

<div class="cart-container">
    <h1>Vielen Dank für deine Bestellung!</h1>

    <?php if (empty($cartItems)): ?>
        <p>Dein Warenkorb war leer oder wurde bereits abgeschlossen.</p>
    <?php else: ?>
        <table class="cart-table">
            <thead>
                <tr>
                    <th>Bild</th>
                    <th>Pizza</th>
                    <th>Größe</th>
                    <th>Toppings</th>
                    <th>Sonderwünsche</th>
                    <th>Menge</th>
                    <th>Preis</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cartItems as $item): 
                    $lineTotal = ($item['unit_price'] ?? 0) * ($item['quantity'] ?? 1);
                ?>
                <tr>
                    <td><img src="<?= htmlspecialchars(getPizzaImage($item['pizza_name']), ENT_QUOTES, 'UTF-8') ?>" width="80" alt="<?= htmlspecialchars($item['pizza_name'], ENT_QUOTES, 'UTF-8') ?>"></td>
                    <td><?= htmlspecialchars($item['pizza_name'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($item['size_name'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars(getToppingNames($item['toppings'], $conn), ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($item['note'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($item['quantity'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td>€<?= number_format($lineTotal, 2) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="6" style="text-align:right;font-weight:bold;">Gesamt:</td>
                    <td>€<?= number_format($totalCartPrice, 2) ?></td>
                </tr>
            </tfoot>
        </table>

        <h2>Lieferadresse</h2>
        <p>
            <strong>Name: </strong><?= htmlspecialchars($deliveryAddress['firstName'] . ' ' . $deliveryAddress['lastName'], ENT_QUOTES, 'UTF-8') ?><br>
            <strong>Straße: </strong><?= htmlspecialchars($deliveryAddress['street'], ENT_QUOTES, 'UTF-8') ?><br>
            <strong>PLZ & Ort: </strong><?= htmlspecialchars($deliveryAddress['city'], ENT_QUOTES, 'UTF-8') ?>
        </p>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>
</body>
</html>
