<?php

namespace websp\Controllers;

// ukazka aliasu
use websp\Models\DatabaseModel;
use websp\Models\OpinionModel;

/**
 * Ovladac zajistujici vypsani stranky se spravou uzivatelu.
 * @package kivweb\Controllers
 */
class OpinionManagementController implements IController {

    /** @var DatabaseModel $db  Sprava databaze. */
    private $db;

    private $db2;

    /**
     * Inicializace pripojeni k databazi.
     */
    public function __construct() {
        $this->db = new DatabaseModel();
        $this->db2 = new OpinionModel();
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

        //// neprisel pozadavek na smazani nazoru?
        if(isset($_POST['action']) and $_POST['action'] == "delete"
            and isset($_POST['id_clanky'])
        ){
            var_dump(intval($_POST['id_clanky']));
            // provedu smazani uzivatele
            $ok = $this->db2->deleteOpinion(intval($_POST['id_clanky']));
            if($ok){
                $tplData['delete'] = "OK: Názor s ID:$_POST[id_clanky] byl smazán z databáze.";
            } else {
                $tplData['delete'] = "CHYBA: Názor s ID:$_POST[id_clanky] se nepodařilo smazat z databáze.";
            }
        }

        $tplData['opinions'] = $this->db2->getAllOpinions();

        //// neprisel pozadavek na přidání recenzenta?
        if (isset($_POST['action']) && $_POST['action'] == 'add') {
            $tplData['registration'] = $this->pridej($tplData);
        }

        //// neprisel pozadavek na odebrání recenzenta?
        if (isset($_POST['action']) && $_POST['action'] == 'remove') {
            $tplData['registration'] = $this->odeber($tplData);
        }

        $tplData['opinions'] = $this->db2->getAllOpinions();

        //// nactu data nazoru
        $tplData["prihlasen"] = $this->db->isUserLogged();

        if ($this->db->isUserLogged()) {
            $tplData["uzivatel"] = $this->db->getLoggedUserData();
            $tplData["pravo"] = $this->db->getRightById($tplData["uzivatel"]["id_pravo"]);
            $tplData["pravoNazev"] = ($tplData["pravo"] == null) ? "*Nezánámé*" : $tplData["pravo"]["nazev"];
        }

        for ($index = 0; $index < sizeof($tplData["opinions"]); $index++) {
            $tplData['opinions'][$index]['autor'] = $this->db->getUserById($tplData['opinions'][$index]['id_uzivatel'])['jmeno'];
        }

        $tplData['uzivatele'] = $this->db->getAllUsers();

        // vratim sablonu naplnenou daty
        return $tplData;
    }

    /**
     * Zajišťuje pridání recenzenta k hodnocení článku
     * @param array $data   jednotlivé články
     * @return string       zprava
     */
    private function pridej(array $data) {
            //var_dump($_POST);
            // mam vsechny pozadovane hodnoty?
            if (isset($_POST['rec']) && isset($_POST['id_clanky'])) {

                // mam vsechny atributy - ulozim uzivatele do DB
                $res = $this->db2->addRecenzentToOpiion((int)$_POST['id_clanky'], (int)$_POST['rec']);
                // byl ulozen?
                $rec = $this->db->getUserById($_POST['rec']);
                $nazev = null;
                for ($index = 0; $index < sizeof($data["opinions"]); $index++) {
                    if($data['opinions'][$index]['id_clanky'] == $_POST['id_clanky']) {
                        $nazev = $data['opinions'][$index]['nazev'];
                    }
                }
                //var_dump($rec);
                if ($res) {
                    $Data = "OK: Recenzent ".$rec['jmeno']." byl přidán na recenzi názoru ".$nazev.".";
                } else {
                    $Data = "CHYBA: Uložení recenzenta ".$rec['jmeno']." se nezdařilo.";
                }
            } else {
                // nemam vsechny atributy
                $Data = "CHYBA: Nebyly přijaty požadované atributy uživatele.";
            }
            echo "<br><br>";
        return $Data;
    }

    /**
     * Zajišťuje odebrání recenzenta z hodnocení článku
     * @param array $data   jednotlivé články
     * @return string       zprava
     */
    private function odeber(array $data) {
        //var_dump($_POST);
        // mam vsechny pozadovane hodnoty?
        if (isset($_POST['rec']) && isset($_POST['id_clanky'])) {

            // mam vsechny atributy - ulozim uzivatele do DB
            $res = $this->db2->removeRecenzentFromOpinion((int)$_POST['id_clanky'], (int)$_POST['rec']);
            // byl ulozen?
            $rec = $this->db->getUserById($_POST['rec']);
            $nazev = null;
            for ($index = 0; $index < sizeof($data["opinions"]); $index++) {
                if($data['opinions'][$index]['id_clanky'] == $_POST['id_clanky']) {
                    $nazev = $data['opinions'][$index]['nazev'];
                }
            }
            //var_dump($rec);
            if ($res) {
                $Data = "OK: Recenzent ".$rec['jmeno']." byl odebrán z recenze na názor ".$nazev.".";
            } else {
                $Data = "CHYBA: Odebrání recenzenta ".$rec['jmeno']." se nezdařilo.";
            }
        } else {
            // nemam vsechny atributy
            $Data = "CHYBA: Nebyly přijaty požadované atributy uživatele.";
        }
        echo "<br><br>";
        return $Data;
    }
}

?>
