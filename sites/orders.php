<?php
session_start();
include '../includes/db_connect.php';

// Nur Admins dürfen auf diese Seite
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

// Alle Bestellungen der Gäste abrufen
$sql = "SELECT o.id as order_id, o.user_id, o.total_price,
               u.firstName, u.lastName, u.email
        FROM orders o
        JOIN users u ON o.user_id = u.id
        ORDER BY o.id DESC";

$ordersResult = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Alle Bestellungen</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/general.css">
</head>
<body>
<?php include '../includes/header.php'; ?>

<div class="admin-orders-container">
    <h1>Alle Bestellungen</h1>

    <?php if ($ordersResult && $ordersResult->num_rows > 0): ?>
        <?php while($order = $ordersResult->fetch_assoc()): ?>
            <div class="order-block">
                <h2>Bestellung #<?= $order['order_id'] ?></h2>
                <p><strong>Kunde:</strong> <?= htmlspecialchars($order['firstName'] . ' ' . $order['lastName']) ?></p>
                <p><strong>E-Mail:</strong> <?= htmlspecialchars($order['email']) ?></p>
                <p><strong>Gesamtpreis:</strong> <?= number_format($order['total_price'], 2) ?> €</p>

                <table>
                    <thead>
                        <tr>
                            <th>Pizza</th>
                            <th>Größe</th>
                            <th>Anmerkung</th>
                            <th>Menge</th>
                            <th>Preis</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Alle Items der Bestellung abrufen
                        $stmt = $conn->prepare("SELECT oi.quantity, oi.price, oi.note, p.name as pizza_name, s.name as size_name
                                                FROM order_items oi
                                                JOIN pizzas p ON oi.pizza_id = p.id
                                                JOIN sizes s ON oi.size_id = s.id
                                                WHERE oi.order_id = ?");
                        $stmt->bind_param("i", $order['order_id']);
                        $stmt->execute();
                        $itemsResult = $stmt->get_result();

                        while ($item = $itemsResult->fetch_assoc()):
                        ?>
                            <tr>
                                <td><?= htmlspecialchars($item['pizza_name']) ?></td>
                                <td><?= htmlspecialchars($item['size_name']) ?></td>
                                <td><?= htmlspecialchars($item['note']) ?></td>
                                <td><?= $item['quantity'] ?></td>
                                <td><?= number_format($item['price'], 2) ?> €</td>
                            </tr>
                        <?php endwhile; 
                        $stmt->close();
                        ?>
                    </tbody>
                </table>
            </div>
            
        <?php endwhile; ?>
    <?php else: ?>
        <p>Keine Bestellungen vorhanden.</p>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>
</body>
</html>
