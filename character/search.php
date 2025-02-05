<?php
// Verbindung zur SQLite-Datenbank herstellen
$db = new SQLite3('character.db');

// Eingabe absichern
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$search = "%$search%";  // Platzhalter für LIKE-Suche

// SQL-Abfrage vorbereiten
$stmt = $db->prepare("SELECT * FROM characters WHERE name LIKE :search");
$stmt->bindValue(':search', $search, SQLITE3_TEXT);
$result = $stmt->execute();

// HTML Grundstruktur
echo "<!DOCTYPE html>
<html lang='de'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Suchergebnisse</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; background-color: #f4f4f4; }
        .container { width: 80%; margin: 20px auto; padding: 20px; background: white; box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1); border-radius: 10px; }
        img { max-width: 200px; border-radius: 10px; }
    </style>
</head>
<body>
<div class='container'>
    <h1>Suchergebnisse</h1>";

// Falls keine Ergebnisse gefunden wurden
$found = false;
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    $found = true;
    echo "<h2>" . htmlspecialchars($row['name']) . "</h2>";
    echo "<p><strong>Biografie:</strong> " . htmlspecialchars($row['biography']) . "</p>";
    echo "<p><strong>Stärken:</strong> " . htmlspecialchars($row['strengths']) . "</p>";
    echo "<p><strong>Beziehungen:</strong> " . htmlspecialchars($row['relationships']) . "</p>";
    
    if (!empty($row['image_url'])) {
        echo "<img src='" . htmlspecialchars($row['image_url']) . "' alt='" . htmlspecialchars($row['name']) . "'>";
    }
}
if (!$found) {
    echo "<p>Kein Charakter gefunden.</p>";
}

echo "</div></body></html>";

// Verbindung schließen
$db->close();
?>
