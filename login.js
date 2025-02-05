document.getElementById("login-form").addEventListener("submit", function (event) {
    event.preventDefault(); // Verhindert das Neuladen der Seite

    // Eingabe des Passworts
    const password = document.getElementById("password").value;

    // Überprüfung des Passworts
    if (password === "FteamP") {
        // Weiterleitung zur anderen Seite
        window.location.href = "indexteam.html"; // Ändere dies zur gewünschten Zielseite
    } else {
        // Fehlermeldung anzeigen
        document.getElementById("error-message").textContent = "Falsches Passwort!";
        document.getElementById("error-message").style.display = "block";
    }
});