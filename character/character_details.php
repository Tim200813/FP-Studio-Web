<?php
// Verbindung zur SQLite-Datenbank herstellen
$db_file = "character.db";
$conn = new SQLite3($db_file);

// Prüfen, ob die ID übergeben wurde
if (isset($_GET['id'])) {
    $character_id = intval($_GET['id']);
    
    // Abrufen der Charakterdetails
    $sql = "SELECT * FROM characters WHERE id = $character_id";
    $character = $conn->querySingle($sql, true);

    // Abrufen der Fähigkeiten
    $abilities_sql = "SELECT ability_name, description FROM abilities WHERE character_id = $character_id";
    $abilities_result = $conn->query($abilities_sql);
    $abilities = [];
    while ($row = $abilities_result->fetchArray(SQLITE3_ASSOC)) {
        $abilities[] = $row;
    }
} else {
    // Wenn keine ID übergeben wurde
    die("Keine Charakter-ID angegeben.");
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($character['name'] ?? 'Charakterdetails'); ?> - Details</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #1e1e2f, #2a2a4a);
            color: white;
            margin: 0;
            padding: 20px;
        }

        .character-details {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            padding: 20px;
            max-width: 800px;
            margin: auto;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }

        .character-details h1 {
            margin-bottom: 20px;
            text-align: center; /* Titel zentrieren */
        }

        .character-details img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .not-found {
            color: red;
            font-style: italic;
        }

        .back-button {
            display: inline-block;
            padding: 12px 24px;
            background: #4caf50;
            color: white;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            font-size: 16px;
            transition: background 0.3s, transform 0.3s;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            text-align: center; /* Zentrierter Text im Button */
        }

        .back-button:hover {
            background: #45a049;
            transform: translateY(-2px);
        }

        .button-container {
            text-align: center;
            margin-top: 20px;
        }

        .abilities {
            margin-top: 20px;
        }

        .abilities h4 {
            margin: 10px 0 5px 0; /* Abstände für die Fähigkeiten */
        }

        .character-detail-item {
            text-align: center; /* Zentriert Titel und Inhalt */
            margin: 15px 0; /* Abstand zwischen den Abschnitten */
        }

        .character-detail-title {
            font-weight: bold; /* Titel fett */
            font-size: 20px; /* Größere Schriftgröße für Titel */
        }
    </style>
</head>
<body>

<div class="character-details">
    <h1><?php echo htmlspecialchars($character['name'] ?? 'Charaktername nicht vorhanden'); ?></h1>
    <?php if (!empty($character['image_url'])): ?>
        <img src="<?php echo htmlspecialchars($character['image_url']); ?>" alt="<?php echo htmlspecialchars($character['name']); ?>">
    <?php else: ?>
        <p class="not-found">Bild ist nicht vorhanden</p>
    <?php endif; ?>

    <div class="character-detail-item">
        <h2 class="character-detail-title">Biografie:</h2>
        <div><?php echo !empty($character['biography']) ? nl2br(htmlspecialchars($character['biography'])) : '<span class="not-found">ist nicht vorhanden</span>'; ?></div>
    </div>

    <div class="character-detail-item">
        <h2 class="character-detail-title">Stärken:</h2>
        <div><?php echo !empty($character['strengths']) ? nl2br(htmlspecialchars($character['strengths'])) : '<span class="not-found">ist nicht vorhanden</span>'; ?></div>
    </div>

    <div class="character-detail-item">
        <h2 class="character-detail-title">Beziehungen:</h2>
        <div><?php echo !empty($character['relationships']) ? nl2br(htmlspecialchars($character['relationships'])) : '<span class="not-found">ist nicht vorhanden</span>'; ?></div>
    </div>

    <div class="character-detail-item">
        <h2 class="character-detail-title">Alter:</h2>
        <div><?php echo !empty($character['age']) ? htmlspecialchars($character['age']) : '<span class="not-found">ist nicht vorhanden</span>'; ?></div>
    </div>

    <div class="character-detail-item">
        <h2 class="character-detail-title">Herkunft:</h2>
        <div><?php echo !empty($character['origin']) ? nl2br(htmlspecialchars($character['origin'])) : '<span class="not-found">ist nicht vorhanden</span>'; ?></div>
    </div>

    <div class="character-detail-item">
        <h2 class="character-detail-title">Wunsch/Ziel:</h2>
        <div><?php echo !empty($character['dreamDesireGoal']) ? nl2br(htmlspecialchars($character['dreamDesireGoal'])) : '<span class="not-found">ist nicht vorhanden</span>'; ?></div>
    </div>

    <div class="character-detail-item">
        <h2 class="character-detail-title">Verbindung:</h2>
        <div><?php echo !empty($character['connection']) ? nl2br(htmlspecialchars($character['connection'])) : '<span class="not-found">ist nicht vorhanden</span>'; ?></div>
    </div>

    <div class="character-detail-item">
        <h2 class="character-detail-title">Vorlieben:</h2>
        <div><?php echo !empty($character['preferencesList']) ? nl2br(htmlspecialchars($character['preferencesList'])) : '<span class="not-found">ist nicht vorhanden</span>'; ?></div>
    </div>

    <div class="character-detail-item">
        <h2 class="character-detail-title">Eigenschaften:</h2>
        <div><?php echo !empty($character['attributesList']) ? nl2br(htmlspecialchars($character['attributesList'])) : '<span class="not-found">ist nicht vorhanden</span>'; ?></div>
    </div>

    <div class="character-detail-item">
        <h2 class="character-detail-title">Musik-Theme:</h2>
        <div><?php echo !empty($character['music_theme']) ? nl2br(htmlspecialchars($character['music_theme'])) : '<span class="not-found">ist nicht vorhanden</span>'; ?></div>
    </div>

    <?php if (!empty($abilities)): ?>
        <h2 style="text-align: center;">Fähigkeiten:</h2>
        <div class="abilities">
            <?php foreach ($abilities as $ability): ?>
                <div class="character-detail-item">
                    <h2 class="character-detail-title"><?php echo nl2br(htmlspecialchars($ability['ability_name'])); ?></h2>
                    <div><?php echo !empty($character['description']) ? nl2br(htmlspecialchars($ability['description'])): '<span class="not-found">Beschreibung ist nicht vorhanden</span>'; ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p class="not-found">Fähigkeiten sind nicht vorhanden.</p>
    <?php endif; ?>

    <h2 style="text-align: center;">Art-Bereich:</h2>
    <?php if (!empty($character['art_url'])): ?>
        <div class="button-container">
            <a href="<?php echo htmlspecialchars($character['art_url']); ?>" class="back-button" target="_blank">Siehe dir alle Materialien zu dem Charakter an...</a>
        </div>
    <?php endif; ?>
</div>

<div class="button-container">
    <a href="index.php" class="back-button">Zurück</a> <!-- Zentrierter Back-Button -->
</div>

</body>
</html>
