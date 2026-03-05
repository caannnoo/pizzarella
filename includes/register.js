const form = document.getElementById("register-form");
const msg = document.getElementById("message");

form.addEventListener("submit", (e) => {
  e.preventDefault();

  const firstName = document.getElementById("firstName").value.trim();
  const lastName = document.getElementById("lastName").value.trim();
  const street = document.getElementById("street").value.trim();
  const zip = document.getElementById("zip").value.trim();
  const city = document.getElementById("city").value.trim();
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

  if (password !== passwordRepeat) {
    msg.textContent = "Passwörter stimmen nicht überein!";
    msg.className = "message error";
    return;
  }

  // Demo: speichern in localStorage
  let users = JSON.parse(localStorage.getItem("users") || "[]");
  if (users.find((u) => u.email === email)) {
    msg.textContent = "Diese E-Mail-Adresse ist bereits registriert!";
    msg.className = "message error";
    return;
  }

  users.push({
    firstName,
    lastName,
    street,
    zip,
    city,
    email,
    password,
    role: "guest",
  });

  localStorage.setItem("users", JSON.stringify(users));

  msg.textContent = "Kundenkonto erfolgreich erstellt!";
  msg.className = "message success";
  form.reset();
});
