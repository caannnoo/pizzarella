<?php
session_start();
include '../includes/db_connect.php';
$userId = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Meine Bestellungen</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/general.css">
</head>
<body>

<?php include '../includes/header.php'; ?>

<div class="account-container">
    <h1>Meine Bestellungen</h1>

    <?php
    // Bestellungen des Users 
    $stmtOrders = $conn->prepare("SELECT id, total_price FROM orders WHERE user_id = ? ORDER BY id DESC");
    $stmtOrders->bind_param("i", $userId);
    $stmtOrders->execute();
    $resultOrders = $stmtOrders->get_result();

    if ($resultOrders->num_rows === 0) {
        echo "<p>Du hast noch keine Bestellungen.</p>";
    } else {
        while ($order = $resultOrders->fetch_assoc()) {
            echo "<div class='order-box'>";
            echo "<h2>Bestellung #{$order['id']}</h2>";

            // Items der Bestellung 
            $stmtItems = $conn->prepare("
                SELECT oi.quantity, oi.price as item_price, p.name as pizza_name, s.name as size_name
                FROM order_items oi
                JOIN pizzas p ON oi.pizza_id = p.id
                JOIN sizes s ON oi.size_id = s.id
                WHERE oi.order_id = ?
            ");
            $stmtItems->bind_param("i", $order['id']);
            $stmtItems->execute();
            $resultItems = $stmtItems->get_result();

            echo "<table class='cart-table'>";
            echo "<thead>
                    <tr>
                        <th>Pizza</th>
                        <th>Größe</th>
                        <th>Menge</th>
                        <th>Preis</th>
                    </tr>
                  </thead>
                  <tbody>";

            while ($item = $resultItems->fetch_assoc()) {
                // Toppings 
                $stmtToppings = $conn->prepare("
                    SELECT t.name 
                    FROM order_item_toppings oit
                    JOIN toppings t ON oit.topping_id = t.id
                    WHERE oit.order_item_id = (
                        SELECT id FROM order_items 
                        WHERE order_id = ? AND pizza_id = ? LIMIT 1
                    )
                ");
                $pizzaId = $item['pizza_id'] ?? 0;
                $orderId = $order['id'];

                $stmtToppings->bind_param("ii", $orderId, $pizzaId);

                $stmtToppings->execute();
                $resultToppings = $stmtToppings->get_result();
                $toppings = [];
                while ($t = $resultToppings->fetch_assoc()) {
                    $toppings[] = $t['name'];
                }

                echo "<tr>
                        <td>{$item['pizza_name']}</td>
                        <td>{$item['size_name']}</td>
                        <td>{$item['quantity']}</td>
                        <td>€" . number_format($item['item_price'], 2) . "</td>
                      </tr>";
                if (!empty($toppings)) {
                    echo "<tr><td colspan='4'>Toppings: " . implode(", ", $toppings) . "</td></tr>";
                }
            }

            echo "</tbody>";
            echo "<tfoot><tr><td colspan='3' style='text-align:right;font-weight:bold;'>Gesamt:</td>
                  <td>€" . number_format($order['total_price'], 2) . "</td></tr></tfoot>";
            echo "</table></div>";
        }
    }

    $stmtOrders->close();
    ?>
</div>

<?php include '../includes/footer.php'; ?>
</body>
</html>
