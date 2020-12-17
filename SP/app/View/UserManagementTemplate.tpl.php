<?php
///////////////////////////////////////////////////////////////////////////
/////////// Sablona pro zobrazeni stranky se spravou uzivatelu  ///////////
///////////////////////////////////////////////////////////////////////////

// urceni globalnich promennych, se kterymi sablona pracuje
global $tplData;

// mam vypsat hlasku?
if(isset($tplData['delete'])){
    echo "<div class='alert'>$tplData[delete]</div>";
}

$res = "<table class='table table-bordered info hlavicka'><tr><th>Id_uzivatel</th><th>Právo</th><th>Jméno</th><th>Login</th><th>Heslo</th><th>E-mail</th><th>Smazání</th></tr>";
// projdu data a vypisu radky tabulky
$pocet = 0;
foreach($tplData["users"] as $u){
    if($pocet % 2 == 0) {
        $barva = "zelena";
    } else {
        $barva = "modra";
    }
    $res .= "<tr class='$barva'><td>$u[id_uzivatel]</td><td>$u[id_pravo]</td><td>$u[jmeno]</td><td>$u[login]</td><td>$u[heslo]</td><td>$u[email]</td>"
        ."<td><form method='post'>"
        ."<input type='hidden' name='id_user' value='$u[id_uzivatel]'>"
        ."<button type='submit' name='action' value='delete'>Smazat</button>"
        ."</form></td></tr>";
    $pocet++;
}

$res .= "</table>";
echo $res;

?>
