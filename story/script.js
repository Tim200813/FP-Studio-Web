// Funktion, um die Staffel-Buttons anzuzeigen
async function loadSeasons() {
    const response = await fetch('/api/seasons'); // Dies muss implementiert werden
    const seasons = await response.json();
    const seasonButtons = document.getElementById('season-buttons');

    seasons.forEach(season => {
        const button = document.createElement('button');
        button.className = 'btn';
        button.innerText = `Staffel ${season.season}`;
        button.onclick = () => {
            window.location.href = `staffel.html?season=${season.season}`;
        };
        seasonButtons.appendChild(button);
    });
}

// Funktion, um die Episoden für eine bestimmte Staffel anzuzeigen
async function loadEpisodes() {
    const urlParams = new URLSearchParams(window.location.search);
    const seasonNumber = urlParams.get('season');
    document.getElementById('season-title').innerText = `Staffel ${seasonNumber}`;

    const response = await fetch(`/api/episodes?season=${seasonNumber}`);
    const episodes = await response.json();
    const episodeButtons = document.getElementById('episode-buttons');

    episodes.forEach(episode => {
        const button = document.createElement('button');
        button.className = 'btn';
        button.innerText = `Episode ${episode.episode}`;
        button.onclick = () => {
            window.location.href = `folge.html?season=${seasonNumber}&episode=${episode.episode}`;
        };
        episodeButtons.appendChild(button);
    });
}

// Funktion, um die Szenen für eine bestimmte Episode anzuzeigen
async function loadScenes() {
    const urlParams = new URLSearchParams(window.location.search);
    const seasonNumber = urlParams.get('season');
    const episodeNumber = urlParams.get('episode');
    document.getElementById('episode-title').innerText = `Episode ${episodeNumber} - Staffel ${seasonNumber}`;

    const response = await fetch(`/api/scenes?season=${seasonNumber}&episode=${episodeNumber}`);
    const scenes = await response.json();
    const sceneButtons = document.getElementById('scene-buttons');

    scenes.forEach(scene => {
        const button = document.createElement('a');
        button.className = 'btn';
        button.innerText = `Szene ${scene.scene_number}`;
        button.href = scene.word_link; // Link zur Google Docs-Datei
        button.target = "_blank"; // Öffne den Link in einem neuen Tab
        sceneButtons.appendChild(button);
    });
}

// Lade die entsprechende Funktion basierend auf der Seite
if (document.getElementById('season-buttons')) {
    loadSeasons();
} else if (document.getElementById('episode-buttons')) {
    loadEpisodes();
} else if (document.getElementById('scene-buttons')) {
    loadScenes();
}
