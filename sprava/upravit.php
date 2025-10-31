<?php 

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    function edit(){
        global $pdo;

        if (isset($_POST['cancel'])){
            $_SESSION['unlock'][0] = 0;
            header("Location: ./");
            exit;
        }

        if ($_SESSION['unlock'][0] && isset($_POST['unlockFR'])){
            $stmt = $pdo->prepare("SELECT * FROM rezervace WHERE id = ?");
            $stmt->execute([$_SESSION['unlock'][1]]);

            $edit = $stmt->fetch(PDO::FETCH_ASSOC);
        }
    }

?>