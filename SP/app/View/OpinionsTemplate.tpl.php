<?php
/////////////////////////////////////////////////////////////
/////////// Sablona pro zobrazeni uvodni stranky  ///////////
/////////////////////////////////////////////////////////////

//// pozn.: sablona je samostatna a provadi primy vypis do vystupu:
// -> lze testovat bez zbytku aplikace.
// -> pri vyuziti Twigu se sablona obejde bez PHP.

// urceni globalnich promennych, se kterymi sablona pracuje
global $tplData;
//var_dump($tplData);
$nalezen = true;
$res = "";
if($tplData['prihlasen']) {
    if (array_key_exists('opinions', $tplData) && !(sizeof($tplData['opinions']) == 0)) {

        foreach ($tplData['opinions'] as $d) {
            $res .= "<h2>$d[nazev]</h2>";
            $res .= "<b>Autor:</b> $d[jmeno]<br>";
            $res .= "<div style='text-align:justify;'><b>Úryvek:<br></b> $d[abstrakt]</div><hr>";
        }
    } else {
        echo "<div class='masage'>Žádné názory nenalezeny</div>";
    }
    echo $res;
} else {
    $pocet = 0;
    foreach ($tplData['opinions'] as $d) {
        if (array_key_exists('hodnoceni', $tplData['opinions'][$pocet]) && $tplData['opinions'][$pocet]['hodnoceni'] != null) {
                $res .= "<h2>$d[nazev]</h2>";
                $res .= "<b>Autor:</b> $d[jmeno]<br>";
                $res .= "<div style='text-align:justify;'><b>Úryvek:</b> $d[abstrakt]</div><hr>";
                $nalezen = false;
        }
        $pocet++;
    }
    if ($nalezen) {
        echo "<div class='masage'>Žádné veřejné názory nenalezeny. Musíte se přihlásit.</div>";
    } else {
        echo $res;
    }
}
?>
