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
$newsItems = fetchData($pdo, 'news'); // Tabelle für Aktuelles
$statusItems = fetchData($pdo, 'status'); // Tabelle für Status
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FP-Studio</title>
    <link rel="stylesheet" href="styles1.css">
    <script src="https://cdn.jsdelivr.net/npm/@supabase/supabase-js"></script>
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

    <button class="login-button" onclick="window.location.href='login.html'">Login</button>

    <div class="menu" onclick="toggleMenu()">Menü ☰</div>
    <div class="tabs">
        <button class="tab-button" onclick="showTab('news')">Aktuelles</button>
        <button class="tab-button" onclick="showTab('status')">Status</button>
        <button class="tab-button" onclick="window.location.href='team.html'">Team</button>
        <button class="tab-button" onclick="window.location.href='./character/index.php'">Charakter-Wikipedia</button>
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

    <div id="video" class="tab-content">
        <h2>Video</h2>
        <video id="myVideo" src="./image/lol.mp4"></video>
        
        <!-- Eigene Steuerung -->
        <div class="video-controls">
            <button id="playPause">▶️</button>
            <input type="range" id="seekBar" value="0">
            <span id="timeDisplay">0:00 / 0:00</span>
        </div>

        <script>
            document.addEventListener("DOMContentLoaded", function () {
                const video = document.getElementById("myVideo");
                const playPauseBtn = document.getElementById("playPause");
                const seekBar = document.getElementById("seekBar");
                const timeDisplay = document.getElementById("timeDisplay");

                // Stellt sicher, dass das Video mit Ton startet
                video.muted = false;

                // Play/Pause Funktion
                playPauseBtn.addEventListener("click", function () {
                    if (video.paused) {
                        video.play();
                        playPauseBtn.textContent = "⏸️"; // Ändert das Symbol zu Pause
                    } else {
                        video.pause();
                        playPauseBtn.textContent = "▶️"; // Ändert das Symbol zu Play
                    }
                });

                // Aktualisiert die Timeline während das Video läuft
                video.addEventListener("timeupdate", function () {
                    let progress = (video.currentTime / video.duration) * 100;
                    seekBar.value = progress;

                    // Aktualisiert die Zeitanzeige
                    let currentMinutes = Math.floor(video.currentTime / 60);
                    let currentSeconds = Math.floor(video.currentTime % 60);
                    let durationMinutes = Math.floor(video.duration / 60);
                    let durationSeconds = Math.floor(video.duration % 60);
                    
                    timeDisplay.textContent = `${currentMinutes}:${currentSeconds < 10 ? '0' : ''}${currentSeconds} / ${durationMinutes}:${durationSeconds < 10 ? '0' : ''}${durationSeconds}`;
                });

                // Benutzer kann die Timeline steuern
                seekBar.addEventListener("input", function () {
                    let seekTime = (seekBar.value / 100) * video.duration;
                    video.currentTime = seekTime;
                });
            });
        </script>
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

    <div class="black-bar">
        <a href="main-about-us.html">Kontakte</a>
        <a href="impressum.html">Impressum</a>
        <a href="dashboard-kontakt.html">Beauftrage uns</a>
        <a href="bewerbung.html">Werde Mitglied ↗️</a>
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
