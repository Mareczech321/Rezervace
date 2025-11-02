<?php 

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    function edit() {
        global $pdo;

        if (isset($_POST['cancel'])) {
            $_SESSION['unlock'] = [false, 0];
            header("Location: ./");
            exit;
        }

        if (isset($_POST['edit-id'])) {
            $id = $_POST['edit-id'];

            $stmt = $pdo->prepare("SELECT heslo FROM rezervace WHERE id = ?");
            $stmt->execute([$id]);
            $rez = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($rez && empty($rez['heslo'])) {
                $_SESSION['unlock'] = [true, $id];
            }
        

            if ($rez && (empty($rez['heslo']) || (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $rez['id_uzivatele']))) {
                $id = $_SESSION['unlock'][1];

                $stmt = $pdo->prepare("SELECT heslo FROM rezervace WHERE id = ?");
                $stmt->execute([$id]);
                $rez = $stmt->fetch(PDO::FETCH_ASSOC);

                if (empty($rez['heslo']) || (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $rez['id_uzivatele'])) {
                    $_SESSION['unlock'] = [true, $id];
                } else {
                    $_SESSION['msg'] = "Nemáte oprávnění upravovat tuto rezervaci!";
                    $_SESSION['error'] = true;
                    header("Location: ./");
                    exit;
                }
            }

            $mistnost_id = $_POST['edit-room'] ?? null;
            $datum = $_POST['edit-date'] ?? null;
            $zacatek = $_POST['edit-zacatek'] ?? null;
            $konec = $_POST['edit-konec'] ?? null;

            if (empty($mistnost_id) || empty($datum) || empty($zacatek) || empty($konec)) {
                $_SESSION['msg'] = "Vyplňte prosím všechna pole pro úpravu rezervace.";
                $_SESSION['error'] = true;
                header("Location: ./");
                exit;
            }

            if ($konec <= $zacatek) {
                $_SESSION['msg'] = "Konec musí být později než začátek!";
                $_SESSION['error'] = true;
                header("Location: ./");
                exit;
            }

            $stmt = $pdo->prepare("SELECT COUNT(*) FROM rezervace 
                                WHERE mistnost_id = ? 
                                AND datum = ? 
                                AND id != ? 
                                AND ((zacatek < ? AND konec > ?) 
                                OR (zacatek < ? AND konec > ?) 
                                OR (zacatek >= ? AND konec <= ?))");
            $stmt->execute([$mistnost_id, $datum, $id, $konec, $zacatek, $zacatek, $konec, $zacatek, $konec]);
            $obsazeno = $stmt->fetchColumn();

            if ($obsazeno > 0) {
                $_SESSION['msg'] = "Místnost je v tomto časovém úseku již rezervovaná!";
                $_SESSION['error'] = true;
                header("Location: ./");
                exit;
            }

            $stmt = $pdo->prepare("UPDATE rezervace 
                                SET mistnost_id = ?, datum = ?, zacatek = ?, konec = ?
                                WHERE id = ?");
            $stmt->execute([$mistnost_id, $datum, $zacatek, $konec, $id]);

            $_SESSION['unlock'] = [false, 0];
            $_SESSION['msg'] = "Rezervace #{$id} byla úspěšně upravena!";
            $_SESSION['error'] = false;
            header("Location: ./");
            exit;
        }
    }

?>