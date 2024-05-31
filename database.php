<?php
$servername = "localhost";
$username = "root"; // Tu nombre de usuario de MySQL
$password = ""; // Tu contraseña de MySQL
$dbname = "movie_recommender"; // El nombre de tu base de datos

try {
    // Crear una nueva instancia de PDO
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    // Configurar el modo de errores de PDO para que lance excepciones
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Manejar errores de conexión
    echo 'Connection failed: ' . $e->getMessage();
    exit;
}
