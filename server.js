const express = require("express");
const path = require("path");
const cors = require("cors");
const { createClient } = require('@supabase/supabase-js');

const app = express();
const PORT = 3000;



const supabaseUrl = "https://rrxjwcgrhlevwdybksgv.supabase.co";
const supabaseKey = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InJyeGp3Y2dyaGxldndkeWJrc2d2Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3Mzg1NDI1MDMsImV4cCI6MjA1NDExODUwM30.M8dAu4fCMpuVF_plJLlGTVmkhuonlVdX2-YQ4zUMtnI";  // Verwende den Anonymen Schlüssel oder Service Key je nach Bedarf

const supabase = createClient(supabaseUrl, supabaseKey);
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
//const db = new sqlite3.Database("./storyboard/org-storyboard.db", sqlite3.OPEN_READONLY, (err) => {
//    if (err) {
//         console.error("Fehler beim Verbinden mit der Datenbank:", err.message);
//     } else {
//         console.log("Erfolgreich mit der Datenbank verbunden.");
//     }
// });

// API-Route: Alle Seasons abrufen
// API-Route: Alle Seasons abrufen
app.get("/api/seasons", async (req, res) => {
    try {
        // Supabase-Abfrage, um alle einzigartigen Seasons abzurufen
        const { data, error } = await supabase
            .from('storyboard') // Name der Tabelle
            .select('season', { distinct: true }) // Einzigartige Seasons abrufen
            .order('season', { ascending: true }); // Nach Season aufsteigend sortieren

        if (error) throw error; // Bei Fehlern eine Ausnahme auslösen

        res.json(data || []); // Sicherstellen, dass immer eine Liste zurückgegeben wird
    } catch (err) {
        console.error("Fehler beim Abrufen der Seasons:", err.message);
        res.status(500).json({ error: err.message }); // Fehlerbehandlung
    }
});




// API-Route: Alle Episoden einer Season abrufen
// API-Route: Alle Episoden einer Season abrufen
app.get("/api/episodes/:season", async (req, res) => {
    const { season } = req.params; // Extrahiere die Saison aus den URL-Parametern

    if (!season) {
        return res.status(400).json({ error: "Season-Parameter ist erforderlich" });
    }

    try {
        // Supabase-Abfrage, um alle Episoden für die gegebene Saison abzurufen
        const { data, error } = await supabase
            .from('storyboard_episode') // Ändere die Tabelle zu storyboard_episode
            .select('episode') // Alle Episoden abrufen
            .eq('season_id', season) // Filtere nach der angegebenen Saison-ID
            .order('episode', { ascending: true }); // Nach Episode aufsteigend sortieren

        if (error) throw error; // Bei Fehlern eine Ausnahme auslösen

        res.json(data || []); // Sicherstellen, dass immer eine Liste zurückgegeben wird
    } catch (err) {
        console.error("Fehler beim Abrufen der Episoden:", err.message);
        res.status(500).json({ error: err.message }); // Fehlerbehandlung
    }
});




// API-Route: Alle Szenen einer Episode abrufen
// API-Route: Alle Szenen einer Episode abrufen
// API-Route: Szenen einer Episode abrufen
// API-Route: Alle Szenen einer Episode abrufen
app.get("/api/scenes/:season/:episode", async (req, res) => {
    const { season, episode } = req.params;

    if (!season || !episode) {
        return res.status(400).json({ error: "Season und Episode sind erforderlich" });
    }

    try {
        // Zuerst die season_id basierend auf der Staffel abrufen
        const { data: seasonData, error: seasonError } = await supabase
            .from('storyboard_episode')
            .select('id')
            .eq('season_id', season)
            .eq('episode', episode)
            .single();

        if (seasonError || !seasonData) {
            return res.status(404).json({ error: "Episode nicht gefunden." });
        }

        const episodeId = seasonData.id;

        // Supabase-Abfrage, um alle Szenen für die gegebene Episode abzurufen
        const { data, error } = await supabase
            .from('storyboard_scene') // Ändere die Tabelle zu storyboard_scene
            .select('id, scene') // Stelle sicher, dass die Szene ID und die Beschreibung zurückgegeben werden
            .eq('episode_id', episodeId) // Filtere nach der Episode-ID
            .order('id', { ascending: true }); // Nach Szene-ID aufsteigend sortieren

        if (error) throw error;
        res.json(data || []); // Falls keine Daten, leere Liste zurückgeben
    } catch (err) {
        console.error("Fehler beim Abrufen der Szenen:", err.message);
        res.status(500).json({ error: err.message });
    }
});



// API-Route: Bilder einer Szene abrufen
app.get("/api/images/:season/:episode/:scene", async (req, res) => {
    const { season, episode, scene } = req.params;

    if (!season || !episode || !scene) {
        return res.status(400).json({ error: "Season, Episode und Scene sind erforderlich" });
    }

    try {
        // Zuerst die scene_id basierend auf der Szene abrufen
        const { data: sceneData, error: sceneError } = await supabase
            .from('storyboard_scene')
            .select('id')
            .eq('episode_id', episode) // Hier muss die episode_id aus der vorherigen API abgerufen werden
            .eq('scene', scene)
            .single();

        if (sceneError || !sceneData) {
            return res.status(404).json({ error: "Szene nicht gefunden." });
        }

        const sceneId = sceneData.id;

        // Supabase-Abfrage, um alle Bilder für die gegebene Szene abzurufen
        const { data, error } = await supabase
            .from('storyboard_image') // Ändere die Tabelle zu storyboard_image
            .select('image_path, download_url')
            .eq('scene_id', sceneId) // Filtere nach der Szene-ID
            .order('image_path', { ascending: true }); // Sortiere Bilder nach Pfad

        if (error) throw error;
        res.json(data || []);
    } catch (err) {
        console.error("Fehler beim Abrufen der Bilder:", err.message);
        res.status(500).json({ error: err.message });
    }
});

// API-Route: Szenentitel abrufen
app.get("/api/scene-title/:season/:episode/:scene", async (req, res) => {
    const { season, episode, scene } = req.params;

    if (!season || !episode || !scene) {
        return res.status(400).json({ error: "Season, Episode und Scene sind erforderlich" });
    }

    try {
        // Zuerst die scene_id abrufen
        const { data: sceneData, error: sceneError } = await supabase
            .from('storyboard_scene')
            .select('id, scene') // Hier könnte auch ein Titel gespeichert sein
            .eq('episode_id', episode)
            .eq('scene', scene)
            .single(); // Nur ein Ergebnis erwarten

        if (sceneError || !sceneData) {
            return res.status(404).json({ error: "Szene nicht gefunden." });
        }

        res.json(sceneData || { title: `Szene ${scene}` }); // Falls keine Szene existiert, Standardwert
    } catch (err) {
        console.error("Fehler beim Abrufen des Szenentitels:", err.message);
        res.status(500).json({ error: err.message });
    }
});


// Verbindung zur SQLite-Datenbank für Charaktere
// const dbCharacters = new sqlite3.Database("./character/character.db", (err) => {
//     // ...
// 
//     if (err) {
//         console.error("Fehler beim Verbinden mit der Datenbank:", err.message);
//     } else {
//         console.log("Verbunden mit SQLite-Datenbank.");
//     }
// });

// API-Route: Alle Charaktere abrufen
// API-Route: Alle Charaktere abrufen
app.get("/api/characters", async (req, res) => {
    try {
        // Supabase-Abfrage, um alle Charaktere und ihre Fähigkeiten abzurufen
        const { data, error } = await supabase
            .from('characters') // Name der Tabelle
            .select(`
                id,
                name,
                biography,
                strengths,
                relationships,
                image_url,
                art_url,
                music_theme,
                preferencesList,
                origin,
                dreamDesireGoal,
                connection,
                age,
                attributesList,
                abilities (ability_name, description) // Unterabfrage für Fähigkeiten
            `);

        if (error) throw error; // Bei Fehlern eine Ausnahme auslösen

        res.json(data); // Antwort mit den abgerufenen Daten
    } catch (err) {
        res.status(500).json({ error: err.message }); // Fehlerbehandlung
    }
});


// API-Route: Charaktere suchen
// API-Route: Charaktere nach Name suchen
// API-Route: Charaktere suchen
app.get("/api/search", async (req, res) => {
    const search = req.query.search || '';
    try {
        // Supabase-Abfrage, um Charaktere mit dem Namen, der "like" dem Suchbegriff entspricht, abzurufen
        const { data, error } = await supabase
            .from('characters') // Name der Tabelle
            .select('*') // Alle Felder auswählen
            .ilike('name', `%${search}%`); // "ilike" für eine case-insensitive Suche

        if (error) throw error; // Bei Fehlern eine Ausnahme auslösen

        res.json(data); // Antwort mit den abgerufenen Daten
    } catch (err) {
        res.status(500).json({ error: err.message }); // Fehlerbehandlung
    }
});


// Beispiel für Express.js
// API-Route: Charaktere nach ID abrufen
app.get('/api/characters/:id', async (req, res) => {
    const characterId = req.params.id;
    try {
        // Zuerst den Charakter abrufen
        const { data: character, error: characterError } = await supabase
            .from('characters')
            .select('*')
            .eq('id', characterId)
            .single(); // Wir erwarten nur ein Ergebnis

        if (characterError) throw characterError; // Bei Fehlern eine Ausnahme auslösen
        if (!character) {
            return res.status(404).json({ error: 'Charakter nicht gefunden' });
        }

        // Dann die Fähigkeiten abrufen
        const { data: abilities, error: abilitiesError } = await supabase
            .from('abilities')
            .select('ability_name, description')
            .eq('character_id', characterId);

        if (abilitiesError) throw abilitiesError; // Bei Fehlern eine Ausnahme auslösen

        // Sende sowohl Charakter als auch Fähigkeiten zurück
        res.json({ character, abilities });
    } catch (err) {
        res.status(500).json({ error: err.message }); // Fehlerbehandlung
    }
});



//story

// Verbindung zur SQLite-Datenbank für die Story
// const dbStory = new sqlite3.Database("./story/org.db", sqlite3.OPEN_READONLY, (err) => {
//     if (err) {
//         console.error("Fehler beim Verbinden mit der Story-Datenbank:", err.message);
//     } else {
//         console.log("Erfolgreich mit der Story-Datenbank verbunden.");
//     }
// });

// API-Route: Alle Seasons aus der Story-Datenbank abrufen
// API-Route: Alle Seasons aus der Story-Datenbank abrufen
// API-Route: Alle Seasons aus der Story-Datenbank abrufen
app.get("/api/seasons-story", async (req, res) => {
    const { data, error } = await supabase.rpc('distinct_seasons'); // RPC-Aufruf

    if (error) {
        console.error('Fehler beim Abrufen der Seasons:', error);
        return res.status(500).json({ error: error.message });
    }

    res.json(data);
});




// API-Route: Alle Episoden einer Season abrufen
// API-Route: Alle Episoden einer Season aus der Story-Datenbank abrufen
app.get("/api/episodes-story/:season", async (req, res) => {
    const { season } = req.params;

    try {
        // Supabase-Abfrage, um alle Episoden für die angegebene Season abzurufen
        const { data, error } = await supabase
            .from('story') // Name der Tabelle
            .select('episode') // Wir möchten die Episode abrufen
            .eq('season', season); // Bedingung für die Season

        if (error) throw error; // Bei Fehlern eine Ausnahme auslösen

        // Sicherstellen, dass nur einzigartige Episoden zurückgegeben werden
        const uniqueEpisodes = [...new Set(data.map(item => item.episode))].map(episode => ({ episode }));

        res.json(uniqueEpisodes); // Antwort mit den abgerufenen einzigartigen Episoden
    } catch (err) {
        res.status(500).json({ error: err.message }); // Fehlerbehandlung
    }
});





// API-Route: Alle Szenen einer Episode abrufen
// API-Route: Alle Szenen einer Episode aus der Story-Datenbank abrufen
app.get("/api/scenes-story/:season/:episode", async (req, res) => {
    const { season, episode } = req.params;

    try {
        // Supabase-Abfrage, um die Szenen für die angegebene Season und Episode abzurufen
        const { data, error } = await supabase
            .from('story') // Name der Tabelle
            .select('scene_number, word_link') // Nur die Spaltennamen angeben
            .eq('season', season) // Bedingung für die Season
            .eq('episode', episode); // Bedingung für die Episode

        if (error) throw error; // Bei Fehlern eine Ausnahme auslösen

        // Falls keine Daten gefunden wurden
        if (!data || data.length === 0) {
            return res.status(404).json({ error: 'Keine Szenen gefunden für die angegebene Staffel und Episode.' });
        }

        // Manuelle Umbenennung der Spalte `scene_number` zu `scene`
        const transformedData = data.map(scene => ({
            scene: scene.scene_number,
            word_link: scene.word_link
        }));

        console.log("Daten aus der API:", transformedData); // Debugging der abgerufenen Daten
        res.json(transformedData); // Antwort mit den umbenannten Daten
    } catch (err) {
        res.status(500).json({ error: err.message }); // Fehlerbehandlung
    }
});








// Server starten
app.listen(PORT, () => {
    console.log(`Server läuft auf http://localhost:${PORT}`); // Backticks für Template-String
});
