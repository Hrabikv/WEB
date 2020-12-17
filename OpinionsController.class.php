<?php

namespace websp\Controllers;

// nactu rozhrani kontroleru
//require_once(DIRECTORY_CONTROLLERS."/IController.interface.php");
use websp\Models\DatabaseModel;
use websp\Models\OpinionModel;

/**
 * Ovladac zajistujici vypsani uvodni stranky.
 */
class OpinionsController implements IController {

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
     * Vrati obsah uvodni stranky.
     * @param string $pageTitle     Nazev stranky.
     * @return string               Vypis v sablone.
     */
    public function show(string $pageTitle):array {
        //// vsechna data sablony budou globalni
        global $tplData;
        $tplData = [];

        $tplData["title"] = $pageTitle;

        $tplData["opinions"] = $this->db2->getAllOpinions();

        $pocet = 0;
        foreach ($tplData["opinions"] as $n) {
            $uz = $this->db->getUserById($n["id_uzivatel"]);
            $tplData["opinions"][$pocet]["jmeno"] = $uz["jmeno"];
            $pocet++;
        }
        //var_dump($tplData["nazory"]);
        $tplData["prihlasen"] = $this->db->isUserLogged();

        if ($this->db->isUserLogged()) {
            $tplData["uzivatel"] = $this->db->getLoggedUserData();
            $tplData["pravo"] = $this->db->getRightById($tplData["uzivatel"]["id_pravo"]);
            $tplData["pravoNazev"] = ($tplData["pravo"] == null) ? "*Nezánámé*" : $tplData["pravo"]["nazev"];
        }
        for ($index = 0; $index < sizeof($tplData["opinions"]); $index++) {
            if($tplData['opinions'][$index]['hodnoceni_1'] != null && $tplData['opinions'][$index]['hodnoceni_2'] != null
                && $tplData['opinions'][$index]['hodnoceni_3'] != null) {
                $tplData['opinions'][$index]['hodnoceni'] =  $tplData['opinions'][$index]['hodnoceni_1'] + $tplData['opinions'][$index]['hodnoceni_2']
                    + $tplData['opinions'][$index]['hodnoceni_3'];
                if($tplData['opinions'][$index]['hodnoceni'] < 3) {
                    $tplData['opinions'][$index]['hodnoceni'] =  null;
                }
            } else {
                $tplData['opinions'][$index]['hodnoceni'] =  null;
            }
        }

        // vratim sablonu naplnenou daty
        return $tplData;
    }
}

?>