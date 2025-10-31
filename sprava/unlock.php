<?php

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }


    function unlock(){
        global $pdo;

        if (isset($_POST['unlockFR'])){
            $pass = $_POST['unlock_rez'];
            $id = $_POST['rez_id'];

            $stmt = $pdo->prepare("SELECT * FROM rezervace WHERE id = ?");
            $stmt->execute([$id]);
            $rezervace = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($pass == $rezervace['heslo']){
                $_SESSION['unlock'] = [true, $id];
            }else {
                $_SESSION['unlock'] = [false, $id];
            }
            
            header("Location: ./");
            exit;            
        }
    }

?>