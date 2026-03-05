document.addEventListener("DOMContentLoaded", () => {
  let cart = JSON.parse(localStorage.getItem("cart")) || [];
  let currentUser = JSON.parse(localStorage.getItem("currentUser"));
  let orderContent = document.getElementById("order-content");

  if (!currentUser) {
    orderContent.innerHTML = "<p>Bitte zuerst einloggen.</p>";
    return;
  }

  if (cart.length === 0) {
    orderContent.innerHTML = "<p>Dein Warenkorb ist leer.</p>";
    return;
  }

  let totalPrice = 0;

  cart.forEach((item) => {
    totalPrice += item.unit_price * item.quantity;
  });

  // Bestellung speichern
  let orders = JSON.parse(localStorage.getItem("orders")) || [];

  let newOrder = {
    id: Date.now(),
    user: currentUser,
    items: cart,
    total: totalPrice,
    date: new Date().toLocaleString(),
  };

  orders.push(newOrder);

  localStorage.setItem("orders", JSON.stringify(orders));

  // HTML erstellen
  let tableHTML = `
<table class="cart-table">

<thead>
<tr>
<th>Bild</th>
<th>Pizza</th>
<th>Größe</th>
<th>Toppings</th>
<th>Sonderwünsche</th>
<th>Menge</th>
<th>Preis</th>
</tr>
</thead>

<tbody>
`;

  cart.forEach((item) => {
    let lineTotal = item.unit_price * item.quantity;

    tableHTML += `
<tr>

<td>
<img src="${item.img}" width="80">
</td>

<td>${item.pizza_name}</td>

<td>${item.size_name}</td>

<td>${item.toppings.join(", ")}</td>

<td>${item.note}</td>

<td>${item.quantity}</td>

<td>€${lineTotal.toFixed(2)}</td>

</tr>
`;
  });

  tableHTML += `
</tbody>

<tfoot>
<tr>
<td colspan="6" style="text-align:right;font-weight:bold;">Gesamt:</td>
<td>€${totalPrice.toFixed(2)}</td>
</tr>
</tfoot>

</table>

<h2>Lieferadresse</h2>

<p>
<strong>Name:</strong> ${currentUser.firstName} ${currentUser.lastName}<br>
<strong>Straße:</strong> ${currentUser.street}<br>
<strong>PLZ & Ort:</strong> ${currentUser.zip} ${currentUser.city}
</p>
`;

  orderContent.innerHTML = tableHTML;

  // Warenkorb leeren
  localStorage.removeItem("cart");

  // Counter im Header aktualisieren
  if (typeof updateCartCounter === "function") {
    updateCartCounter();
  }
});
