// Funktion, um den Preis einer Pizza zu berechnen
function calculatePrice(form, pizza) {
  const basePrice = parseFloat(pizza.base_price);
  const sizePrice = parseFloat(
    form.querySelector("select[name='size']").selectedOptions[0].dataset
      .price || 0,
  );
  const toppingsPrice = Array.from(
    form.querySelector("select[name='toppings[]']").selectedOptions,
  ).reduce((sum, t) => sum + parseFloat(t.dataset.price), 0);

  return basePrice + sizePrice + toppingsPrice;
}

function initAddToCart() {
  document.querySelectorAll(".pizza-order-form").forEach((form) => {
    form.addEventListener("submit", (e) => {
      e.preventDefault();

      const fd = new FormData(form);
      const pizzaName = fd.get("pizza_name");
      const pizza = pizzas.find((p) => p.name === pizzaName);
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
          size_name: fd.get("size"),
          toppings: fd.getAll("toppings[]"),
          note: fd.get("note") || "",
          quantity: 1,
          img: pizza.img,
          unit_price: unitPrice, // <- hier
        });
      }

      localStorage.setItem("cart", JSON.stringify(cart));

      // Counter im Header aktualisieren
      if (typeof updateCartCounter === "function") {
        updateCartCounter();
      }

      alert(`${pizzaName} (${size}) wurde in den Warenkorb gelegt!`);
    });
  });
}
