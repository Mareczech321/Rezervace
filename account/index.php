<?php

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    function registrace(){
        global $pdo;

        if (isset($_POST['register'], $_POST['user-reg'], $_POST['pass-reg'], $_POST['email-reg'])) {

            $password = password_hash($_POST['pass-reg'], PASSWORD_BCRYPT);

            $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->execute([$_POST['user-reg'], $_POST['email-reg'], $password]);
            
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            $_SESSION['user_id'] = $user['ID'];
            $_SESSION['username'] = $user['username'];
            header("Location: ./");
            exit;
        }
    }

    function prihlaseni() {
        global $pdo;

        if (isset($_POST['login'], $_POST['username'], $_POST['heslo'])) {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->execute([$_POST['username']]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($_POST['heslo'], $user['password'])) {
                $_SESSION['user_id'] = $user['ID'];
                $_SESSION['username'] = $user['username'];
                header("Location: index.php");
                exit();
            } else {
                echo "<p style='color:red;'>Neplatné uživatelské jméno nebo heslo.</p>";
            }
            header("Location: ./");
            exit;
        }
        if (isset($_SESSION['user_id']) && !isset($_SESSION['username'])) {
            $stmt = $pdo->prepare("SELECT username FROM users WHERE id = ?");
            $stmt->execute([$_SESSION['user_id']]);
            $user = $stmt->fetch();
            if ($user) {
                $_SESSION['username'] = $user['username'];
            }
        }
    }   
?>