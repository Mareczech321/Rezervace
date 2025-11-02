<?php

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }


    function unlock(){
        global $pdo;

        if (isset($_POST['unlockFR'], $_POST['rez_id'], $_POST['unlock_rez'])) {
            $id = $_POST['rez_id'];
            $heslo = trim($_POST['unlock_rez']);

            $stmt = $pdo->prepare("SELECT heslo FROM rezervace WHERE id = ?");
            $stmt->execute([$id]);
            $rez = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($rez && $rez['heslo'] === $heslo) {
                $_SESSION['unlock'] = [true, $id];
                $_SESSION['msg'] = "Rezervace #{$id} byla odemknuta pro úpravy.";
                $_SESSION['error'] = false;
            } else {
                $_SESSION['msg'] = "Špatné heslo pro rezervaci #{$id}!";
                $_SESSION['error'] = true;
            }

            header("Location: ./");
            exit;
        }

        if (isset($_POST['upravit-bez-hesla'], $_POST['rez_id'])) {
            $id = $_POST['rez_id'];
            $_SESSION['unlock'] = [true, $id];
            $_SESSION['msg'] = "Rezervace #{$id} byla otevřena pro úpravy.";
            $_SESSION['error'] = false;
            header("Location: ./");
            exit;
        }
    }

?>