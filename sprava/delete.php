<?php 

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    function delete() {
        global $pdo;

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_POST['deleteUnlocked'])){
            $id = $_POST['deleteUnlocked'];

            $stmt = $pdo->prepare("DELETE FROM rezervace WHERE id = ?");
            $stmt->execute([$id]);

            $_SESSION['msg'] = "Rezervace #{$id} byla úspěšně smazána!";
            session_write_close();
            header("Location: ./");
            exit;
        }

        if (isset($_POST['smazat'], $_POST['id']) || isset($_POST['smazat-form'], $_POST['rez_id'])) {
            $id = isset($_POST['id']) ? $_POST['id'] : $_POST['rez_id'];

            $check = $pdo->prepare("SELECT heslo FROM rezervace WHERE id = ?");
            $check->execute([$id]);
            $rezervace = $check->fetch(PDO::FETCH_ASSOC);

            if ((isset($_SESSION['user_id']) && $_SESSION['user_id'] == $rezervace['id_uzivatele']) || empty($rezervace['heslo'])) {
                $stmt = $pdo->prepare("DELETE FROM rezervace WHERE id = ?");
                $stmt->execute([$id]);
                $_SESSION['msg'] = "Rezervace #{$id} byla úspěšně smazána!";
                $_SESSION['error'] = false;
                header("Location: ./");
                exit;
            }

            $stmt = $pdo->prepare("DELETE FROM rezervace WHERE id = ?");
            $stmt->execute([$id]);

            $_SESSION['msg'] = "Rezervace #{$id} byla úspěšně smazána!";
            session_write_close();
            header("Location: ./");
            exit;
        }
    }

?>