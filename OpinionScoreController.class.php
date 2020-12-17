<?php

namespace websp\Controllers;

// ukazka aliasu
use websp\Models\DatabaseModel;
use websp\Models\OpinionModel;

/**
 * Ovladac zajistujici vypsani stranky se spravou uzivatelu.
 * @package kivweb\Controllers
 */
class OpinionScoreController implements IController {

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

        $tplData["prihlasen"] = $this->db->isUserLogged();

        if ($this->db->isUserLogged()) {
            $tplData["uzivatel"] = $this->db->getLoggedUserData();
            $tplData["pravo"] = $this->db->getRightById($tplData["uzivatel"]["id_pravo"]);
            $tplData["pravoNazev"] = ($tplData["pravo"] == null) ? "*Nezánámé*" : $tplData["pravo"]["nazev"];
        }

        $data = $this->db2->getAllOpinions();
        $i = 0;
        for($index = 0; $index < sizeof($data); $index++) {
            if($data[$index]["recenzent_1"] == $tplData["uzivatel"]["id_uzivatel"]
                || $data[$index]["recenzent_2"] == $tplData["uzivatel"]["id_uzivatel"]
                || $data[$index]["recenzent_3"] == $tplData["uzivatel"]["id_uzivatel"]) {
                    $tplData['opinions'][$i] = $data[$index];
                    $tplData['opinions'][$i]['autor'] = $this->db->getUserById($data[$index]['id_uzivatel']);
                    $i++;
            }
        }

        if(isset($_POST['action'])) {
            //var_dump($_POST);
            $res = $this->db2->addScoreToOpiion($_POST['id_clanky'], $_POST['znamka'], $tplData['uzivatel']['id_uzivatel']);
            if($res) {
                $tplData['hodnoceni'] = "OK: Známka zapsána.";
            } else {
                $tplData['hodnoceni'] = "CHYBA: Známku se nepodařilo zapsat do databáze.";
            }
        }
        // vratim sablonu naplnenou daty
        return $tplData;
    }



}

?>
