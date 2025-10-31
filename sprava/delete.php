<?php 

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    function delete() {
        global $pdo;

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_POST['smazat'], $_POST['id']) || isset($_POST['smazat-form'], $_POST['rez_id'])) {
            $id = isset($_POST['id']) ? $_POST['id'] : $_POST['rez_id'];

            $check = $pdo->prepare("SELECT heslo FROM rezervace WHERE id = ?");
            $check->execute([$id]);
            $rezervace = $check->fetch(PDO::FETCH_ASSOC);

            if ($rezervace && !empty($rezervace['heslo'])) {
                $_SESSION['msg'] = "Rezervace #{$id} je chráněná heslem a nelze ji smazat!";
                $_SESSION['error'] = true;
                session_write_close();
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