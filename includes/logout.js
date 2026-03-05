// Löscht alle Login-relevanten Daten und leitet auf die Startseite weiter
document.addEventListener("DOMContentLoaded", () => {
  // Alles entfernen, was den Login betrifft
  localStorage.removeItem("role");
  localStorage.removeItem("firstName");
  localStorage.removeItem("users"); // optional, je nach Bedarf
  localStorage.removeItem("cart"); // optional: Warenkorb leeren

  // Danach zur Startseite weiterleiten
  window.location.href = "/index.html";
});
