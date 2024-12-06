<?php
$host = 'localhost';
$username = 'root'; // Dein Benutzername
$password = ''; // Dein Passwort
$database = 'kategorie_db'; 

// Verbindung zur Datenbank
$conn = new mysqli($host, $username, $password, $database);

// Prüf, ob die Verbindung erfolgreich war
if ($conn->connect_error) {
    die("Verbindung fehlgeschlagen: " . $conn->connect_error);
}
?>
