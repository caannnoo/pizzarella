// server.js
const express = require("express");
const fs = require("fs");
const path = require("path");
const bodyParser = require("body-parser");

const app = express();
const PORT = 3000;

// Middleware
app.use(bodyParser.json());

// Statische Ordner freigeben
app.use("/css", express.static(path.join(__dirname, "css"))); // CSS
app.use("/img", express.static(path.join(__dirname, "img"))); // Bilder
app.use(express.static(path.join(__dirname, "sites"))); // HTML/JS Dateien
app.use("/includes", express.static(path.join(__dirname, "includes"))); // Header/Footer/JS

// Registrierung: POST /register
app.post("/register", (req, res) => {
  const user = req.body;
  const usersPath = path.join(__dirname, "data", "users.json");

  // users.json laden
  let users = [];
  if (fs.existsSync(usersPath)) {
    users = JSON.parse(fs.readFileSync(usersPath));
  }

  // prüfen, ob Email existiert
  if (users.find((u) => u.email === user.email)) {
    return res.status(400).json({ message: "Email existiert bereits!" });
  }

  // speichern
  users.push(user);
  fs.writeFileSync(usersPath, JSON.stringify(users, null, 2));
  res.json({ message: "Registrierung erfolgreich!" });
});

app.listen(PORT, () =>
  console.log(`Server läuft auf http://127.0.0.1:${PORT}`),
);
