// Header laden
fetch("../includes/header.html")
  .then((res) => res.text())
  .then((data) => {
    document.getElementById("header").innerHTML = data;

    const script = document.createElement("script");
    script.src = "../includes/header.js";
    document.body.appendChild(script);
  });

// Footer laden
fetch("../includes/footer.html")
  .then((res) => res.text())
  .then((data) => (document.getElementById("footer").innerHTML = data));

// Warenkorb laden
let cart = JSON.parse(localStorage.getItem("cart")) || [];
let currentUser = JSON.parse(localStorage.getItem("currentUser"));

if (cart.length === 0) {
  alert("Warenkorb ist leer!");
  window.location.href = "cart.html";
}

if (!currentUser) {
  alert("Bitte zuerst einloggen!");
  window.location.href = "login.html";
}

const orderItems = document.getElementById("order-items");
const totalPriceEl = document.getElementById("total-price");

let total = 0;

// Bestellung anzeigen
cart.forEach((item) => {
  const lineTotal = item.unit_price * item.quantity;
  total += lineTotal;

  const row = document.createElement("tr");

  row.innerHTML = `

<td>
<img src="${item.img}" width="80">
</td>

<td>${item.pizza_name}</td>

<td>${item.size_name}</td>

<td>${item.toppings.join(", ")}</td>

<td>${item.note}</td>

<td>${item.quantity}</td>

<td>€${lineTotal.toFixed(2)}</td>

`;

  orderItems.appendChild(row);
});

totalPriceEl.textContent = "€" + total.toFixed(2);

// Lieferadresse anzeigen
document.getElementById("delivery-address").innerHTML = `

<strong>Name:</strong> ${currentUser.firstName} ${currentUser.lastName}<br>
<strong>Straße:</strong> ${currentUser.street}<br>
<strong>PLZ & Ort:</strong> ${currentUser.zip} ${currentUser.city}

`;

// Bestellung abschicken
document.getElementById("place-order").addEventListener("click", () => {
  let orders = JSON.parse(localStorage.getItem("orders")) || [];

  const newOrder = {
    id: Date.now(),
    userEmail: currentUser.email,
    items: cart,
    total: total,
    date: new Date().toLocaleString(),
  };

  orders.push(newOrder);

  localStorage.setItem("orders", JSON.stringify(orders));

  localStorage.removeItem("cart");

  alert("Bestellung erfolgreich!");

  window.location.href = "../index.html";
});
