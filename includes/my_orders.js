document.addEventListener("DOMContentLoaded", () => {
  const ordersContainer = document.getElementById("orders-container");

  const currentUser = JSON.parse(localStorage.getItem("currentUser"));

  if (!currentUser) {
    ordersContainer.innerHTML = "<p>Bitte zuerst einloggen.</p>";
    return;
  }

  const orders = JSON.parse(localStorage.getItem("orders")) || [];

  const userOrders = orders.filter(
    (order) => order.userEmail === currentUser.email,
  );

  if (userOrders.length === 0) {
    ordersContainer.innerHTML = "<p>Du hast noch keine Bestellungen.</p>";
    return;
  }

  userOrders.forEach((order, index) => {
    let html = `
    <div class="order-box">
      <h2>Bestellung #${index + 1}</h2>  <!-- hier index + 1 -->
      
      <table class="cart-table">
        <thead>
          <tr>
            <th>Pizza</th>
            <th>Größe</th>
            <th>Menge</th>
            <th>Preis</th>
          </tr>
        </thead>
        <tbody>
          ${order.items
            .map(
              (item) => `
            <tr>
              <td>${item.pizza_name}</td>
              <td>${item.size_name}</td>
              <td>${item.quantity}</td>
              <td>€${item.unit_price}</td>
            </tr>
          `,
            )
            .join("")}
        </tbody>
        <tfoot>
          <tr>
            <td colspan="3" style="text-align:right;font-weight:bold;">Gesamt:</td>
            <td>€${order.total}</td>
          </tr>
        </tfoot>
      </table>
    </div>
  `;

    ordersContainer.innerHTML += html;
  });
});
