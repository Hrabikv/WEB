<?php
///////////////////////////////////////////////////////////////////////////
/////////// Sablona pro zobrazeni stranky se spravou uzivatelu  ///////////
///////////////////////////////////////////////////////////////////////////

// urceni globalnich promennych, se kterymi sablona pracuje
global $tplData;

// mam vypsat hlasku?
if(isset($tplData['hodnoceni'])){
    echo "<div class='alert'>$tplData[hodnoceni]</div>";
}
//var_dump($tplData);

$res = "<table class='table' border><tr><th>Nazev</th><th>Autor</th><th>Abstrakt</th><th>Známka</th><th>Akce</th></tr>";
// projdu data a vypisu radky tabulky
if(array_key_exists("opinions", $tplData)) {
    foreach($tplData["opinions"] as $u){
        $autor = $u['autor']['jmeno'];
        $znamky = null;
        for ($index = 1; $index < 6; $index++) {
            $znamky .= "<option value='$index'>$index</option>";
        }//hodnota znamky
        $hod ="<td><form method='post'>
        <select name='znamka'>
        $znamky
        </select>
        </td><td>
        <input type='hidden' name='id_clanky' value=$u[id_clanky]>
        <button type='submit' name='action' value='hodnoceni'>Oznamkovat</button></form></td></tr>";
        $res .= "<tr><td>$u[nazev]</td><td>$autor</td><td>$u[abstrakt]</td>$hod</td></tr>";
    }

    $res .= "</table>";
    echo $res;
} else {
    echo "<div class='masage'>Žádné články nebyly přiděleny k recenzi</div>";
}


?>
