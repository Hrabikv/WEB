<?php

namespace websp\Controllers;

// ukazka aliasu
use websp\Models\DatabaseModel;

/**
 * Ovladac zajistujici vypsani stranky se spravou uzivatelu.
 * @package kivweb\Controllers
 */
class UserManagementController implements IController {

    /** @var DatabaseModel $db  Sprava databaze. */
    private $db;

    /**
     * Inicializace pripojeni k databazi.
     */
    public function __construct() {
        $this->db = new DatabaseModel();

    }

    /**
     * Vrati obsah stranky se spravou uzivatelu.
     * @param string $pageTitle     Nazev stranky.
     * @return array                Vytvorena data pro sablonu.
     */
    public function show(string $pageTitle):array {
        //// vsechna data sablony budou globalni
        $tplData = [];
        // nazev
        $tplData['title'] = $pageTitle;

        //// neprisel pozadavek na smazani uzivatele?
        if(isset($_POST['action']) and $_POST['action'] == "delete"
            and isset($_POST['id_user'])
        ){
            // provedu smazani uzivatele
            $ok = $this->db->deleteUser(intval($_POST['id_user']));
            if($ok){
                $tplData['delete'] = "OK: Uživatel s ID:$_POST[id_user] byl smazán z databáze.";
            } else {
                $tplData['delete'] = "CHYBA: Uživatele s ID:$_POST[id_user] se nepodařilo smazat z databáze.";
            }
        }

        $tplData["prihlasen"] = $this->db->isUserLogged();

        if ($this->db->isUserLogged()) {
            $tplData["uzivatel"] = $this->db->getLoggedUserData();
            $tplData["pravo"] = $this->db->getRightById($tplData["uzivatel"]["id_pravo"]);
            $tplData["pravoNazev"] = ($tplData["pravo"] == null) ? "*Nezánámé*" : $tplData["pravo"]["nazev"];
        }

        //// nactu aktualni data uzivatelu
        $tplData['users'] = $this->db->getAllUsers();

        // vratim sablonu naplnenou daty
        return $tplData;
    }

}

?>
