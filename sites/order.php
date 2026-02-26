<?php
session_start();

include '../includes/db_connect.php';


// Alle Pizzen holen
$resultPizzas = $conn->query("SELECT * FROM pizzas");
$pizzas = [];
while($row = $resultPizzas->fetch_assoc()) {
    $pizzas[] = $row;
}

// Alle Größen holen
$resultSizes = $conn->query("SELECT * FROM sizes");
$sizes = [];
while ($row = $resultSizes->fetch_assoc()) {
    $sizes[$row['name']] = $row['price'];
}

// Alle Toppings holen
$resultToppings = $conn->query("SELECT * FROM toppings");
$toppings = [];
while($row = $resultToppings->fetch_assoc()) {
    $toppings[] = $row;
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Pizzen bestellen</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/general.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <div class="pizza-list">
        <?php foreach ($pizzas as $pizza): ?>
            <div class="pizza-card">
                <img src="<?= htmlspecialchars($pizza['img']) ?>" alt="<?= htmlspecialchars($pizza['name']) ?>" class="pizza-img-order">
                <h2><?= htmlspecialchars($pizza['name']) ?></h2>
                <p><?= htmlspecialchars($pizza['description']) ?></p>
                <p>Basispreis: €<?= number_format($pizza['base_price'], 2) ?></p>

                <form method="post" action="add_to_cart.php" class="pizza-order-form">
                    <input type="hidden" name="pizza_name" value="<?= htmlspecialchars($pizza['name']) ?>">

                    <!-- Größe auswählen -->
                    <label for="size">Größe:</label>
                    <select name="size" id="size">
                        <?php foreach ($sizes as $sizeName => $sizePrice): ?>
                            <option value="<?= $sizeName ?>"><?= $sizeName ?> <?php if($sizePrice>0) echo "(+€".number_format($sizePrice,2).")"; ?></option>
                        <?php endforeach; ?>
                    </select>

                    <!-- Toppings als Mehrfachauswahl -->
                    <label for="toppings">Toppings wählen (mehrere möglich):</label>
                    <select name="toppings[]" id="toppings" multiple size="5">
                        <?php foreach ($toppings as $top): ?>
                            <option value="<?= htmlspecialchars($top['name']) ?>">
                                <?= htmlspecialchars($top['name']) ?> (+€<?= number_format($top['price'], 2) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <!-- Topping-Bilder -->
                    <div class="topping-images">
                        <?php foreach ($toppings as $top): ?>
                            <img src="<?= htmlspecialchars($top['img']) ?>" alt="<?= htmlspecialchars($top['name']) ?>" width="50">
                        <?php endforeach; ?>
                    </div>

                    <!-- Sonderwünsche -->
                    <label for="note">Sonderwünsche:</label>
                    <textarea name="note" placeholder="Hier Sonderwünsche eintragen..."></textarea>

                    <button type="submit">In den Warenkorb</button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>
</html>
