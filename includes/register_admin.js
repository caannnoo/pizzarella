const form = document.getElementById("register-admin-form");
const msg = document.getElementById("message");

form.addEventListener("submit", (e) => {
  e.preventDefault();

  const firstName = document.getElementById("firstName").value.trim();
  const lastName = document.getElementById("lastName").value.trim();
  const email = document.getElementById("email").value.trim();
  const password = document.getElementById("password").value;
  const passwordRepeat = document.getElementById("passwordRepeat").value;

  // E-Mail prüfen
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (!emailRegex.test(email)) {
    msg.textContent = "Bitte eine gültige E-Mail-Adresse eingeben!";
    msg.className = "message error";
    return;
  }

  // Passwort prüfen
  if (password !== passwordRepeat) {
    msg.textContent = "Passwörter stimmen nicht überein!";
    msg.className = "message error";
    return;
  }

  // Alle Benutzer aus LocalStorage laden
  let users = JSON.parse(localStorage.getItem("users")) || [];

  // Prüfen, ob E-Mail schon existiert
  if (users.find((u) => u.email === email)) {
    msg.textContent = "Diese E-Mail-Adresse ist bereits registriert!";
    msg.className = "message error";
    return;
  }

  // Neuen Admin-Benutzer speichern
  const newUser = {
    firstName,
    lastName,
    email,
    password,
    role: "admin",
  };

  users.push(newUser);
  localStorage.setItem("users", JSON.stringify(users));

  // Feedback
  msg.textContent = "Admin-Konto erfolgreich erstellt!";
  msg.className = "message success";
  form.reset();
});
