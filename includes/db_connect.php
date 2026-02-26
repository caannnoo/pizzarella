<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pizzarella";

$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset("utf8mb4");


if ($conn->connect_error) {
    die("Verbindung fehlgeschlagen: " . $conn->connect_error);
}
?>
