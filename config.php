<?php
// config.php

$db_host = 'db.rrxjwcgrhlevwdybksgv.supabase.co';
$db_port = '5432';
$db_name = 'postgres';
$db_user = 'postgres';
$db_password = 'TKKGfreunde1';

// Datenbankverbindung herstellen
try {
    $pdo = new PDO("pgsql:host=$db_host;port=$db_port;dbname=$db_name", $db_user, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("❌ Verbindung zur Datenbank fehlgeschlagen: " . $e->getMessage());
}
?>
