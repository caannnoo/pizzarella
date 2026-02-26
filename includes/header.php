<?php
if (session_status() === PHP_SESSION_NONE)
    session_start();
?>

<header class="header">
    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
        <a href="/sites/orders.php">
            <img class="logo" alt="Pizzarella Logo" src="/img/logoheader.png" />
        </a>
    <?php else: ?>
        <a href="/index.php">
            <img class="logo" alt="Pizzarella Logo" src="/img/logoheader.png" />
        </a>
    <?php endif; ?>

    <nav class="nav">
        <ul class="nav-list">
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <!-- Admin-Menü -->
                <li><a class="nav-link" href="/sites/orders.php">Alle Bestellungen</a></li>
                <li><a class="nav-link" href="/sites/account_admin.php">Mein Konto</a></li>
                <li><span class="greeting">Hallo, <?= htmlspecialchars($_SESSION['firstName']); ?> 👋</span></li>
                <li><a class="nav-link" href="/sites/logout.php">Logout</a></li>
            <?php else: ?>
                <!-- Normaler Kunde/Gast -->
                <li><a class="nav-link" href="/index.php">Startseite</a></li>
                <li><a class="nav-link" href="/sites/order.php">Bestellen</a></li>
                <?php if (isset($_SESSION['firstName'])): ?>
                    <li><a class="nav-link" href="/sites/account.php">Mein Konto</a></li>
                    <li><span class="greeting">Hallo, <?= htmlspecialchars($_SESSION['firstName']); ?> 👋</span></li>
                    <li><a class="nav-link" href="/sites/logout.php">Logout</a></li>
                <?php else: ?>
                    <li><a class="nav-link" href="/sites/login.php">Registrieren/Anmelden</a></li>
                <?php endif; ?>
                <div class="cart-icon">
                    <a href="/sites/cart.php">🛒
                        <span class="cart-count"><?= $_SESSION['cart_count'] ?? 0 ?></span>
                    </a>
                </div>
            <?php endif; ?>
        </ul>
    </nav>
</header>
