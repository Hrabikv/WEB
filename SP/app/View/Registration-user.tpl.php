<?php
///////////////////////////////////////////////////////////////////
////////////// Stranka pro registraci uzivatele ////////////////
///////////////////////////////////////////////////////////////////

// nacteni souboru s funkcemi
global $tplData;

// mam vypsat hlasku?
if(isset($tplData['registration'])){
    echo "<div class='alert'>$tplData[registration]</div>";
}

///////////// PRO NEPRIHLASENE UZIVATELE ///////////////
if(!$tplData['prihlasen']) {
?>

    <form action="" method="POST" oninput="x.value=(pas1.value == pas2.value)?'OK':'Nestejná hesla'">
        <table>
            <tr><td>Login:</td><td><input type="text" name="login" required></td></tr>
            <tr><td>Heslo 1:</td><td><input type="password" name="heslo" id="pas1" required></td></tr>
            <tr><td>Heslo 2:</td><td><input type="password" name="heslo2" id="pas2" required></td></tr>
            <tr><td>Ověření hesla:</td><td><output name="x" for="pas1 pas2"></output></td></tr>
            <tr><td>Jméno:</td><td><input type="text" name="jmeno" required></td></tr>
            <tr><td>E-mail:</td><td><input type="email" name="email" required></td></tr>
            <tr><td>Právo:</td>
                <td>
                    <select name="pravo">

                        <?php
                        // ziskam vsechna prava
                        foreach ($tplData["rights"] as $r) {
                            echo "<option value='$r[id_pravo]'>$r[nazev]</option>";
                        }
                        ?>
                    </select>
                </td>
            </tr>
        </table>

        <input type="submit" name="potvrzeni" value="Registrovat se">
    </form>
    <?php
    ///////////// KONEC: PRO NEPRIHLASENE UZIVATELE ///////////////
} else {
    ///////////// PRO PRIHLASENE UZIVATELE ///////////////
        echo "<div class='masage'>Přihlášený uživatel se nemůže znovu registrovat.</div>";
}
///////////// KONEC: PRO PRIHLASENE UZIVATELE ///////////////
?>
