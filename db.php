<?php
// Configuration de la base de données
define('DB_HOST', 'localhost');
define('DB_NAME', 'labo_medical');
define('DB_USER', 'root'); // Modifier selon votre configuration
define('DB_PASS', ''); // Modifier selon votre configuration

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Erreur de connexion à la base de données: " . $e->getMessage());
}
?>