<?php
require 'config.php'; // Stelle sicher, dass der Pfad zu config.php korrekt ist

// Funktion zum Abrufen von Daten aus der Datenbank
function fetchData($pdo, $table) {
    try {
        $query = $pdo->query("SELECT * FROM $table");
        return $query->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("❌ Fehler beim Abrufen der Daten: " . $e->getMessage());
    }
}

// Daten abrufen
$newsItems = fetchData($pdo, 'newsteam'); // Tabelle für Aktuelles
$statusItems = fetchData($pdo, 'statusteam'); // Tabelle für Status
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FP-Studio-Team</title>
    <link rel="stylesheet" href="styles1.css">
    <script>
        function toggleMenu() {
            const tabs = document.querySelector('.tabs');
            tabs.style.display = tabs.style.display === 'flex' ? 'none' : 'flex';
        }
        function showTab(tabId) {
            document.querySelectorAll('.tab-content').forEach(tab => tab.classList.remove('active'));
            document.getElementById(tabId).classList.add('active');
        }
    </script>
</head>
<body>

    <button class="logout-button" onclick="window.location.href='index.php'">Logout</button>

    <div class="menu" onclick="toggleMenu()">Menü ☰</div>
    <div class="tabs">
        <button class="tab-button" onclick="showTab('news')">Aktuelles</button>
        <button class="tab-button" onclick="window.location.href='./story/story.php'">Story</button>
        <button class="tab-button" onclick="window.location.href='./storyboard/staffel.php'">Storyboards</button>
        <button class="tab-button" onclick="showTab('status')">Status</button>
        <button class="tab-button" onclick="window.location.href='./character/index.php'">Charakter-Wekipedia</button>
    </div>

    <div id="news" class="tab-content active">
        <h2>Aktuelles</h2>
        <div id="news-list">
            <?php foreach ($newsItems as $news): ?>
                <div class="news-item">
                    <h3><?= htmlspecialchars($news['title']) ?></h3>
                    <p><?= htmlspecialchars($news['content']) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div id="storyboard" class="tab-content">
        <h2>Storyboards</h2>
        <div class="storyboard-gallery">
            <div class="storyboard-item">
                <img src="./image/02.png" alt="Storyboard 1">
                <p>Anmerkung: Szene 1, Kampf beginnt</p>
            </div>
            <div class="storyboard-item">
                <img src="./image/02.png" alt="Storyboard 1">
                <p>Anmerkung: Einführung des Antagonisten</p>
            </div>
        </div>
    </div>

    <div id="story" class="tab-content">
        <h2>Story</h2>
        <p>Die Geschichte der Anime-Charaktere wird hier beschrieben.</p>
        <button onclick="window.location.href='staffel1.html'">Staffel 1</button>
    </div>
    

    <div id="status" class="tab-content">
        <h2>Status</h2>
        <div id="status-list">
            <?php foreach ($statusItems as $status): ?>
                <div class="status-item">
                    <h3><?= htmlspecialchars($status['title']) ?></h3>
                    <p><?= htmlspecialchars($status['description']) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div id="characters" class="tab-content">
        <h2>Character Datenbank</h2>
        <div class="character-card">
            <h3>Ryu Takashi</h3>
            <p>Alter: 25</p>
            <p>Fähigkeiten: Blitzschnelle Schwertkunst ⚡</p>
            <p>Hintergrund: Wurde in einer alten Kriegerfamilie geboren...</p>
        </div>
        <div class="character-card">
            <h3>Aiko Himura</h3>
            <p>Alter: 22</p>
            <p>Fähigkeiten: Magische Feuerkontrolle 🔥</p>
            <p>Hintergrund: Entdeckte ihre Fähigkeiten in jungen Jahren...</p>
        </div>
    </div>


</body>
</html>