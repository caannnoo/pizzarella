// Header & Footer laden
fetch("../includes/header.html")
  .then((res) => res.text())
  .then((data) => {
    document.getElementById("header").innerHTML = data;
    const script = document.createElement("script");
    script.src = "../includes/header.js";
    document.body.appendChild(script);
  });

fetch("../includes/footer.html")
  .then((res) => res.text())
  .then((data) => (document.getElementById("footer").innerHTML = data));

// Prüfen ob Admin
let currentUser = JSON.parse(localStorage.getItem("currentUser"));
if (!currentUser || currentUser.role !== "admin") {
  alert("Nur Admins dürfen auf diese Seite!");
  window.location.href = "../index.html";
}

const msg = document.getElementById("account-message");

// Passwort ändern
const pwForm = document.getElementById("password-form");
pwForm.addEventListener("submit", (e) => {
  e.preventDefault();
  const oldPw = document.getElementById("oldPassword").value;
  const newPw = document.getElementById("newPassword").value;
  const confirmPw = document.getElementById("confirmPassword").value;

  if (oldPw !== currentUser.password) {
    msg.textContent = "Altes Passwort ist falsch!";
    msg.className = "account-message error";
    return;
  }

  if (newPw !== confirmPw) {
    msg.textContent = "Passwörter stimmen nicht überein!";
    msg.className = "account-message error";
    return;
  }

  currentUser.password = newPw;

  let users = JSON.parse(localStorage.getItem("users")) || [];
  const index = users.findIndex((u) => u.email === currentUser.email);
  if (index !== -1) users[index] = currentUser;

  localStorage.setItem("users", JSON.stringify(users));
  localStorage.setItem("currentUser", JSON.stringify(currentUser));

  msg.textContent = "Passwort erfolgreich geändert!";
  msg.className = "account-message success";
  pwForm.reset();
});
