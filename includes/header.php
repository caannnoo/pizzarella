<header class="header">
    <a id="logo-link" href="index.html">
        <img class="logo" alt="Pizzarella Logo" src="img/logoheader.png" />
    </a>

    <nav class="nav">
        <ul class="nav-list" id="nav-list">
            <!-- JS füllt hier dynamisch die Links -->
        </ul>
    </nav>
</header>

<script>
    // Beispiel: "role" und "firstName" aus localStorage / SessionStorage simulieren
    // In echtem statischen Projekt ersetzt du PHP-Session durch JS-Speicher oder API
    const role = localStorage.getItem('role'); // "admin" oder null
    const firstName = localStorage.getItem('firstName'); // Name des Users
    const cartCount = localStorage.getItem('cart_count') || 0;

    const navList = document.getElementById('nav-list');
    const logoLink = document.getElementById('logo-link');

    if (role === 'admin') {
        logoLink.href = 'sites/orders.html';
        navList.innerHTML = `
            <li><a class="nav-link" href="sites/orders.html">Alle Bestellungen</a></li>
            <li><a class="nav-link" href="sites/account_admin.html">Mein Konto</a></li>
            <li><span class="greeting">Hallo, ${firstName} 👋</span></li>
            <li><a class="nav-link" href="sites/logout.html">Logout</a></li>
        `;
    } else {
        logoLink.href = 'index.html';
        if (firstName) {
            navList.innerHTML = `
                <li><a class="nav-link" href="index.html">Startseite</a></li>
                <li><a class="nav-link" href="sites/order.html">Bestellen</a></li>
                <li><a class="nav-link" href="sites/account.html">Mein Konto</a></li>
                <li><span class="greeting">Hallo, ${firstName} 👋</span></li>
                <li><a class="nav-link" href="sites/logout.html">Logout</a></li>
            `;
        } else {
            navList.innerHTML = `
                <li><a class="nav-link" href="index.html">Startseite</a></li>
                <li><a class="nav-link" href="sites/order.html">Bestellen</a></li>
                <li><a class="nav-link" href="sites/login.html">Registrieren/Anmelden</a></li>
            `;
        }
        // Warenkorb
        navList.innerHTML += `
            <div class="cart-icon">
                <a href="sites/cart.html">🛒
                    <span class="cart-count">${cartCount}</span>
                </a>
            </div>
        `;
    }
</script>