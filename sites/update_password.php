<?php
session_start();
include '../includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $userId = $_SESSION['user_id'] ?? null;
    $oldPassword = $_POST['old_password'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';


    // Stimmen Passwörter überein?
    if ($newPassword !== $confirmPassword) {
        $_SESSION['account_message'] = "Die neuen Passwörter stimmen nicht überein.";
        redirectByRole();
    }

    // Altes Passwort korrek?
    $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->bind_result($hashedPassword);
    $stmt->fetch();
    $stmt->close();

    if (!password_verify($oldPassword, $hashedPassword)) {
        $_SESSION['account_message'] = "Altes Passwort ist falsch.";
        redirectByRole();
    }

    // Neues Passwort
    $newHashed = password_hash($newPassword, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
    $stmt->bind_param("si", $newHashed, $userId);
    $stmt->execute();
    $stmt->close();

    $_SESSION['account_message'] = "✅ Passwort erfolgreich geändert!";

    redirectByRole();
}

// Unterschiedliche Weiterleitung von Admin und Guest
function redirectByRole() {
    if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
        header("Location: account_admin.php");
    } else {
        header("Location: account.php");
    }
    exit;
}
?>
