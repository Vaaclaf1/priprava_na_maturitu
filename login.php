<?php
require 'config.php';
session_start();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $hashed_password);
    $stmt->fetch();

    if ($stmt->num_rows === 1 && password_verify($password, $hashed_password)) {
        $_SESSION['user_id'] = $id;
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Špatné jméno nebo heslo!";
    }
}
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style/style.css">
</head>
<body>
<main>
    <section>
        <div class="form">
            <div class="form-header">
                <img src="images/apexlogo.png" alt="LogoApex">
                <h2>Login</h2>
            </div>

            <?php if ($error) echo "<p style='color:red;'>$error</p>"; ?>

            <form method="POST">
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit">Login</button>
            </form>

            <div class="form-footer">
                <a href="index.php">Don't have an account? Register</a>
            </div>
        </div>
    </section>
</main>
</body>
</html>
