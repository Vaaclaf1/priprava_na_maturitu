<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) exit("Nepřihlášen!");

$id = $_SESSION['user_id'];
$new = trim($_POST['new_username']);

$stmt = $conn->prepare("UPDATE users SET username=? WHERE id=?");
$stmt->bind_param("si", $new, $id);
$stmt->execute();

header("Location: dashboard.php");
exit;
?>
