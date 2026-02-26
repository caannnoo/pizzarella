<?php
include '../includes/db_connect.php';
$message = "";
$messageType = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $street = $_POST['street'];
    $zip = $_POST['zip'];
    $city = $_POST['city'];
    $password = $_POST['password'];
    $passwordRepeat = $_POST['passwordRepeat'];

    // Umlaute prüfen
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || preg_match('/[äöüÄÖÜ]/', $email)) {
        $message = "Die E-Mail-Adresse ist ungültig oder enthält nicht erlaubte Zeichen (ä, ö, ü)!";
        $messageType = "error";
    } elseif ($password !== $passwordRepeat) {
        $message = "Die Passwörter stimmen nicht überein!";
        $messageType = "error";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (firstName, lastName, email, street, zip, city, password, role) VALUES (?, ?, ?, ?, ?, ?, ?, 'guest')");
        $stmt->bind_param("sssssss", $firstName, $lastName, $email, $street, $zip, $city, $hashed_password);

        if ($stmt->execute()) {
            $message = "Kundenkonto erfolgreich erstellt!";
            $messageType = "success"; 
        } else {
            if ($conn->errno == 1062) {
                $message = "Diese E-Mail-Adresse ist bereits registriert!";
            } else {
                $message = "Es ist ein Fehler aufgetreten. Bitte versuche es erneut.";
            }
            $messageType = "error"; 
        }
    }
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" href="img/favicon.png" />
    <link rel="stylesheet" href="../css/general.css" />
    <link rel="stylesheet" href="../css/style.css" />
    <title>Pizzarella</title>
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <main>
      <section class="section-register">
        <header>Als Gast registrieren</header>
        <div class="register">
          <form class="register-form" method="POST" action="register.php">
            <a class="btn-form-login" href="../sites/login.php">Zum Login</a>

            <div class="input-box-registerguest">
              <label class="firstname" for="firstname">Vorname</label>
              <input type="text" name="firstName" required />
            </div>
            <div class="input-box-registerguest">
              <label class="lastname" for="lastname">Nachname</label>
              <input type="text" name="lastName" required />
            </div>
            <div class="input-box-registerguest">
              <label class="street" for="street">Straße & Hausnummer</label>
              <input type="text" name="street" required />
            </div>
            <div class="input-box-registerguest">
              <label class="zip" for="zip">Postleitzahl</label>
              <input type="text" name="zip" required />
            </div>
            <div class="input-box-registerguest">
              <label class="city" for="city">Ort</label>
              <input type="text" name="city" required />
            </div>
            <div class="input-box-registerguest">
              <label class="email" for="email">E-Mail-Adresse</label>
              <input type="text" name="email" required />
            </div>
            <div class="input-box-registerguest">
              <label class="password" for="password">Passwort</label>
              <input type="password" name="password" required />
            </div>
            <div class="input-box-registerguest">
              <label class="password" for="passwordrepeat">Passwort wiederholen</label>
              <input type="password" name="passwordRepeat" required />
            </div>

            <button class="btn-form" type="submit">Registrieren</button>

            <?php if(!empty($message)): ?>
                <p class="message_registerguest <?= $messageType ?>"><?= $message ?></p>
            <?php endif; ?>
          </form>
