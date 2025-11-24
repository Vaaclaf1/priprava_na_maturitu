<?php
$host = "localhost";
$user = "root";
$pass = "root";
$db = "apex_users";

$conn = new mysqli($host, $user, $pass, $db);
$conn->set_charset("utf8mb4");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
