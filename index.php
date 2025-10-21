<?php

    include 'config/db.php';
    
    if (isset($_POST['pridat'], $_POST['id_mistnosti'], $_POST['jmeno'], $_POST['zacatek'], $_POST['konec'], $_POST['datum'])) {
    $id_mistnosti = $_POST['id_mistnosti'];
    $cele_jmeno = $_POST['jmeno'];
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
        die("Místnost je v tomto časovém úseku již rezervovaná!");
    }

    if (empty($prijmeni)) {
        die("Prosím zadejte i příjmení.");
    }

    if ($konec <= $zacatek) {
        die("Konec musí být později než začátek!");
    }

    $datum_cas = DateTime::createFromFormat('Y-m-d H:i', $datum . ' ' . $zacatek);
    $now = new DateTime();

    if ($datum_cas < $now) {
        die("Rezervace nemůže být v minulosti!");
    }

    $stmt = $pdo->prepare("INSERT INTO rezervace (mistnost_id, jmeno_osoby, prijmeni_osoby, datum, zacatek, konec) VALUES (?, ?, ?, ?, ?, ?)"); 

    $stmt->execute([$id_mistnosti, $jmeno, $prijmeni, $datum, $zacatek, $konec]);
    }

    if (isset($_POST['smazat'], $_POST['id'])) {
        $id = $_POST['id'];

        $stmt = $pdo->prepare("DELETE FROM rezervace WHERE id = ?"); 

        $stmt->execute([$id]);
    }
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rezervační systém</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Systém rezervování místností</h1>

        <nav>
            <a href="#rezervovane">Domů</a>
            <a href="#mistnosti">Místnosti</a>
            <a href="#rezervace">Správa</a> 
        </nav>
    </header>

    <section id="rezervovane">
        <?php 
        
            if (isset($_POST['pridat'])) {
                $lastId = $pdo->lastInsertId();
                echo "<p style='color:lime;'>Rezervace #{$lastId} byla úspěšně přidána!</p>";
            }
        if (isset($_POST['smazat'], $_POST['id'])) {
            echo "<p style='color:lime;'>Rezervace #" . htmlspecialchars($_POST['id']) . " byla úspěšně smazána!</p>";
        }
        
        ?>
        <h2>Rezervované místnosti</h2>
        <?php

            $reserved = 0;

            $stmt = $pdo->query("SELECT rezervace.id, mistnosti.nazev_mistnosti, DATE_FORMAT(rezervace.datum, '%d. %m.') AS datum_formatted, DATE_FORMAT(rezervace.zacatek, '%H:%i') AS zacatek, DATE_FORMAT(rezervace.konec, '%H:%i') AS konec, rezervace.prijmeni_osoby FROM rezervace JOIN mistnosti ON rezervace.mistnost_id = mistnosti.id ORDER BY rezervace.datum ASC, rezervace.zacatek ASC");

            $rezervace = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (count($rezervace) > 0) {
                echo "<table>";
                echo "<tr><th>ID</th><th>Místnost</th><th>Datum</th><th>Čas</th><th>Zarezervoval</th></tr>";
                foreach ($rezervace as $rezervaceItem) {
                    $rowClass = ($reserved % 2 == 0) ? 'odd' : 'even';
                    echo "<tr class='{$rowClass}'>";
                    echo "<td>" . htmlspecialchars($rezervaceItem['id']) . "</td>";
                    echo "<td>" . htmlspecialchars($rezervaceItem['nazev_mistnosti']) . "</td>";
                    echo "<td>" . htmlspecialchars($rezervaceItem['datum_formatted']) . "</td>";
                    echo "<td>" . htmlspecialchars($rezervaceItem['zacatek']) . " - " . htmlspecialchars($rezervaceItem['konec']) . "</td>";
                    echo "<td>" . htmlspecialchars($rezervaceItem['prijmeni_osoby']) . "</td>";
                    echo "</tr>";
                    $reserved++;
                }
                echo "</table>";
            } else {
                echo "<p>Žádné rezervace nebyly nalezeny.</p>";
            }

        
        ?>
    </section>
    <section id="mistnosti">
        <h2>Seznam místností</h2>
        <?php

            $rooms = 0;

            $stmt = $pdo->query("SELECT id, nazev_mistnosti, kapacita FROM mistnosti");
            $mistnosti = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (count($mistnosti) > 0) {
                echo "<table>";
                echo "<tr><th>ID místnosti</th><th>Název místnosti</th><th>Kapacita</th></tr>";
                foreach ($mistnosti as $mistnost) {
                    if ($rooms % 2 == 0) {
                        echo "<tr class='odd'>";
                    } else {
                        echo "<tr class='even'>";
                    }
                    echo "<td>" . htmlspecialchars($mistnost['id']) . "</td>";
                    echo "<td>" . htmlspecialchars($mistnost['nazev_mistnosti']) . "</td>";
                    echo "<td>" . htmlspecialchars($mistnost['kapacita']) . "</td>";
                    echo "</tr>";
                    $rooms++;
                }
                echo "</table>";
            } else {
                echo "<p>Žádné místnosti nebyly nalezeny.</p>";
            }

        ?>
    </section>
    <section id="rezervace">
        <h2>Správa rezervací</h2>
        <br><hr>
        <form method="POST" id="pridat">
            <h3>Přidat</h3>
            <div class="row">
                <div class="floating-label">
                    <select name="id_mistnosti" id="id_mistnosti" required>
                        <option value="" disabled selected>Vyber místnost</option>
                        <?php
                        $stmt = $pdo->query("SELECT id, nazev_mistnosti FROM mistnosti");
                        $mistnosti = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($mistnosti as $m) {
                            echo "<option value='{$m['id']}'>{$m['nazev_mistnosti']}</option>";
                        }
                        ?>
                    </select>
                    <label for="id_mistnosti">Místnost</label>
                </div>
                <div class="floating-label">
                    <input type="text" name="jmeno" id="jmeno" placeholder=" " required>
                    <label for="jmeno">Jméno a příjmení</label>
                </div>
            </div>

            <div class="row">
                <div class="floating-label">
                    <input type="time" name="zacatek" id="zacatek" step="60" placeholder=" " required>
                    <label for="zacatek">Od</label>
                </div>
                <div class="floating-label">
                    <input type="time" name="konec" id="konec" step="60" placeholder=" " required>
                    <label for="konec">Do</label>
                </div>
            </div>

            <div class="row">
                <div class="floating-label">
                    <input type="date" name="datum" id="datum" placeholder=" " required>
                    <label for="datum">Datum</label>
                </div>
                <input type="submit" value="Přidat rezervaci" name="pridat">
            </div>
            </form>

            <form method="POST" id="smazat">
                <h3>Smazat</h3>
                <div class="row">
                    <div class="floating-label">
                        <input type="number" name="id" id="id" placeholder=" " required>
                        <label for="id">ID rezervace</label>
                    </div>
                    <input type="submit" value="Smazat rezervaci" name="smazat">
                </div>
            </form>
    </section>
</body>
</html>