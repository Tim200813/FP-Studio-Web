<?php
$db = new SQLite3('org-storyboard.db');

// Szenen-ID abrufen
$scene_id = $_GET['scene_id'] ?? 1;

// Sicherstellen, dass nur Zahlen verwendet werden
if (!is_numeric($scene_id)) {
    die("Ungültige Szenennummer!");
}

// Szenentitel abrufen
$scene_query = $db->prepare("SELECT title FROM scenes WHERE id = :scene_id");
$scene_query->bindValue(':scene_id', $scene_id, SQLITE3_INTEGER);
$scene_result = $scene_query->execute();
$scene = $scene_result->fetchArray();

if (!$scene) {
    die("Szene nicht gefunden!");
}

// Bilder und Download-URLs abrufen
$image_query = $db->prepare("SELECT image_url, download_url FROM storyboard_images WHERE scene_id = :scene_id");
$image_query->bindValue(':scene_id', $scene_id, SQLITE3_INTEGER);
$image_result = $image_query->execute();
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($scene['title']) ?></title>
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
            height: 100vh;
            background-attachment: fixed;
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
        .gallery {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            justify-content: center;
            padding: 20px;
        }
        .image-card {
            background: #333;
            padding: 10px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            transition: transform 0.3s ease;
            text-align: center;
        }
        .image-card:hover {
            transform: scale(1.05);
        }
        .image-card img {
            width: 100%;
            height: auto;
            border-radius: 10px;
        }
        .download-btn {
            display: inline-block;
            margin-top: 10px;
            padding: 10px 20px;
            background-color: #ff4de0;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background 0.3s ease;
        }
        .download-btn:hover {
            background-color: #ff267e;
        }
        .no-download {
            margin-top: 10px;
            color: #ff4de0; /* Farbe für den Hinweis */
        }
        .back-btn {
            margin-top: 30px;
            padding: 10px 20px;
            background-color: #00d4ff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            transition: background 0.3s ease;
        }
        .back-btn:hover {
            background-color: #0097b2;
        }
    </style>
</head>
<body>
    <h1><?= htmlspecialchars($scene['title']) ?></h1>

    <div class="gallery">
        <?php while ($image = $image_result->fetchArray()): ?>
            <div class="image-card">
                <img src="<?= htmlspecialchars($image['image_url']) ?>" alt="Storyboard Bild">
                <?php if (!empty($image['download_url'])): ?>
                    <a class="download-btn" href="<?= htmlspecialchars($image['download_url']) ?>" download>📥 Download</a>
                <?php else: ?>
                    <div class="no-download">Kein Download verfügbar</div>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    </div>

    <a class="back-btn" href="javascript:history.back()">🔙 Zurück</a>
</body>
</html>
