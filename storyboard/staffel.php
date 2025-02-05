<?php
$db = new SQLite3('org-storyboard.db');

// Staffelnummer abrufen
$season_number = $_GET['season'] ?? 1;

// Sicherstellen, dass nur Zahlen verwendet werden
if (!is_numeric($season_number)) {
    die("Ungültige Staffelnummer!");
}

// Episoden für die Staffel abrufen
$episodes = $db->prepare("SELECT id, episode, title FROM story WHERE season = :season");
$episodes->bindValue(':season', $season_number, SQLITE3_INTEGER);
$episode_result = $episodes->execute();
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staffel <?= htmlspecialchars($season_number) ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Roboto', sans-serif;
        }
        body {
            background-image: url('../image/background2.svg');
            background-attachment: fixed;
            height: 100vh;
            background-size: cover;
            color: white;
            text-align: center;
            padding: 20px;
        }
        h1 {
            font-size: 2rem;
            margin-bottom: 20px;
            color: #ff4de0;
        }
        .season-buttons {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 15px;
        }
        .btn {
            background-color: #00d4ff;
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 8px;
            font-size: 1.2rem;
            cursor: pointer;
            transition: 0.3s ease;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }
        .btn:hover {
            background-color: #0097b2;
            transform: scale(1.05);
        }
        .back-btn {
            margin-top: 30px;
            padding: 10px 20px;
            background-color: #ff4de0;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            transition: 0.3s ease;
        }
        .back-btn:hover {
            background-color: #ff267e;
        }
    </style>
</head>
<body>
    <h1>Staffel <?= htmlspecialchars($season_number) ?></h1>
    <div class="season-buttons">
        <?php while ($episode = $episode_result->fetchArray()): ?>
            <button class="btn" onclick="window.location.href='folge.php?episode_id=<?= $episode['id'] ?>'">
                📺 <?= htmlspecialchars($episode['title']) ?> (Folge <?= $episode['episode'] ?>)
            </button>
        <?php endwhile; ?>
    </div>
    <a class="back-btn" href="javascript:history.back()">🔙 Zurück</a>
</body>
</html>
