<?php

namespace websp\Controllers;

// nactu rozhrani kontroleru
//require_once(DIRECTORY_CONTROLLERS."/IController.interface.php");
use websp\Models\DatabaseModel;

/**
 * Ovladac zajistujici vypsani uvodni stranky.
 */
class RegistrationController implements IController {

    /** @var DatabaseModel $db  Sprava databaze. */
    private $db;

    /**
     * Inicializace pripojeni k databazi.
     */
    public function __construct() {
        $this->db = new DatabaseModel();
    }

    /**
     * Vrati obsah uvodni stranky.
     * @param string $pageTitle     Nazev stranky.
     * @return string               Vypis v sablone.
     */
    public function show(string $pageTitle):array
    {
        //// vsechna data sablony budou globalni
        global $tplData;
        $tplData = [];

        $tplData["title"] = $pageTitle;

        $tplData["rights"] = $this->db->getAllRights();

        $tplData["prihlasen"] = $this->db->isUserLogged();

        if ($this->db->isUserLogged()) {
            $tplData["uzivatel"] = $this->db->getLoggedUserData();
        }

        // zpracovani odeslanych formularu
        if (isset($_POST['potvrzeni'])) {
            // mam vsechny pozadovane hodnoty?
            if (isset($_POST['login']) && isset($_POST['heslo']) && isset($_POST['heslo2'])
                && isset($_POST['jmeno']) && isset($_POST['email']) && isset($_POST['pravo'])
                && $_POST['heslo'] == $_POST['heslo2']
                && $_POST['login'] != "" && $_POST['heslo'] != "" && $_POST['jmeno'] != "" && $_POST['email'] != ""
                && $_POST['pravo'] > 0
            ) {
                // pozn.: heslo by melo byt sifrovano
                // napr. password_hash($password, PASSWORD_BCRYPT) pro sifrovani
                // a password_verify($password, $hash) pro kontrolu hesla.

                // mam vsechny atributy - ulozim uzivatele do DB
                $res = $this->db->addNewUser($_POST['login'], $_POST['heslo'], $_POST['jmeno'], $_POST['email'], $_POST['pravo']);
                // byl ulozen?
                if ($res) {
                    $tplData["registration"] = "OK: Uživatel byl přidán do databáze a byl prihlášen.";
                    $this->db->userLogin($_POST['login'], $_POST['heslo']);
                    $tplData["prihlasen"] = $this->db->isUserLogged();
                    $tplData["uzivatel"] = $this->db->getLoggedUserData();
                } else {
                    $tplData["registration"] = "CHYBA: Uložení uživatele se nezdařilo.";
                }
            } else {
                // nemam vsechny atributy
                $tplData["registration"] = "CHYBA: Nebyly přijaty požadované atributy uživatele.";
            }
            echo "<br><br>";
        }
        // vratim sablonu naplnenou dat
        return $tplData;
    }
}

?>