<?php
session_start();
include '../includes/db_connect.php';

$cartItems = $_SESSION['cart'] ?? [];
if (empty($cartItems)) {
    header("Location: cart.php");
    exit;
}

// User-Daten holen
$userId = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT firstName, lastName, street, zip, city FROM users WHERE id=?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$userData = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Pizza-Bild
function getPizzaImage($name) {
    $basePath = "../img/pizzas/";
    if (file_exists($basePath.$name.".png")) return $basePath.$name.".png";
    if (file_exists($basePath.$name.".jpg")) return $basePath.$name.".jpg";
    return $basePath."default.png";
}

// Topping
function getToppingNames($toppingIds, $conn) {
    $names = [];
    foreach ($toppingIds as $id) {
        $res = $conn->query("SELECT name FROM toppings WHERE id=$id");
        if ($row = $res->fetch_assoc()) $names[] = $row['name'];
    }
    return implode(', ', $names);
}

// Gesamtpreis
$totalPrice = 0;
foreach ($cartItems as $item) {
    $totalPrice += ($item['unit_price'] ?? 0) * ($item['quantity'] ?? 1);
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Bestellübersicht</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/general.css">
</head>
<body>
<?php include '../includes/header.php'; ?>

<div class="review-container">
    <h1>Bestellübersicht</h1>
    <form method="post" action="order_complete.php">
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
                    <td><img src="<?= getPizzaImage($item['pizza_name']) ?>" width="80" alt="<?= htmlspecialchars($item['pizza_name']) ?>"></td>
                    <td><?= htmlspecialchars($item['pizza_name']) ?></td>
                    <td><?= htmlspecialchars($item['size_name']) ?></td>
                    <td><?= htmlspecialchars(getToppingNames($item['toppings'], $conn)) ?></td>
                    <td><?= htmlspecialchars($item['note']) ?></td>
                    <td><?= htmlspecialchars($item['quantity']) ?></td>
                    <td>€<?= number_format($lineTotal,2) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="6" style="text-align:right;font-weight:bold;">Gesamt:</td>
                    <td>€<?= number_format($totalPrice,2) ?></td>
                </tr>
            </tfoot>
        </table>

        <div class="delivery-address">
            <h2>Lieferadresse</h2>
            <p><strong>Name:</strong> <?= htmlspecialchars($userData['firstName']." ".$userData['lastName']) ?><br>
               <strong>Straße:</strong> <?= htmlspecialchars($userData['street']) ?><br>
               <strong>PLZ & Ort:</strong> <?= htmlspecialchars($userData['zip']." ".$userData['city']) ?></p>
        </div>

        <button type="submit" class="checkout-btn">Bestellung abschicken</button>
    </form>
</div>

<?php include '../includes/footer.php'; ?>
</body>
</html>
