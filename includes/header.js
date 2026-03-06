function initHeader() {
  const role = localStorage.getItem("role");
  const firstName = localStorage.getItem("firstName");

  // Warenkorb laden
  let cart = JSON.parse(localStorage.getItem("cart")) || [];
  let cartCount = cart.reduce((sum, item) => sum + item.quantity, 0);

  const navList = document.getElementById("nav-list");
  const logoLink = document.getElementById("logo-link");

  if (!navList || !logoLink) return;

  let navHTML = "";

  // === ADMIN ===
  if (role === "admin") {
    logoLink.href = "/sites/orders.html";
    navHTML += `
      <li><a class="nav-link" href="/sites/orders.html">Alle Bestellungen</a></li>
      <li><a class="nav-link" href="/sites/account_admin.html">Mein Konto</a></li>
      <li><span class="greeting">Hallo, ${firstName || "Admin"} 👋</span></li>
      <li><a class="nav-link" href="/sites/logout.html">Logout</a></li>
    `;
  }
  // === USER ===
  else {
    logoLink.href = "/index.html";
    navHTML += `
      <li><a class="nav-link" href="/index.html">Startseite</a></li>
      <li><a class="nav-link" href="/sites/order.html">Bestellen</a></li>
    `;

    if (firstName) {
      navHTML += `
      
        <li><a class="nav-link" href="/sites/account.html">Mein Konto</a></li>
        <li><span class="greeting">Hallo, ${firstName} 👋</span></li>
        <li><a class="nav-link" href="/sites/logout.html">Logout</a></li>
      `;
    } else {
      navHTML += `
        <li><a class="nav-link" href="/sites/login.html">Registrieren / Anmelden</a></li>
      `;
    }

    // Warenkorb Icon
    navHTML += `
      <li class="cart-icon">
        <a class="nav-link" href="/sites/cart.html">
          🛒 <span class="cart-count">${cartCount}</span>
        </a>
      </li>
    `;
  }

  navList.innerHTML = navHTML;
}

// 🔥 Diese Funktion sorgt dafür, dass der Counter sofort aktualisiert wird
window.updateCartCounter = function () {
  let cart = JSON.parse(localStorage.getItem("cart")) || [];
  let total = cart.reduce((sum, item) => sum + item.quantity, 0);

  const counter = document.querySelector(".cart-count");
  if (counter) {
    counter.textContent = total;
  }
};

// direkt aufrufen, sobald header.js geladen wird
initHeader();
updateCartCounter();
