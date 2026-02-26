<?php

session_start();
include '../includes/db_connect.php'; // Datenbankverbindung

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $message = "Bitte E-Mail und Passwort eingeben.";
    } else {
        // Benutzer aus Datenbank holen (Gast oder Admin)
        $stmt = $conn->prepare("SELECT id, firstName, lastName, password, role FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $firstName, $lastName, $hashed_password, $role);
            $stmt->fetch();

            if (password_verify($password, $hashed_password)) {
                
                $_SESSION['user_id']    = $id;
                $_SESSION['firstName']  = $firstName;
                $_SESSION['lastName']   = $lastName;
                $_SESSION['role']       = $role;
                $_SESSION['cart_count'] = $_SESSION['cart_count'] ?? 0;

                // Weiterleitung von Gast und Admin
                if ($role === 'admin') {
                    header("Location: /sites/orders.php"); // Admin-Bestellungen-Ansicht
                } else {
                    $redirect = $_GET['redirect'] ?? '/index.php'; // Gast weiter zur Startseite
                    header("Location: " . $redirect);
                }
                exit;

            } else {
                $message = "Falsches Passwort!";
            }
        } else {
            $message = "Benutzer nicht gefunden!";
        }

        $stmt->close();
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
    <title>Pizzarella - Login</title>
</head>
<body>
<?php include '../includes/header.php'; ?>

<main>
    <section class="section-login">
        <header>Login</header>

        <div class="login">
            <?php if(!empty($message)) { ?>
                <p class="needlogin-msg"><?= htmlspecialchars($message) ?></p>
            <?php } ?>

            <form class="login-form" method="post" action="">
                <div class="input-box-login">
                    <label for="email">E-Mail-Adresse</label>
                    <input type="text" name="email" id="email" required />
                </div>
                <div class="input-box-login">
                    <label for="password">Passwort</label>
                    <input type="password" name="password" id="password" required />
                </div>

                <button class="btn-form" type="submit">Anmelden</button>

                <p>Noch kein Konto?</p>
                <a class="btn-form" href="register.php">Als Gast registrieren</a>
                <a class="btn-form" href="register_admin.php">Als Admin registrieren</a>
            </form>
        </div>
    </section>
</main>

<?php include '../includes/footer.php'; ?>
</body>
</html>
