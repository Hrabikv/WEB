<?php

namespace websp\Controllers;

// nactu rozhrani kontroleru
//require_once(DIRECTORY_CONTROLLERS."/IController.interface.php");
use websp\Models\DatabaseModel;

/**
 * Ovladac zajistujici vypsani uvodni stranky.
 */
class LoginController implements IController
{

    /** @var DatabaseModel $db Sprava databaze. */
    private $db;

    /**
     * Inicializace pripojeni k databazi.
     */
    public function __construct() {
        $this->db = new DatabaseModel();
    }

    /**
     * Vrati obsah uvodni stranky.
     * @param string $pageTitle Nazev stranky.
     * @return string               Vypis v sablone.
     */
    public function show(string $pageTitle): array
    {
        //// vsechna data sablony budou globalni
        global $tplData;
        $tplData = [];

        $tplData["title"] = $pageTitle;

        $tplData["prihlasen"] = false;

        // zpracovani odeslanych formularu
        if (isset($_POST['action'])) {
            // prihlaseni
            if (isset($_POST['login']) && $_POST['action'] == 'login' && isset($_POST['heslo'])) {
                // pokusim se prihlasit uzivatele
                $res = $this->db->userLogin($_POST['login'], $_POST['heslo']);
                if ($res) {
                    $tplData["logged"] = "OK: Uživatel byl přihlášen.";
                } else {
                    $tplData["logged"] = "CHYBA: Přihlášení uživatele se nezdařilo.";
                }

            } // odhlaseni
            else if ($_POST['action'] == 'logout') {
                // odhlasim uzivatele
                $this->db->userLogout();
                $tplData["logged"] = "OK: Uživatel byl odhlášen.";
            } // neznama akce
            else {
                $tplData["logged"] = "WARNING: Neznámá akce.";
            }
        }

        $tplData["prihlasen"] = $this->db->isUserLogged();

        if ($this->db->isUserLogged()) {
            $tplData["uzivatel"] = $this->db->getLoggedUserData();
            $tplData["pravo"] = $this->db->getRightById($tplData["uzivatel"]["id_pravo"]);
            $tplData["pravoNazev"] = ($tplData["pravo"] == null) ? "*Nezánámé*" : $tplData["pravo"]["nazev"];
        }


        return $tplData;


    }
}

?>