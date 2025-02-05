<?php
require 'config.php'; // Stelle sicher, dass der Pfad zu config.php korrekt ist

// Debug: Prüfen, ob die Tabelle existiert
$tableCheck = $pdo->query("SELECT * FROM information_schema.tables WHERE table_name = 'test_images'")->fetchAll();
if (empty($tableCheck)) {
    die("❌ Tabelle 'test_images' existiert nicht!");
}

// Bild-URL aus der Datenbank abrufen
$query = $pdo->query("SELECT image_url FROM public.test_images LIMIT 1");
$image = $query->fetch(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test-Bild aus Supabase</title>
</head>
<body>
    <h1>Bild aus der Datenbank</h1>
    <?php if ($image): ?>
        <img src="<?= htmlspecialchars($image['image_url']) ?>" alt="Gespeichertes Bild" width="500">
    <?php else: ?>
        <p>Kein Bild gefunden!</p>
    <?php endif; ?>
</body>
</html>
