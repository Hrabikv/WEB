<?php
///////////////////////////////////////////////////////////////////
////////////// Stranka pro prihlaseni uzivatele ////////////////
///////////////////////////////////////////////////////////////////
// urceni globalnich promennych, se kterymi sablona pracuje
global $tplData;

//mam vypsat hlasku
if(isset($tplData['logged'])){
    echo "<div class='alert'>$tplData[logged]</div>";
}
///////////// PRO NEPRIHLASENE UZIVATELE ///////////////
if(!$tplData["prihlasen"]) {
    ?>

    <form action="" method="POST">
        <table>
            <tr><td>Login:</td><td><input type="text" name="login"></td></tr>
            <tr><td>Heslo:</td><td><input type="password" name="heslo"></td></tr>
        </table>
        <input type="hidden" name="action" value="login">
        <input type="submit" name="potvrzeni" value="Přihlásit se">
    </form>
    <p>Nemáte účet?<br>
        <a border href='index.php?page=registrace' class='text-blue'>Registrovat se můžete zde.</a>
    </p>
<?php
    ///////////// KONEC: PRO NEPRIHLASENE UZIVATELE ///////////////
} else {
    ///////////// PRO PRIHLASENE UZIVATELE ///////////////
?>

<h2>Přihlášený uživatel</h2>

Login: <?php echo $tplData['uzivatel']['login']; ?><br>
Jméno: <?php echo $tplData['uzivatel']['jmeno']; ?><br>
E-mail: <?php echo $tplData['uzivatel']['email']; ?><br>
Právo: <?php echo $tplData['pravoNazev'] ?><br>
<br>

Odhlášení uživatele:
<form action="" method="POST">
    <input type="hidden" name="action" value="logout">
    <input type="submit" name="potvrzeni" value="Odhlásit se">

</form>
<?php
    ///////////// KONEC: PRO PRIHLASENE UZIVATELE ///////////////
}
?>