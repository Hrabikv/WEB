<?php
// urceni globalnich promennych, se kterymi sablona pracuje
global $tplData;

//mam vypsat hlasku?
if(isset($tplData['registration'])){
    echo "<div class='alert'>$tplData[registration]</div>";
}
//var_dump($tplData);

?>
<!-- pri odesilani souboru musi byt doplnen atribut enctype="multipart/form-data" -->
<form method="POST" action="" enctype="multipart/form-data">

        <table>
            <tr><td>Nadpis:</td><td><input type="text" name="nazev" required></td></tr>
            <tr><td>Abstrakt:</td><td><input type="text" name="abstrakt" required></td></tr>
            <tr><td>Soubor:</td><td><input type="file" name="soubory[]"></td></tr>
        </table>
        <br>
        <input type="submit" name="potvrzeni" value="Odeslat formulář">
        <input type="reset" value="Smazat formulář">
</form>
<?php
    if(isset($tplData['zprava'])){
        echo "<div class='masage'>$tplData[zprava]</div>";
    }
?>
