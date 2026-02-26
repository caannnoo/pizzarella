<?php
session_start();
include '../includes/db_connect.php';

$userId = $_SESSION['user_id'];

// Aktuelle Daten laden
$stmt = $conn->prepare("SELECT firstName, lastName, email, street, city, zip FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Mein Konto</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/general.css">
</head>
<body>

<?php include '../includes/header.php'; ?>

<div class="account-container">
    <h1>Mein Konto</h1>

    <?php if (isset($_SESSION['account_message'])): ?>
    <div class="account-message">
        <?= htmlspecialchars($_SESSION['account_message']) ?>
    </div>
        <?php unset($_SESSION['account_message']); ?>
    <?php endif; ?>


    <form class="account-form" action="update_account.php" method="post">
        <label>Vorname:</label>
        <input type="text" name="firstName" value="<?= htmlspecialchars($user['firstName']) ?>">

        <label>Nachname:</label>
        <input type="text" name="lastName" value="<?= htmlspecialchars($user['lastName']) ?>">

        <label>E-Mail:</label>
        <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>">

        <label>Adresse:</label>
        <input type="text" name="street" value="<?= htmlspecialchars($user['street']) ?>">

        <label>Stadt:</label>
        <input type="text" name="city" value="<?= htmlspecialchars($user['city']) ?>">

        <label>PLZ:</label>
        <input type="text" name="zip" value="<?= htmlspecialchars($user['zip']) ?>">

        <button type="submit">Änderungen speichern</button>
    </form>


    <h2>Meine Bestellungen</h2>
    <div class="myorders">
        <a href="/sites/my_orders.php" class="btn-order-account">Meine Bestellungen</a>
    </div>



    <h2>Passwort ändern</h2>
    <form class="account-form-pw" action="update_password.php" method="post">
      <label>Altes Passwort:</label>
      <input type="password" name="old_password" required>

      <label>Neues Passwort:</label>
      <input type="password" name="new_password" required>

      <label>Neues Passwort wiederholen:</label>
      <input type="password" name="confirm_password" required>

      <button type="submit">Passwort ändern</button>
    </form>

</div>

<?php include '../includes/footer.php'; ?>

</body>
</html>
