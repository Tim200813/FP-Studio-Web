const sqlite3 = require('sqlite3').verbose();
const db = new sqlite3.Database("./story/org.db");

db.all("SELECT * FROM story", [], (err, rows) => {
    if (err) {
        console.error("Fehler beim Abrufen der Daten:", err.message);
    } else {
        console.log("Daten in der story-Tabelle:", rows);
    }
    db.close();
});
