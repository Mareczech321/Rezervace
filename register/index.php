<?php

    function registrace($username, $password, $email){

        global $pdo;

        $password = password_hash($password, PASSWORD_BCRYPT);

        $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$username, $email, $password]);
    }
    
?>