<?php
$host = "localhost";
$user = "root";      // uprav podle hostingu
$pass = "";          // uprav podle hostingu
$db = "apex_users";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
