<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Načtení údajů uživatele
$stmt = $conn->prepare("SELECT username, avatar FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($username, $avatar);
$stmt->fetch();
$stmt->close();

// Zpracování změny username
if (isset($_POST['change_username'])) {
    $new_username = trim($_POST['new_username']);
    if (!empty($new_username)) {
        $stmt = $conn->prepare("UPDATE users SET username = ? WHERE id = ?");
        $stmt->bind_param("si", $new_username, $user_id);
        $stmt->execute();
        $stmt->close();
        header("Location: dashboard.php");
        exit;
    }
}

// Zpracování změny hesla
if (isset($_POST['change_password'])) {
    $current_pass = $_POST['current_password'];
    $new_pass = $_POST['new_password'];
    $confirm_pass = $_POST['confirm_password'];

    // Načteme hash hesla z DB
    $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($hashed_password);
    $stmt->fetch();
    $stmt->close();

    if (password_verify($current_pass, $hashed_password)) {
        if ($new_pass === $confirm_pass && strlen($new_pass) >= 6) {
            $new_hash = password_hash($new_pass, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmt->bind_param("si", $new_hash, $user_id);
            $stmt->execute();
            $stmt->close();
            $pass_message = "Heslo bylo úspěšně změněno!";
        } else {
            $pass_message = "Nová hesla se neshodují nebo jsou příliš krátká.";
        }
    } else {
        $pass_message = "Špatné aktuální heslo.";
    }
}

// Zpracování změny avatara
if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === 0) {
    $avatar_tmp = file_get_contents($_FILES['avatar']['tmp_name']);
    $stmt = $conn->prepare("UPDATE users SET avatar = ? WHERE id = ?");
    $null = NULL;
    $stmt->bind_param("bi", $null, $user_id);
    $stmt->send_long_data(0, $avatar_tmp);
    $stmt->execute();
    $stmt->close();
    header("Location: dashboard.php");
    exit;
}
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
        <div class="form" style="max-width:700px; padding:2rem;">
            <div class="form-header">
                <h2>Dashboard</h2>
            </div>

            <!-- Profil -->
            <div class="profile" style="display:flex; align-items:center; gap:1rem; margin-bottom:2rem;">
                <?php if ($avatar): ?>
                    <img src="data:image/png;base64,<?= base64_encode($avatar) ?>" alt="Avatar" style="width:100px; border-radius:50%;">
                <?php else: ?>
                    <img src="images/default_avatar.png" alt="Default Avatar" style="width:100px; border-radius:50%;">
                <?php endif; ?>
                <h3><?= htmlspecialchars($username) ?></h3>
            </div>

            <!-- Změna username -->
            <form method="POST">
                <label for="new_username">Změnit nick:</label>
                <input type="text" name="new_username" placeholder="Nový nick" required>
                <button type="submit" name="change_username">Uložit nick</button>
            </form>

            <!-- Změna hesla -->
            <form method="POST" style="margin-top:1rem;">
                <label for="current_password">Aktuální heslo:</label>
                <input type="password" name="current_password" placeholder="Současné heslo" required>
                <label for="new_password">Nové heslo:</label>
                <input type="password" name="new_password" placeholder="Nové heslo" required>
                <label for="confirm_password">Potvrzení nového hesla:</label>
                <input type="password" name="confirm_password" placeholder="Potvrzení" required>
                <button type="submit" name="change_password">Změnit heslo</button>
                <?php if (!empty($pass_message)) echo "<p>$pass_message</p>"; ?>
            </form>

            <!-- Změna avatara -->
            <form action="" method="POST" enctype="multipart/form-data" style="margin-top:1rem;">
                <label for="avatar">Změnit avatar:</label>
                <input type="file" name="avatar" accept="image/*" required>
                <button type="submit">Nahrát avatar</button>
            </form>

            <!-- Odhlášení -->
            <div style="margin-top:2rem;">
                <a href="logout.php" style="color:#f25a5a; text-decoration:none;">Odhlásit se</a>
            </div>
        </div>
    </section>
</main>
</body>
</html>
