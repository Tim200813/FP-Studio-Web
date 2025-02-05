<?php
// PHPMailer und erforderliche Klassen einbinden
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Prüfen, ob das Formular gesendet wurde
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Eingaben abrufen und sicherstellen, dass sie korrekt sind
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $subject = htmlspecialchars($_POST['subject']);
    $message = htmlspecialchars($_POST['message']);

    $mail = new PHPMailer(true);

    try {
        // SMTP konfigurieren
        $mail->isSMTP();  // SMTP verwenden
        $mail->Host       = 'smtp.gmail.com'; // Google SMTP-Server
        $mail->SMTPAuth   = true; // SMTP-Authentifizierung aktivieren
        $mail->Username   = 'minecrafter14510@gmail.com'; // Deine Gmail-Adresse
        $mail->Password   = 'wicn ftly wqcf dblc'; // Das Google App-Passwort
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // TLS-Verschlüsselung aktivieren
        $mail->Port       = 587; // TCP-Port für die Verbindung

        // Absender und Empfänger
        $mail->setFrom('minecrafter14510@gmail.com', $name); // Absendername und -adresse
        $mail->addAddress('minecrafter14510@gmail.com', 'Admin'); // Empfänger-Adresse

        // Nachricht
        $mail->isHTML(true); // HTML-Nachricht
        $mail->Subject = $subject; // Betreff der E-Mail

        // Modernes E-Mail-Design
        $mail->Body = "
        <html>
        <head>
            <style>
                body {
                    font-family: 'Arial', sans-serif;
                    background-color: #f7f7f7;
                    color: #333;
                    margin: 0;
                    padding: 0;
                }
                .email-container {
                    max-width: 600px;
                    margin: 20px auto;
                    background: #ffffff;
                    border-radius: 12px;
                    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
                    overflow: hidden;
                }
                .email-header {
                    background: linear-gradient(135deg, #6a11cb, #2575fc);
                    color: #fff;
                    padding: 20px;
                    text-align: center;
                }
                .email-header h1 {
                    margin: 0;
                    font-size: 24px;
                    font-weight: bold;
                }
                .email-content {
                    padding: 20px;
                }
                .email-content p {
                    margin: 10px 0;
                    font-size: 16px;
                    line-height: 1.6;
                }
                .email-content strong {
                    color: #007BFF;
                }
                .email-footer {
                    background: #f4f4f4;
                    padding: 15px;
                    text-align: center;
                    font-size: 14px;
                    color: #666;
                }
            </style>
        </head>
        <body>
            <div class='email-container'>
                <div class='email-header'>
                    <h1>Neue Nachricht von $name</h1>
                </div>
                <div class='email-content'>
                    <p><strong>E-Mail:</strong> $email</p>
                    <p><strong>Betreff:</strong> $subject</p>
                    <p><strong>Nachricht:</strong></p>
                    <p>$message</p>
                </div>
                <div class='email-footer'>
                    <p>Diese E-Mail wurde über das Kontaktformular gesendet.</p>
                </div>
            </div>
        </body>
        </html>
        "; // E-Mail-Inhalt

        // E-Mail senden
        if ($mail->send()) {
            echo "✅ Nachricht erfolgreich gesendet!";
        } else {
            echo "❌ Nachricht konnte nicht gesendet werden. Fehler: {$mail->ErrorInfo}"; // Fehlerbehandlung
        }
    } catch (Exception $e) {
        echo "❌ Nachricht konnte nicht gesendet werden. Fehler: {$mail->ErrorInfo}"; // Fehlerbehandlung
    }
} else {
    echo "❌ Ungültige Anfrage."; // Wenn das Formular nicht gesendet wurde
}
?>