// login.js
document.addEventListener("DOMContentLoaded", () => {
  const loginForm = document.getElementById("login-form");
  const loginMsg = document.getElementById("login-msg");

  if (!loginForm) return;

  loginForm.addEventListener("submit", function (e) {
    e.preventDefault();

    const email = document.getElementById("email").value.trim();
    const password = document.getElementById("password").value;

    if (!email || !password) {
      loginMsg.textContent = "Bitte E-Mail und Passwort eingeben.";
      return;
    }

    // Benutzer aus LocalStorage laden
    const users = JSON.parse(localStorage.getItem("users")) || [];
    const user = users.find((u) => u.email === email);

    if (!user) {
      loginMsg.textContent = "Benutzer nicht gefunden!";
      return;
    }

    if (user.password !== password) {
      loginMsg.textContent = "Falsches Passwort!";
      return;
    }

    // Login speichern
    const role = user.role || "guest"; // default "guest", falls Role fehlt
    user.role = role; // sicherstellen, dass user-Objekt die Role hat

    localStorage.setItem("firstName", user.firstName);
    localStorage.setItem("role", user.role);
    localStorage.setItem("currentUser", JSON.stringify(user));

    // Optional: Warenkorb für neuen User initialisieren
    if (!localStorage.getItem("cart"))
      localStorage.setItem("cart", JSON.stringify([]));

    // Header Counter updaten, falls updateCartCounter() existiert
    if (typeof updateCartCounter === "function") updateCartCounter();

    // Weiterleitung
    if (user.role === "admin") {
      window.location.href = "orders.html"; // Admin-Seite
    } else {
      window.location.href = "../index.html"; // Normaler User
    }
  });
});
