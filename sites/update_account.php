<?php
session_start();
include '../includes/db_connect.php';

$userId = $_SESSION['user_id'];

// Daten holen
$firstName = trim($_POST['firstName'] ?? '');
$lastName  = trim($_POST['lastName'] ?? '');
$email     = trim($_POST['email'] ?? '');
$street    = trim($_POST['street'] ?? '');
$city      = trim($_POST['city'] ?? '');
$zip       = trim($_POST['zip'] ?? '');

// Alle Felder müssen gefüllt sein
if (empty($firstName) || empty($lastName) || empty($email) || empty($street) || empty($city) || empty($zip)) {
    $_SESSION['account_message'] = "Bitte alle Felder ausfüllen!";
    header("Location: account.php");
    exit();
}

// PLZ muss 5 Ziffern enthalten
if (!preg_match('/^\d{5}$/', $zip)) {
    $_SESSION['account_message'] = "Die PLZ muss genau 5 Ziffern enthalten.";
    header("Location: account.php");
    exit();
}

// E-Mail prüfen --> keine Umlaute oder Sonderzeichen
if (!preg_match('/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/', $email)) {
    $_SESSION['account_message'] = "Bitte eine gültige E-Mail-Adresse ohne Umlaute oder Sonderzeichen eingeben.";
    header("Location: account.php");
    exit();
}

// Ist E-Mail Adresse schon vorhanden?
$stmt = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
$stmt->bind_param("si", $email, $userId);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    $_SESSION['account_message'] = "Diese E-Mail-Adresse ist bereits vergeben.";
    $stmt->close();
    header("Location: account.php");
    exit();
}
$stmt->close();

// Daten aktualisieren
$stmt = $conn->prepare("UPDATE users SET firstName=?, lastName=?, email=?, street=?, city=?, zip=? WHERE id=?");
$stmt->bind_param("ssssssi", $firstName, $lastName, $email, $street, $city, $zip, $userId);
if ($stmt->execute()) {
    $_SESSION['account_message'] = "Daten erfolgreich aktualisiert!";
} else {
    $_SESSION['account_message'] = "Fehler beim Aktualisieren. Bitte später erneut versuchen.";
}
$stmt->close();

header("Location: account.php");
exit();
?>
