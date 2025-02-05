<?php
// Verbindung zur SQLite-Datenbank herstellen
$db_file = "character.db";
$conn = new SQLite3($db_file);

// Prüfe, ob die Verbindung erfolgreich war
if (!$conn) {
    die("Verbindung zur Datenbank fehlgeschlagen!");
}

// Standard-SQL-Abfrage (zeigt alle Charaktere an)
$sql = "
    SELECT c.id, c.name, c.biography, c.strengths, c.relationships, c.image_url,
           c.art_url, c.music_theme, c.preferencesList, c.origin,
           c.dreamDesireGoal, c.connection, c.age, c.attributesList,
           a.ability_name, a.description
    FROM characters c
    LEFT JOIN abilities a ON c.id = a.character_id
";

if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = htmlspecialchars($_GET['search'], ENT_QUOTES, 'UTF-8');
    $sql .= " WHERE c.name LIKE '%$search%'";
}

$result = $conn->query($sql);

// Array zum Speichern der Charaktere mit ihren Fähigkeiten
$characters = [];
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    $character_id = $row['id']; // Verwende die ID als Schlüssel
    if (!isset($characters[$character_id])) {
        // Erstelle einen neuen Charaktereintrag
        $characters[$character_id] = [
            'id' => $row['id'],
            'name' => $row['name'] ? htmlspecialchars($row['name']) : 'ist nicht vorhanden',
            'biography' => $row['biography'] ? nl2br(htmlspecialchars($row['biography'])) : 'ist nicht vorhanden',
            'strengths' => $row['strengths'] ? nl2br(htmlspecialchars($row['strengths'])) : 'ist nicht vorhanden',
            'relationships' => $row['relationships'] ? nl2br(htmlspecialchars($row['relationships'])) : 'ist nicht vorhanden',
            'image_url' => $row['image_url'] ? htmlspecialchars($row['image_url']) : '',
            'art_url' => $row['art_url'] ? htmlspecialchars($row['art_url']) : '',
            'music_theme' => $row['music_theme'] ? nl2br(htmlspecialchars($row['music_theme'])) : 'ist nicht vorhanden',
            'preferencesList' => $row['preferencesList'] ? nl2br(htmlspecialchars($row['preferencesList'])) : 'ist nicht vorhanden',
            'origin' => $row['origin'] ? nl2br(htmlspecialchars($row['origin'])) : 'ist nicht vorhanden',
            'dreamDesireGoal' => $row['dreamDesireGoal'] ? nl2br(htmlspecialchars($row['dreamDesireGoal'])) : 'ist nicht vorhanden',
            'connection' => $row['connection'] ? nl2br(htmlspecialchars($row['connection'])) : 'ist nicht vorhanden',
            'age' => $row['age'] ? htmlspecialchars($row['age']) : 'ist nicht vorhanden',
            'attributesList' => $row['attributesList'] ? nl2br(htmlspecialchars($row['attributesList'])) : 'ist nicht vorhanden',
            'abilities' => []
        ];
    }

    // Füge die Fähigkeit hinzu, wenn sie existiert
    if (!empty($row['ability_name'])) {
        $characters[$character_id]['abilities'][] = [
            'name' => nl2br(htmlspecialchars($row['ability_name'])),
            'description' => !empty($character['description']) ? nl2br(htmlspecialchars($ability['description'])): ''
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Charakter-Suche</title>
    <style>
        /* Allgemeines Styling */
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #1e1e2f, #2a2a4a);
            color: white;
            margin: 0;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        h1 {
            text-transform: uppercase;
            font-size: 2.5em;
            letter-spacing: 2px;
        }

        /* Suchformular */
        form {
            background: rgba(255, 255, 255, 0.1);
            padding: 15px;
            border-radius: 10px;
            display: flex;
            gap: 10px;
            backdrop-filter: blur(10px);
            width: 50%;
            justify-content: center;
        }

        input {
            padding: 10px;
            border: none;
            border-radius: 5px;
            font-size: 1em;
            flex: 1;
        }

        button {
            background: #ff4757;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            transition: 0.3s;
        }

        button:hover {
            background: #e84148;
            transform: scale(1.05);
        }

        /* Charakter-Container */
        .character-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); /* Anpassen der minimalen Breite */
            gap: 20px;
            max-width: 1200px;
            margin-top: 20px;
        }

        .character {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            transition: transform 0.3s;
            backdrop-filter: blur(10px);
            cursor: pointer; /* Zeigt an, dass es anklickbar ist */
            display: flex; /* Flexbox verwenden für die Anordnung */
            flex-direction: column; /* Elemente in einer Spalte anordnen */
            align-items: center; /* Elemente zentrieren */
        }

        .character:hover {
            transform: scale(1.02);
        }

        .character img {
            max-width: 200px; /* Maximale Breite */
            height: auto; /* Höhe automatisch anpassen */
            border-radius: 10px;
            margin-bottom: 15px; /* Abstand unter dem Bild */
        }

        .character-info {
            text-align: center; /* Text zentrieren */
        }

        .character-info h3 {
            font-size: 1.8em;
            margin: 10px 0; /* Abstand oben und unten */
        }

        .character-info p {
            font-size: 1em;
            opacity: 0.9;
            line-height: 1.5;
            margin: 5px 0; /* Abstand oben und unten */
        }

        /* Link zu art_url */
        .art-button {
            margin-top: 10px;
            display: inline-block;
            padding: 10px 15px;
            background: #4caf50;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            transition: background 0.3s;
        }

        .art-button:hover {
            background: #45a049;
        }

        /* Fähigkeiten-Container */
        .abilities {
            margin-top: 10px;
            padding: 10px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            width: 100%; /* Die Breite auf 100% setzen */
        }

        .abilities h4 {
            margin: 0;
        }

        /* Responsive Anpassungen */
        @media (max-width: 768px) {
            .character-container {
                grid-template-columns: 1fr;
            }
            
            .character {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }

            .character img {
                max-width: 100%;
            }
        }

        .back-button {
            display: inline-block;
            padding: 12px 24px; /* genügend Padding für eine angenehme Fläche */
            background: #4caf50; /* Grundfarbe für den Button */
            color: white;
            border: none; /* keine Rahmen */
            border-radius: 8px; /* sanfte Ecken */
            text-decoration: none;
            font-weight: bold; /* fett für mehr Sichtbarkeit */
            font-size: 16px; /* größere Schriftgröße für bessere Lesbarkeit */
            transition: background 0.3s, transform 0.3s; /* sanfte Übergänge */
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3); /* Schatten für Tiefe */
        }

        .back-button:hover {
            background: #45a049; /* dunklere Farbe bei Hover */
            transform: translateY(-2px); /* leichtes Anheben des Buttons beim Hover */
        }

        .back-button:active {
            transform: translateY(0); /* Button zurücksetzen beim Klicken */
        }

        .button-container {
            text-align: center; /* zentriert den Button */
            margin-top: 20px; /* Abstand zum oberen Inhalt */
        }
    </style>
</head>
<body>
    <h1>Charakter-Suche</h1>

    <!-- Suchformular -->
    <form action="index.php" method="GET">
        <input type="text" name="search" placeholder="Charaktername eingeben" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
        <button type="submit">Suchen</button>
    </form>

    <div class="character-container">
        <?php
        // Anzeigen der Charaktere und ihrer Fähigkeiten
        foreach ($characters as $character) {
            echo "<div class='character' onclick=\"location.href='character_details.php?id=" . $character['id'] . "';\">"; // Link zur Detailseite
            if (!empty($character['image_url'])) {
                echo "<img src='" . $character['image_url'] . "' alt='" . $character['name'] . "'>";
            }
            echo "<div class='character-info'>";
            echo "<h3>" . $character['name'] . "</h3>";
           // echo "<p><strong>Alter:</strong> " . $character['age'] . "</p>";
           // echo "<p><strong>Herkunft:</strong> " . $character['origin'] . "</p>";

            // Link zur Art-Seite (art_url)

            echo "</div></div>"; // Schließen der character-info und character-Div
        }
        ?>
    </div>

    <div class="button-container">
        <a href="../index.php" class="back-button">Home</a> <!-- Zentrierter Back-Button -->
    </div>
</body>
</html>
