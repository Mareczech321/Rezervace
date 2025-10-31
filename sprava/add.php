<?php 

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    function add(){
        global $pdo;

        if (isset($_POST['pridat'], $_POST['id_mistnosti'], $_POST['jmeno'], $_POST['zacatek'], $_POST['konec'], $_POST['datum'])) {
            $id_mistnosti = $_POST['id_mistnosti'];
            $cele_jmeno = trim($_POST['jmeno']);
            $parts = explode(" ", $cele_jmeno, 2);
            $jmeno = $parts[0];
            $prijmeni = isset($parts[1]) ? $parts[1] : '';
            $zacatek = $_POST['zacatek'];
            $konec = $_POST['konec'];
            $datum = $_POST['datum'];

            $stmt = $pdo->prepare("SELECT COUNT(*) FROM rezervace WHERE mistnost_id = ? AND datum = ? AND ((zacatek < ? AND konec > ?) OR (zacatek < ? AND konec > ?) OR (zacatek >= ? AND konec <= ?))");

            $stmt->execute([$id_mistnosti, $datum, $konec, $zacatek, $zacatek, $konec, $zacatek, $konec]);
            $obsazeno = $stmt->fetchColumn();

            if ($obsazeno > 0) {
                $_SESSION['msg'] = "Místnost je v tomto časovém úseku již rezervovaná!";
                $_SESSION = true;
                header("Location: ./");
                exit;
            }

            if (empty($prijmeni)) {
                $_SESSION['msg'] = "Prosím zadejte i příjmení.";
                $_SESSION = true;
                header("Location: ./");
                exit;
            }

            if ($konec <= $zacatek) {
                $_SESSION['msg'] = "Konec musí být později než začátek!";
                $_SESSION = true;
                header("Location: ./");
                exit;
            }

            $datum_cas = DateTime::createFromFormat('Y-m-d H:i', $datum . ' ' . $zacatek);
            $now = new DateTime();

            if ($datum_cas < $now) {
                $_SESSION['msg'] = "Rezervace nemůže být v minulosti!";
                $_SESSION = true;
                header("Location: ./");
                exit;
            }

            if (isset($_POST["heslo_rezervace"])){
                $heslo_rez = $_POST["heslo_rezervace"];

                if (isset($_SESSION['user_id'])){
                    $stmt = $pdo->prepare("INSERT INTO rezervace (mistnost_id, jmeno_osoby, prijmeni_osoby, datum, zacatek, konec, ID_uzivatele, heslo) VALUES (?, ?, ?, ?, ?, ?, ?, ?)"); 
                    $stmt->execute([$id_mistnosti, $jmeno, $prijmeni, $datum, $zacatek, $konec, $_SESSION['user_id'], $heslo_rez]);
                }else{
                    $stmt = $pdo->prepare("INSERT INTO rezervace (mistnost_id, jmeno_osoby, prijmeni_osoby, datum, zacatek, konec, heslo) VALUES (?, ?, ?, ?, ?, ?, ?)"); 

                    $stmt->execute([$id_mistnosti, $jmeno, $prijmeni, $datum, $zacatek, $konec, $heslo_rez]);
                }
                $lastId = $pdo->lastInsertId();
                $_SESSION['msg'] = "Rezervace #{$lastId} byla úspěšně přidána!";
                header("Location: ./");
                exit;
            }

            if (isset($_SESSION['user_id'])){
                $stmt = $pdo->prepare("INSERT INTO rezervace (mistnost_id, jmeno_osoby, prijmeni_osoby, datum, zacatek, konec, ID_uzivatele) VALUES (?, ?, ?, ?, ?, ?, ?)"); 
                $stmt->execute([$id_mistnosti, $jmeno, $prijmeni, $datum, $zacatek, $konec, $_SESSION['user_id']]);
            }else {
                $stmt = $pdo->prepare("INSERT INTO rezervace (mistnost_id, jmeno_osoby, prijmeni_osoby, datum, zacatek, konec) VALUES (?, ?, ?, ?, ?, ?)");

            $stmt->execute([$id_mistnosti, $jmeno, $prijmeni, $datum, $zacatek, $konec]);
            }

            $lastId = $pdo->lastInsertId();
            $_SESSION['msg'] = "Rezervace #{$lastId} byla úspěšně přidána!";
            header("Location: ./");
            exit;
        }
    }


?>