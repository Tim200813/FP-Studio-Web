<?php
$db = new SQLite3('org-storyboard.db');

// Episode-ID abrufen
$episode_id = $_GET['episode_id'] ?? 1;

// Sicherstellen, dass nur Zahlen verwendet werden
if (!is_numeric($episode_id)) {
    die("Ungültige Episodennummer!");
}

// Episodentitel abrufen
$episode_query = $db->prepare("SELECT title FROM story WHERE id = :episode_id");
$episode_query->bindValue(':episode_id', $episode_id, SQLITE3_INTEGER);
$episode_result = $episode_query->execute();
$episode = $episode_result->fetchArray();

if (!$episode) {
    die("Episode nicht gefunden!");
}

// Szenen abrufen
$scenes_query = $db->prepare("SELECT id, title FROM scenes WHERE episode_id = :episode_id");
$scenes_query->bindValue(':episode_id', $episode_id, SQLITE3_INTEGER);
$scenes_result = $scenes_query->execute();
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($episode['title']) ?></title>
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
        .scene-buttons {
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
    <h1><?= htmlspecialchars($episode['title']) ?></h1>
    <div class="scene-buttons">
        <?php while ($scene = $scenes_result->fetchArray()): ?>
            <button class="btn" onclick="window.location.href='szene.php?scene_id=<?= $scene['id'] ?>'">
                🎬 <?= htmlspecialchars($scene['title']) ?>
            </button>
        <?php endwhile; ?>
    </div>
    <a class="back-btn" href="javascript:history.back()">🔙 Zurück</a>
</body>
</html>
