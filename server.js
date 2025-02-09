const express = require("express");
const sqlite3 = require("sqlite3").verbose();
const path = require("path");
const cors = require("cors");

const app = express();
const PORT = 3000;

// CORS erlauben (damit der Client darauf zugreifen kann)
app.use(cors());

// Statische Dateien bereitstellen (CSS, JS, Bilder, etc.)
app.use(express.static(__dirname)); // Falls index.html im Hauptverzeichnis ist
// Falls index.html in "storyboard" liegt, dann stattdessen:
// app.use(express.static(path.join(__dirname, "storyboard")));
// Statische Dateien bereitstellen (HTML, CSS, etc.) // Der Pfad zum Ordner mit den HTML-Dateien

// Route für die Startseite
app.get("/", (req, res) => {
    res.sendFile(path.join(__dirname, "index.html"));
    // Falls index.html in "storyboard" ist:
    // res.sendFile(path.join(__dirname, "storyboard", "index.html"));
});

// Verbindung zur SQLite-Datenbank für das Storyboard
const db = new sqlite3.Database("./storyboard/org-storyboard.db", sqlite3.OPEN_READONLY, (err) => {
    if (err) {
        console.error("Fehler beim Verbinden mit der Datenbank:", err.message);
    } else {
        console.log("Erfolgreich mit der Datenbank verbunden.");
    }
});

// API-Route: Alle Seasons abrufen
app.get("/api/seasons", (req, res) => {
    db.all("SELECT DISTINCT season FROM storyboard", [], (err, rows) => {
        if (err) {
            res.status(500).json({ error: err.message });
            return;
        }
        res.json(rows);
    });
});

// API-Route: Alle Episoden einer Season abrufen
app.get("/api/episodes/:season", (req, res) => {
    const { season } = req.params;
    db.all("SELECT DISTINCT episode FROM storyboard WHERE season = ?", [season], (err, rows) => {
        if (err) {
            res.status(500).json({ error: err.message });
            return;
        }
        res.json(rows);
    });
});

// API-Route: Alle Szenen einer Episode abrufen
app.get("/api/scenes/:season/:episode", (req, res) => {
    const { season, episode } = req.params;
    db.all("SELECT DISTINCT scene FROM storyboard WHERE season = ? AND episode = ?", [season, episode], (err, rows) => {
        if (err) {
            res.status(500).json({ error: err.message });
            return;
        }
        res.json(rows);
    });
});

// API-Route: Bilder einer Szene abrufen
app.get("/api/images/:season/:episode/:scene", (req, res) => {
    const { season, episode, scene } = req.params;
    db.all("SELECT image_path, download_url FROM storyboard WHERE season = ? AND episode = ? AND scene = ?", 
        [season, episode, scene], (err, rows) => {
            if (err) {
                res.status(500).json({ error: err.message });
                return;
            }
            res.json(rows);
        });
});

// API-Route: Szenentitel abrufen
app.get("/api/scene-title/:season/:episode/:scene", (req, res) => {
    const { season, episode, scene } = req.params;
    db.get("SELECT title FROM scenes WHERE season = ? AND episode = ? AND scene = ?", 
        [season, episode, scene], (err, row) => {
            if (err) {
                res.status(500).json({ error: err.message });
                return;
            }
            res.json(row || { title: `Szene ${scene}` }); // Backticks für Template-String
        });
});

// Verbindung zur SQLite-Datenbank für Charaktere
const dbCharacters = new sqlite3.Database("./character/character.db", (err) => {
    // ...

    if (err) {
        console.error("Fehler beim Verbinden mit der Datenbank:", err.message);
    } else {
        console.log("Verbunden mit SQLite-Datenbank.");
    }
});

// API-Route: Alle Charaktere abrufen
app.get("/api/characters", (req, res) => {
    const sql = `
        SELECT c.id, c.name, c.biography, c.strengths, c.relationships, 
               c.image_url, c.art_url, c.music_theme, c.preferencesList, 
               c.origin, c.dreamDesireGoal, c.connection, c.age, c.attributesList, 
               a.ability_name, a.description
        FROM characters c
        LEFT JOIN abilities a ON c.id = a.character_id;
    `;

    dbCharacters.all(sql, [], (err, rows) => {
        if (err) {
            res.status(500).json({ error: err.message });
            return;
        }
        res.json(rows);
    });
});

// API-Route: Charaktere suchen
// API-Route: Charaktere nach Name suchen
app.get("/api/search", (req, res) => {
    const search = req.query.search || '';
    const sql = `
        SELECT * FROM characters 
        WHERE name LIKE ?;
    `;
    
    // "%" Zeichen für eine LIKE-Suche hinzufügen
    const searchTerm = `%${search}%`;

    dbCharacters.all(sql, [searchTerm], (err, rows) => {
        if (err) {
            res.status(500).json({ error: err.message });
            return;
        }
        res.json(rows); // Gibt die gefundenen Charaktere als JSON zurück
    });
});

// Beispiel für Express.js
app.get('/api/characters/:id', (req, res) => {
    const characterId = req.params.id;
    const characterSql = `SELECT * FROM characters WHERE id = ?`;
    const abilitiesSql = `SELECT ability_name, description FROM abilities WHERE character_id = ?`;

    // Zuerst den Charakter abrufen
    dbCharacters.get(characterSql, [characterId], (err, character) => {
        if (err) {
            return res.status(500).json({ error: err.message });
        }
        if (!character) {
            return res.status(404).json({ error: 'Charakter nicht gefunden' });
        }

        // Dann die Fähigkeiten abrufen
        dbCharacters.all(abilitiesSql, [characterId], (err, abilities) => {
            if (err) {
                return res.status(500).json({ error: err.message });
            }
            // Sende sowohl Charakter als auch Fähigkeiten zurück
            res.json({ character, abilities });
        });
    });
});


//story

// Verbindung zur SQLite-Datenbank für die Story
const dbStory = new sqlite3.Database("./story/org.db", sqlite3.OPEN_READONLY, (err) => {
    if (err) {
        console.error("Fehler beim Verbinden mit der Story-Datenbank:", err.message);
    } else {
        console.log("Erfolgreich mit der Story-Datenbank verbunden.");
    }
});

// API-Route: Alle Seasons aus der Story-Datenbank abrufen
// API-Route: Alle Seasons aus der Story-Datenbank abrufen
app.get("/api/seasons-story", (req, res) => {
    dbStory.all("SELECT DISTINCT season FROM story WHERE season IN (SELECT DISTINCT season FROM story WHERE episode IS NOT NULL)", [], (err, rows) => {
        if (err) {
            res.status(500).json({ error: err.message });
            return;
        }
        console.log("Daten aus der API für /api/seasons-story:", rows); // Debugging
        res.json(rows);
    });
});

// API-Route: Alle Episoden einer Season abrufen
app.get("/api/episodes-story/:season", (req, res) => {
    const { season } = req.params;
    dbStory.all("SELECT DISTINCT episode FROM story WHERE season = ?", [season], (err, rows) => {
        if (err) {
            res.status(500).json({ error: err.message });
            return;
        }
        res.json(rows);
    });
});


// API-Route: Alle Szenen einer Episode abrufen
app.get("/api/scenes-story/:season/:episode", (req, res) => {
    const { season, episode } = req.params;
    dbStory.all(
        "SELECT scene_number AS scene, word_link FROM story WHERE season = ? AND episode = ?", 
        [season, episode], 
        (err, rows) => {
            if (err) {
                res.status(500).json({ error: err.message });
                return;
            }
            console.log("Daten aus der Datenbank:", rows);  // 🔴 DEBUGGING
            res.json(rows);
        }
    );
});





// Server starten
app.listen(PORT, () => {
    console.log(`Server läuft auf http://localhost:${PORT}`); // Backticks für Template-String
});
