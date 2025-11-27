<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $password2 = $_POST['password2'];

    verify_csrf_token($_POST['csrf_token'] ?? '');

    if (empty($username) || empty($password) || empty($password2)) {
        $_SESSION['register_error'] = "Vyplňte všechna pole!";
        $_SESSION['register_data'] = ['username' => $username];
        header("Location: index.php");
        exit;
    } elseif ($password !== $password2) {
        $_SESSION['register_error'] = "Hesla se neshodují!";
        $_SESSION['register_data'] = ['username' => $username];
        header("Location: index.php");
        exit;
    } elseif (strlen($password) < 6) {
        $_SESSION['register_error'] = "Heslo musí mít alespoň 6 znaků!";
        $_SESSION['register_data'] = ['username' => $username];
        header("Location: index.php");
        exit;
    } else {
        // Kontrola zda uživatel existuje
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $_SESSION['register_error'] = "Uživatel už existuje!";
            $_SESSION['register_data'] = ['username' => $username];
            header("Location: index.php");
            exit;
        } else {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            $stmt->bind_param("ss", $username, $password_hash);

            if ($stmt->execute()) {
                $_SESSION['register_success'] = "Registrace byla úspěšná! <a href='login.php' style='color:#f25a5a;'>Přihlaste se zde</a>.";
                header("Location: index.php");
                exit;
            } else {
                $_SESSION['register_error'] = "Chyba při registraci.";
                $_SESSION['register_data'] = ['username' => $username];
                header("Location: index.php");
                exit;
            }
        }
    }
} else {
    header("Location: index.php");
    exit;
}
?>
