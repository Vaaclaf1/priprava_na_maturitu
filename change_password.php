<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) exit("Nepřihlášen!");

$id = $_SESSION['user_id'];
$old = $_POST['old_password'];
$new = $_POST['new_password'];

// staré heslo
$stmt = $conn->prepare("SELECT password FROM users WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($hash);
$stmt->fetch();

if (!password_verify($old, $hash)) {
    die("Staré heslo je špatně!");
}

$newHash = password_hash($new, PASSWORD_DEFAULT);

$stmt = $conn->prepare("UPDATE users SET password=? WHERE id=?");
$stmt->bind_param("si", $newHash, $id);
$stmt->execute();

header("Location: dashboard.php");
exit;
?>
