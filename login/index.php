<?php
    function prihlaseni($username, $password) {
        global $pdo;

        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['ID'];
            $_SESSION['username'] = $user['username'];
            header("Location: index.php");
            exit();
        } else {
            echo "<p style='color:red;'>Neplatné uživatelské jméno nebo heslo.</p>";
        }
    }   
?>
