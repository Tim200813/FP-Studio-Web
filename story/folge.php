<?php
// Datenbankverbindung herstellen
$db = new SQLite3('org.db');

// Staffel- und Episodenummer aus der URL abrufen
$season_number = $_GET['season'];
$episode_number = $_GET['episode'];

// Abfrage der Szenen für die gewählte Episode
$scenes = $db->query("SELECT scene_number, word_link FROM scenes WHERE season = $season_number AND episode = $episode_number");
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Folge <?= $episode_number ?> - Staffel <?= $season_number ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'Roboto', sans-serif;
            background-image: url('../image/background.svg');
            background-attachment: fixed;
            height: 100vh;
            background-size: cover;
            color: #ffffff;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }
        header {
            background: rgba(40, 40, 40, 0.9);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
            margin-bottom: 20px;
            width: 100%;
            text-align: center;
        }
        h1 {
            font-size: 2.5rem;
            color:rgb(204, 64, 255); /* Bunter Titel */
        }
        .scene-buttons {
            margin-top: 80px; /* Abstand für das Header */
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
        }
        .btn {
            background-color:rgb(0, 191, 255); /* Buntes Pink */
            color: white;
            padding: 20px 40px;
            margin: 10px; /* Abstand zwischen den Buttons */
            border: none;
            border-radius: 10px;
            font-size: 1.5rem;
            transition: transform 0.3s ease, background-color 0.3s ease;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            position: relative;
            overflow: hidden;
        }
        .btn:hover {
            transform: translateY(-5px);
            background-color:rgb(255, 181, 61); /* Helleres Pink */
        }
        footer {
            padding: 20px;
            background: rgba(40, 40, 40, 0.9);
            color: white;
            width: 100%;
            position: relative;
            margin-top: auto;
            border-radius: 10px;
            box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.5);
            text-align: center;
        }
    </style>
</head>
<body>
    <header>
        <h1>Folge <?= $episode_number ?> - Staffel <?= $season_number ?></h1>
    </header>
    <div class="scene-buttons">
        <?php while ($scene = $scenes->fetchArray()): ?>
            <button class="btn" onclick="window.location.href='<?= $scene['word_link'] ?>'">Szene <?= $scene['scene_number'] ?></button>
        <?php endwhile; ?>
    </div>
    <p><button class="btn" onclick="history.back()">Zurück</button></p>
    <footer>
        <p>&copy; 2025 Anime Story. Alle Rechte vorbehalten.</p>
    </footer>
</body>
</html>
