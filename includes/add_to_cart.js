let pizzas = [];

// === Header & Footer laden ===
fetch("../includes/header.html")
  .then((res) => res.text())
  .then((data) => {
    document.getElementById("header").innerHTML = data;

    // Header-Script anhängen (Cart-Counter)
    const script = document.createElement("script");
    script.src = "../includes/header.js";
    document.body.appendChild(script);

    script.onload = () => {
      loadPizzas();
    };
  });

fetch("../includes/footer.html")
  .then((res) => res.text())
  .then((data) => (document.getElementById("footer").innerHTML = data));

// === Pizzas laden ===
function loadPizzas() {
  Promise.all([
    fetch("../data/pizzas.json").then((r) => r.json()),
    fetch("../data/sizes.json").then((r) => r.json()),
    fetch("../data/toppings.json").then((r) => r.json()),
  ])
    .then(([pizzaData, sizesArr, toppingsArr]) => {
      pizzas = pizzaData;
      buildPizzaCards(pizzas, sizesArr, toppingsArr);
    })
    .catch((err) => console.error(err));
}

// === Pizza-Karten bauen ===
function buildPizzaCards(pizzas, sizesArr, toppingsArr) {
  const pizzaList = document.getElementById("pizza-list");

  pizzas.forEach((pizza) => {
    const sizeOptions = sizesArr
      .map(
        (s) =>
          `<option value="${s.name}" data-price="${s.price ?? 0}">${s.name} ${
            s.price > 0 ? "(+€" + s.price.toFixed(2) + ")" : ""
          }</option>`,
      )
      .join("");

    const toppingOptions = toppingsArr
      .map(
        (t) =>
          `<option value="${t.name}" data-price="${t.price ?? 0}">${t.name} (+€${t.price.toFixed(
            2,
          )})</option>`,
      )
      .join("");

    const pizzaHTML = `
      <div class="pizza-card">
        <img src="${pizza.img}" alt="${pizza.name}" class="pizza-img-order">
        <h2>${pizza.name}</h2>
        <p>${pizza.description}</p>
        <p>Basispreis: €${pizza.base_price.toFixed(2)}</p>
        <form class="pizza-order-form">
          <input type="hidden" name="pizza_name" value="${pizza.name}">
          <label>Größe:</label>
          <select name="size">${sizeOptions}</select>
          <label>Toppings:</label>
          <select name="toppings[]" multiple>${toppingOptions}</select>
          <label>Sonderwünsche:</label>
          <textarea name="note" placeholder="Sonderwünsche..."></textarea>
          <button type="submit">In den Warenkorb</button>
        </form>
      </div>
    `;

    pizzaList.innerHTML += pizzaHTML;
  });

  // Add-to-Cart initialisieren
  if (typeof initAddToCart === "function") {
    initAddToCart();
  }
}

// === Add to Cart Funktion ===
function calculatePrice(form, pizza) {
  const basePrice = parseFloat(pizza.base_price || 0);
  const sizePrice = parseFloat(
    form.querySelector("select[name='size']").selectedOptions[0]?.dataset
      .price || 0,
  );
  const toppingsPrice = Array.from(
    form.querySelector("select[name='toppings[]']").selectedOptions,
  ).reduce((sum, t) => sum + parseFloat(t.dataset.price || 0), 0);

  return basePrice + sizePrice + toppingsPrice;
}

function initAddToCart() {
  document.querySelectorAll(".pizza-order-form").forEach((form) => {
    form.addEventListener("submit", (e) => {
      e.preventDefault();

      const fd = new FormData(form);
      const pizzaName = fd.get("pizza_name");
      const pizza = pizzas.find((p) => p.name === pizzaName);
      if (!pizza) return alert("Pizza nicht gefunden!");

      const size = fd.get("size");
      const toppings = fd.getAll("toppings[]");
      const note = fd.get("note") || "";

      let cart = JSON.parse(localStorage.getItem("cart")) || [];

      // prüfen ob gleiche Pizza existiert
      let found = cart.find(
        (item) =>
          item.pizza_name === pizzaName &&
          item.size_name === size &&
          JSON.stringify(item.toppings) === JSON.stringify(toppings) &&
          item.note === note,
      );

      const unitPrice = calculatePrice(form, pizza);

      if (found) {
        found.quantity += 1;
      } else {
        cart.push({
          pizza_name: pizza.name,
          size_name: size,
          toppings,
          note,
          quantity: 1,
          img: pizza.img,
          unit_price: unitPrice,
        });
      }

      localStorage.setItem("cart", JSON.stringify(cart));

      if (typeof updateCartCounter === "function") updateCartCounter();

      alert(`${pizzaName} (${size}) wurde in den Warenkorb gelegt!`);
    });
  });
}
