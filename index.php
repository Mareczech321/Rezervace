<?php

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    
    if (!isset($_SESSION['unlock'])) {
        $_SESSION['unlock'] = [false, 0];
    }

    if (!isset($_SESSION['error'])){
        $_SESSION['error'] = false;
    }

    include 'config/db.php';
    include 'account/index.php';
    include 'sprava/add.php';
    include 'sprava/delete.php';
    include 'sprava/unlock.php';
    include 'sprava/upravit.php';

    add();
    delete();

    registrace();
    prihlaseni();

    unlock();
    edit();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rezervační systém</title>
    <link rel="stylesheet" href="main.css?v=2">
    <link rel="shortcut icon" href="img/placeholder.png" type="image/x-icon">
</head>
<body>
    <div id="blur-overlay" onclick="zavrit()" style="display: none;"></div>
    <header>
        <div id="header-top">
            <h1>Systém rezervování místností</h1>
            <?php

                if (isset($_SESSION['user_id'])) {
                    echo "<button onclick=\"odhlasit()\" id=\"login\">{$_SESSION['username']} (odhlásit)</button>";
                } else {
                    echo "<button onclick=\"prihlaseni()\" id=\"login\">Přihlášení</button>";
                }

            ?>

        </div>
        
        <nav>
            <a href="#rezervovane">Domů</a>
            <a href="#mistnosti">Místnosti</a>
            <a href="#rezervace">Správa</a> 
        </nav>        
    </header>

    <section>
        <form method="POST" id="prihlaseni">         
            <button onclick="zavrit()" class="zavrit" style="position: absolute; top: 5px; background: none;">✕</button>   
            <h3 style="text-align: center;">Přihlášení</h3>
            <div class="row">
                <div class="floating-label">
                    <input type="text" name="username" id="username-login" placeholder=" ">
                    <label for="username-login">Uživatelské jméno</label>
                </div>
            </div>
            <div class="row">
                <div class="floating-label">
                    <input type="password" name="heslo" id="heslo-login" placeholder=" ">
                    <label for="heslo-login">Heslo</label>
                    <img src="./img/eye.png" alt="" onclick="showPass()" id="show">
                </div>
            </div>
            <div class="row" id="register-link">
                <a onclick="registrace()" id="create-acc">Nemáte účet? Zaregistrujte se zde.</a>
            </div>
            <div class='row'>
                <input type="submit" value="Přihlásit se" name="login" id="login-btn">
            </div>
        </form>

        <form method="POST" id="registrace">
            <button onclick="zavrit()" class="zavrit" style="position: absolute; top: 5px; background: none;">✕</button>
            <h3 style="text-align: center;">Registrace</h3>
            <div class="row">
                <div class="floating-label">
                    <input type="text" name="user-reg" id="user-reg" placeholder=" ">
                    <label for="user-reg">Uživatelské jméno</label>
                </div>
            </div>
            <div class="row">
                <div class="floating-label">
                    <input type="password" name="pass-reg" id="pass-reg" placeholder=" ">
                    <label for="pass-reg">Heslo</label>
                    <img src="./img/eye.png" alt="" onclick="showPass2()" id="show2">
                </div>
            </div>
            <div class="row">
                <div class="floating-label">
                    <input type="email" name="email-reg" id="email-reg" placeholder=" ">
                    <label for="email-reg">E-mail</label>
                </div>
            </div>
            <div class='row'>
                <input type="submit" value="Registrovat se" name="register" id="register-btn">
            </div>
        </form>
    

    <section id="rezervovane">
        <?php 
        
            if (isset($_SESSION['msg'])) {
                if ($_SESSION['error']){
                    echo "<p style='color: red;'>{$_SESSION['msg']}</p>";
                }else {
                    echo "<p style='color: lime;'>{$_SESSION['msg']}</p>";
                }
                $_SESSION['error'] = false;
                unset($_SESSION['msg']);
            }
        
        ?>
        <h2>Rezervované místnosti</h2>
        <?php

            $reserved = 0;

            $stmt = $pdo->query("
                                SELECT 
                                    rezervace.id, 
                                    mistnosti.nazev_mistnosti, 
                                    DATE_FORMAT(rezervace.datum, '%d. %m.') AS datum_formatted, 
                                    rezervace.datum AS datum_iso,
                                    DATE_FORMAT(rezervace.zacatek, '%H:%i') AS zacatek, 
                                    DATE_FORMAT(rezervace.konec, '%H:%i') AS konec, 
                                    rezervace.prijmeni_osoby, 
                                    rezervace.heslo 
                                FROM rezervace 
                                JOIN mistnosti ON rezervace.mistnost_id = mistnosti.id 
                                ORDER BY rezervace.datum ASC, rezervace.zacatek ASC
                            ");

            $rezervace = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (count($rezervace) > 0) {
                echo "<table id='seznam_rez'>";
                echo "<tr>
                        <th>ID</th>
                        <th>Místnost</th>
                        <th>Datum</th>
                        <th>Čas</th>
                        <th>Zarezervoval</th>
                        <th>Akce</th>
                      </tr>";

                foreach ($rezervace as $rezervaceItem) {
                    $rowClass = ($reserved % 2 == 0) ? 'odd' : 'even';

                    echo "<tr class='{$rowClass}' id='rezervace" . $rezervaceItem['id'] . "' ";

                    $unlocked = $_SESSION['unlock'][0] && $rezervaceItem['id'] == $_SESSION['unlock'][1];

                    if ($unlocked){
                        echo "style='background: red; color: white;'";
                    }                    
                    echo "><td>" . htmlspecialchars($rezervaceItem['id']) . "</td>";

                    if ($unlocked){
                        echo "
                            <form method='POST' id='edit-form'>
                                <input type='hidden' name='edit-id' value='{$rezervaceItem['id']}'>
                                <td>
                                    <div class='floating-label' style='background: #ffffff; border-radius: 6px;'>
                                        <select name='edit-room' id='edit-room' required>";
                                        
                                            $stmt = $pdo->query("SELECT id, nazev_mistnosti FROM mistnosti");
                                            $mistnosti = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                            foreach ($mistnosti as $m) {
                                                $selected = ($m['nazev_mistnosti'] === $rezervaceItem['nazev_mistnosti']) ? "selected" : "";
                                                echo "<option value='{$m['id']}' {$selected}>{$m['nazev_mistnosti']}</option>";
                                            }

                        echo           "</select>
                                    </div>
                                </td>                                    
                                <td>
                                    <input type='date' name='edit-date' value='" . htmlspecialchars($rezervaceItem['datum_iso']) . "' required>
                                </td>
                                <td>
                                    <input type='time' name='edit-zacatek' value='" . htmlspecialchars($rezervaceItem['zacatek']) . "' required>
                                    <input type='time' name='edit-konec' value='" . htmlspecialchars($rezervaceItem['konec']) . "' required>
                                </td>
                                <td>" . htmlspecialchars($rezervaceItem['prijmeni_osoby']) . "</td>
                                <td>
                                    <div class='uprava' id='edit-unlocked'>
                                        <input type='submit' value=' ' name='send-edit' title='Potvrdit změny' id='send-edit'>
                                        <input type='submit' value=' ' name='cancel' title='Zrušit změny' id='cancel'>
                                        <input type='submit' value=" . $rezervaceItem['id'] . " name='deleteUnlocked' title='Smazat rezervaci' id='deleteUnlocked'> 
                                    </div>
                                </td>
                            </form>
                        </tr>";
                    }else{
                        echo "<td>" . htmlspecialchars($rezervaceItem['nazev_mistnosti']) . "</td>
                            <td>" . htmlspecialchars($rezervaceItem['datum_formatted']) . "</td>
                            <td>" . htmlspecialchars($rezervaceItem['zacatek']) . " - " . htmlspecialchars($rezervaceItem['konec']) . "</td>
                            <td>" . htmlspecialchars($rezervaceItem['prijmeni_osoby']) . "</td>";
                    
                        echo "<td>
                                <form method='POST' id='hide'>
                                <div class='uprava'>
                                    <input type='hidden' name='rez_id' value=" . $rezervaceItem['id'] . ">";
                                    if (!empty($rezervaceItem['heslo'])) {
                                        echo "<input type='submit' name='upravit-form' value=' ' title='Upravit' class='icons" . $rezervaceItem['id'] . "'>";
                                    } else {
                                        echo "<input type='submit' name='upravit-bez-hesla' value=' ' title='Upravit' class='icons" . $rezervaceItem['id'] . "'>";
                                    }
                                echo "<input type='submit' name='smazat-form' value='Smazat' title='Smazat'  class='icons" . $rezervaceItem['id'] . "'>";
                        if (!empty($rezervaceItem['heslo'])) {
                            $checkOwner = $pdo->prepare("SELECT id_uzivatele FROM rezervace WHERE id = ?");
                            $checkOwner->execute([$rezervaceItem['id']]);
                            $row = $checkOwner->fetch(PDO::FETCH_ASSOC);

                            $jeVlastnik = isset($_SESSION['user_id']) && $row && ($_SESSION['user_id'] == $row['id_uzivatele']);

                            if (!$jeVlastnik) {
                                echo "<input type='button' name='odemknout' value='Odemknout' title='Odemknout pro úpravy' onclick='unlock(" . $rezervaceItem['id'] . ")' id='" . $rezervaceItem['id'] . "'>
                                    <input type='text' name='unlock_rez' id='unlock_rez" . $rezervaceItem['id'] . "' style='display: none;' class='unlock_rez_class'>
                                    <input type='submit' name='unlockFR' value='' title='Odemknout' id='key" . $rezervaceItem['id'] . "' class='key' style='display: none;'>";
                            }
                        }
                        echo "</div></form></td></tr>";
                    }

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
                echo "<tr>
                        <th>ID místnosti</th>
                        <th>Název místnosti</th>
                        <th>Kapacita</th>
                      </tr>";
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
                <div class="floating-label" id="rez_pass_input">
                    <input type="text" name="heslo_rezervace" placeholder=" " id="heslo_rezervace">
                    <label for="heslo_rezervace">Heslo pro úpravu (nepovinné)</label>
                </div>
            </div>
            <div class="row">
                <div class="floating-label">
                    <input type="submit" value="Přidat rezervaci" name="pridat">
                </div>
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
    </section>
</body>
</html>
<script>
function prihlaseni() {
  document.getElementById('blur-overlay').style.display = 'block';
  document.getElementById('prihlaseni').style.display = 'block';
}

function registrovani() {
  document.getElementById('blur-overlay').style.display = 'block';
  document.getElementById('registrace').style.display = 'block';
}

function zavritPrihlaseni() {
  document.getElementById('blur-overlay').style.display = 'none';
  document.getElementById('prihlaseni').style.display = 'none';
}

function zavritRegistraci(){
    document.getElementById('blur-overlay').style.display = 'none';
    document.getElementById('registrace').style.display = 'none';
}

function registrace(){
    zavritPrihlaseni();
    registrovani();
}

function zavrit(){
    zavritPrihlaseni();
    zavritRegistraci();
}

function showPass() {
    var passField = document.getElementById("heslo-login");
    var showIcon = document.getElementById("show");
    if (passField.type === "password") {
        passField.type = "text";
        showIcon.src = "./img/hidden.png";
    } else {
        passField.type = "password";
        showIcon.src = "./img/eye.png";
    }
}

function showPass2() {
    var passField = document.getElementById("pass-reg");
    var showIcon = document.getElementById("show2");
    if (passField.type === "password") {
        passField.type = "text";
        showIcon.src = "./img/hidden.png";
    } else {
        passField.type = "password";
        showIcon.src = "./img/eye.png";
    }
}

function odhlasit() {
    fetch('logout.php', {method: 'POST'}).then(() => {
        window.location.href = './';
    });
}

function unlock(id) {
    const icons = document.getElementsByClassName('icons' + id);
    const textField = document.getElementById('unlock_rez' + id);
    const key = document.getElementById('key' + id);

    for (const element of icons) {
        const isVisible = getComputedStyle(element).display !== 'none';
        element.style.display = isVisible ? 'none' : 'block';
    }
    if (textField.style.display === "none"){
        key.style.display = "block";

        textField.style.display = "block";
        textField.style.border = "1px solid #ccc";
        textField.style.width = "150px";
        textField.style.height = "20px";
    }else{
        textField.style.display = "none";
    }
}

</script>
