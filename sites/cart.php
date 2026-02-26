<?php
session_start();
include '../includes/db_connect.php';

// Warenkorb
$cartItems = $_SESSION['cart'] ?? [];
$totalCartPrice = 0;

// Pizza-Bild 
function getPizzaImage($name) {
    $basePath = "../img/pizzas/";
    if (file_exists($basePath . $name . ".png")) return $basePath . $name . ".png";
    if (file_exists($basePath . $name . ".jpg")) return $basePath . $name . ".jpg";
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Warenkorb</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/general.css">
</head>
<body>
<?php include '../includes/header.php'; ?>

<div class="cart-container">
    <h1>Dein Warenkorb</h1>
    <?php if (empty($cartItems)): ?>
        <div class="empty-cart">Dein Warenkorb ist leer.</div>
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
                    <th>Löschen</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cartItems as $index => $item): 
                    $unitPrice = $item['unit_price'] ?? 0;
                    $lineTotal = $unitPrice * $item['quantity'];
                    $totalCartPrice += $lineTotal;

                    // Toppings-Namen holen
                    $toppingNames = [];
                    if (!empty($item['toppings'])) {
                        foreach ($item['toppings'] as $tId) {
                            $result = $conn->query("SELECT name FROM toppings WHERE id=$tId");
                            if ($row = $result->fetch_assoc()) {
                                $toppingNames[] = $row['name'];
                            }
                        }
                    }
                ?>
                <tr>
                    <td><img src="<?= getPizzaImage($item['pizza_name']) ?>" width="80" alt="<?= htmlspecialchars($item['pizza_name']) ?>"></td>
                    <td><?= htmlspecialchars($item['pizza_name']) ?></td>
                    <td><?= htmlspecialchars($item['size_name']) ?></td>
                    <td><?= htmlspecialchars(implode(', ', $toppingNames)) ?></td>
                    <td><?= htmlspecialchars($item['note']) ?></td>
                    <td><?= htmlspecialchars($item['quantity']) ?></td>
                    <td>€<?= number_format($lineTotal, 2) ?></td>
                    <td>
                        <form method="post" action="remove_from_cart.php">
                            <input type="hidden" name="index" value="<?= $index ?>">
                            <button type="submit">❌</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="6" style="text-align:right;font-weight:bold;">Gesamt:</td>
                    <td colspan="2">€<?= number_format($totalCartPrice, 2) ?></td>
                </tr>
            </tfoot>
        </table>

        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="review_order.php" class="checkout-btn">Weiter zur Bestellübersicht</a>
        <?php else: ?>
            <a href="login.php?redirect=review_order.php" class="checkout-btn">Zum Login, um zu bestellen</a>
        <?php endif; ?>


    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>
</body>
</html>
