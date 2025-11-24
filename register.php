<?php
require 'config.php';

$username = trim($_POST['username']);
$password = $_POST['password'];
$password2 = $_POST['password2'];

if (empty($username) || empty($password) || empty($password2)) {
    die("Vyplňte všechna pole!");
}

if ($password !== $password2) {
    die("Hesla se neshodují!");
}

if (strlen($password) < 6) {
    die("Heslo musí mít alespoň 6 znaků!");
}

// Kontrola zda uživatel existuje
$stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    die("Uživatel už existuje!");
}

$password_hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
$stmt->bind_param("ss", $username, $password_hash);

if ($stmt->execute()) {
    header("Location: login.html?success=1");
    exit;
} else {
    echo "Chyba při registraci.";
}
?>
