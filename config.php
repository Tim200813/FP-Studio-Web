<?php
require 'vendor/autoload.php';

// Debugging aktivieren (setze auf false, wenn du keine Ausgaben sehen möchtest)
$debug = false;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$db_host = $_ENV['DB_HOST'] ?? '';
$db_port = $_ENV['DB_PORT'] ?? '';
$db_name = $_ENV['DB_NAME'] ?? '';
$db_user = $_ENV['DB_USER'] ?? '';
$db_password = $_ENV['DB_PASSWORD'] ?? '';

// Debugging-Ausgaben
if ($debug) {
    echo "DB_HOST: " . htmlspecialchars($db_host) . "<br>";
    echo "DB_PORT: " . htmlspecialchars($db_port) . "<br>";
    echo "DB_NAME: " . htmlspecialchars($db_name) . "<br>";
    echo "DB_USER: " . htmlspecialchars($db_user) . "<br>";
    echo "DB_PASSWORD: " . htmlspecialchars($db_password) . "<br>";
}

// Datenbankverbindung herstellen
try {
    $dsn = "pgsql:host=$db_host;port=$db_port;dbname=$db_name";
    
    // Debugging-Ausgabe
    if ($debug) {
        echo "Verbindungs-DSN: " . htmlspecialchars($dsn) . "<br>";
    }

    if (empty($db_host) || empty($db_port) || empty($db_name) || empty($db_user) || empty($db_password)) {
        throw new Exception("Eine oder mehrere Umgebungsvariablen sind leer.");
    }

    $pdo = new PDO($dsn, $db_user, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    if ($debug) {
        echo "✅ Verbindung zur Datenbank erfolgreich!";
    }
} catch (PDOException $e) {
    die("❌ Verbindung zur Datenbank fehlgeschlagen: " . $e->getMessage());
} catch (Exception $e) {
    die("❌ Fehler: " . $e->getMessage());
}
?>
