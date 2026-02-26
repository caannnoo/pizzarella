<?php
include '../includes/db_connect.php';
$message = "";
$messageType = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
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

        $stmt = $conn->prepare("INSERT INTO users (firstName, lastName, email, password, role) VALUES (?, ?, ?, ?, 'admin')");
        $stmt->bind_param("ssss", $firstName, $lastName, $email, $hashed_password);

        if ($stmt->execute()) {
            $message = "Adminkonto erfolgreich erstellt!";
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
    <link rel="stylesheet" href="../css/general.css" />
    <link rel="stylesheet" href="../css/style.css" />
    <title>Admin Registrierung</title>
</head>
<body>
<?php include '../includes/header.php'; ?>

<main>
    <section class="section-register-admin">
        <header>Als Admin registrieren</header>
        <div class="register-admin">
            <form class="register-form-admin" method="POST" action="register_admin.php">
                <a class="btn-form-login-admin" href="../sites/login.php">Zum Login</a>

                <div class="input-box-registeradmin">
                    <label for="firstname">Vorname</label>
                    <input type="text" name="firstName" required />
                </div>
                <div class="input-box-registeradmin">
                    <label for="lastname">Nachname</label>
                    <input type="text" name="lastName" required />
                </div>
                <div class="input-box-registeradmin">
                    <label for="email">E-Mail-Adresse</label>
                    <input type="text" name="email" required />
                </div>
                <div class="input-box-registeradmin">
                    <label for="password">Passwort</label>
                    <input type="password" name="password" required />
                </div>
                <div class="input-box-registeradmin">
                    <label for="passwordrepeat">Passwort wiederholen</label>
                    <input type="password" name="passwordRepeat" required />
                </div>

                <button class="btn-form" type="submit">Registrieren</button>

                <?php if(!empty($message)): ?>
                    <p class="message_registeradmin <?= $messageType ?>"><?= $message ?></p>
                <?php endif; ?>
            </form>
        </div>
    </section>
</main>

<?php include '../includes/footer.php'; ?>
</body>
</html>
