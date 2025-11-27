<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === 0) {
    $avatar_tmp = file_get_contents($_FILES['avatar']['tmp_name']);

    $stmt = $conn->prepare("UPDATE users SET avatar = ? WHERE id = ?");
    $null = NULL;
    $stmt->bind_param("bi", $null, $user_id);
    $stmt->send_long_data(0, $avatar_tmp);

    if ($stmt->execute()) {
        header("Location: dashboard.php?avatar_updated=1");
        exit;
    } else {
        echo "Chyba při nahrávání avatara: " . $stmt->error;
    }
} else {
    echo "Nepodařilo se načíst soubor.";
}
?>
