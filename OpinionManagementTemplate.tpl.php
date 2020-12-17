<?php
///////////////////////////////////////////////////////////////////////////
/////////// Sablona pro zobrazeni stranky se spravou uzivatelu  ///////////
///////////////////////////////////////////////////////////////////////////
const ZACATEK_HOD = 5;

const ZACATEK_REC = 8;
// urceni globalnich promennych, se kterymi sablona pracuje
global $tplData;

//var_dump($tplData);
// mam vypsat hlasku?
if(isset($tplData['delete'])){
    echo "<div class='alert'>$tplData[delete]</div>";
}

if(isset($tplData['registration'])){
    echo "<div class='alert'>$tplData[registration]</div>";
}

if(sizeof($tplData['opinions']) == 0) {
    echo "<div class='masage'>Nenalezeny žádné názory</div>";
} else {
    $res = "<table class='table table-bordered hlavicka' ><tr><th>Id_clanku</th><th>Autor</th><th>Název</th><th>Hodnocení</th><th>Smazání</th></tr>";
    $radek = 0;
    // projdu data a vypisu radky tabulky
    foreach ($tplData["opinions"] as $u) {

        //vnitrni tabulka s tremi recenzentemi
        $hod = "<table class='table table-bordered hlavicka'><tr><th>Recenzent</th><th>Známka</th><th>Akce</th></tr>";
        for ($pocet = 0; $pocet < 3; $pocet++) {
            if($pocet % 2 == 0) {
                $barva = "cervena";
            } else {
                $barva = "hneda";
            }
            $r = $pocet + ZACATEK_REC;
            if ($u[$r] != null && $u[$r] != -1) {
                $hod .= "<tr class='$barva'><td>$u[$r]</td>";
                $rec = $u[$r];
                $h = ZACATEK_HOD + $pocet;
                if ($u[$h] != null && $u[$h] != -1) {
                    $hod .= "<td>$u[$h]</td><td><form method='post'><input type='hidden' name='id_clanky' value='$u[id_clanky]'>
                    <input type='hidden' name='rec' value='$rec'>
                    <button type='submit' name='action' value='remove'>Odebrat</button></form></td></tr>";
                } else {
                    $hod .= "<td>Neohodnoceno</td><td><form method='post'><input type='hidden' name='id_clanky' value='$u[id_clanky]'>
                    <input type='hidden' name='rec' value='$rec'>
                    <button type='submit' name='action' value='remove'>Odebrat</button></form></td></tr>";
                }
            } else {
                $recen = null;
                foreach ($tplData['uzivatele'] as $uz) {
                    if ($uz['id_pravo'] == 3) {
                        // var_dump($uz);
                        $recen .= "<option value='$uz[id_uzivatel]'>$uz[jmeno]</option>";
                    }
                }
                $hod .= "<tr class='$barva'><td><form method='post'>"
                    . "<select name='rec'>"
                    . $recen
                    . "</select>"
                    . "</td><td>Neni recenzent</td><td><input type='hidden' name='id_clanky' value='$u[id_clanky]'>
                    <button type='submit' name='action' value='add'>Přidat</button></form></td></tr>";
            }
        }
        if($radek % 2 == 0) {
            $barva = "zelena";
        } else {
            $barva = "modra";
        }
        $hod .= "</table>";
        $res .= "<tr class='$barva'><td>$u[id_clanky]</td><td>$u[autor]</td><td>$u[nazev]</td><td>$hod</td>"
            . "<td><form method='post'>"
            . "<input type='hidden' name='id_clanky' value='$u[id_clanky]'>"
            . "<button type='submit' name='action' value='delete'>Smazat</button>"
            . "</form></td></tr>";
        $radek++;
    }
    $res .= "</table>";
    echo $res;
}
?>
