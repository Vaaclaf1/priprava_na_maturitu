<?php
session_start();
require 'config.php';

// Pokud uživatel není přihlášen, přesměruj na login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Získání dat uživatele z DB
$stmt = $conn->prepare("SELECT username FROM users WHERE id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$stmt->bind_result($username);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="style/style.css">
</head>
<body>
    <main>
        <section>
            <div class="form">
                <div class="form-header">
                    <img src="images/apexlogo.png" alt="LogoApex" style="width:150px; height:auto; display:block;">
                    <h2>Dashboard</h2>
                </div>

                <div class="form-main">
                    <p>Vítej, <strong><?php echo htmlspecialchars($username); ?></strong>!</p>
                    <p>Toto je tvůj dashboard. Můžeš zde přidat další obsah pro přihlášené uživatele.</p>
                </div>

                <div class="form-buttons">
                    <a href="logout.php">
                        <button type="button">Odhlásit se</button>
                    </a>
                </div>
            </div>
        </section>
    </main>
</body>
</html>
