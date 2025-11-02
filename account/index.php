<?php

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    function registrace(){
        global $pdo;

        if (isset($_POST['register'], $_POST['user-reg'], $_POST['pass-reg'], $_POST['email-reg'])) {
            $username = trim($_POST['user-reg']);
            $email = trim($_POST['email-reg']);
            $password = password_hash($_POST['pass-reg'], PASSWORD_BCRYPT);

            $check = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ? OR email = ?");
            $check->execute([$username, $email]);
            if ($check->fetchColumn() > 0) {
                $_SESSION['msg'] = "Uživatel s tímto jménem nebo e-mailem již existuje.";
                $_SESSION['error'] = true;
                header("Location: ./");
                exit;
            }
            
            try {
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->execute([$username, $email, $password]);

            $_SESSION['user_id'] = $pdo->lastInsertId();
            $_SESSION['username'] = $username;

            $_SESSION['msg'] = "Registrace byla úspěšná. Jste přihlášen jako <b>{$username}</b>.";
            $_SESSION['error'] = false;
            header("Location: ./");
            exit;
        } catch (PDOException $e) {
            $_SESSION['msg'] = "Došlo k chybě při registraci: " . $e->getMessage();
            $_SESSION['error'] = true;
            header("Location: ./");
            exit;
        }
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