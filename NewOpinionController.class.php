<?php

namespace websp\Controllers;

// nactu rozhrani kontroleru
use websp\Models\DatabaseModel;
use websp\Models\OpinionModel;

/**
 * Ovladac zajistujici vypsani uvodni stranky.
 */
class NewOpinionController implements IController {

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

        $tplData["prihlasen"] = $this->db->isUserLogged();
        // var_dump($tplData["prihlasen"]);

        if ($this->db->isUserLogged()) {
            $tplData["uzivatel"] = $this->db->getLoggedUserData();
            $tplData["pravo"] = $this->db->getRightById($tplData["uzivatel"]["id_pravo"]);
            $tplData["pravoNazev"] = ($tplData["pravo"] == null) ? "*Nezánámé*" : $tplData["pravo"]["nazev"];
        }

        // zpracovani odeslanych formularu
        if (isset($_POST['potvrzeni'])) {
            // mam vsechny pozadovane hodnoty?
            if (isset($_POST['nazev']) && isset($_POST['abstrakt'])) {
                //// byly na server odeslany nejake soubory?

                if(isset($_FILES["soubory"]["name"])
                    && count($_FILES["soubory"]["name"])    // zamyslete se, proc funguje bez ">0"
                    && !empty($_FILES["soubory"]["name"][0])
                ) {

                    $data = $this->prijmySoubory();
                    $tplData['zprava'] = $data['zprava'];
                    $res = $this->db2->addNewOpinionPlus($_POST['nazev'], $_POST['abstrakt'],
                        $tplData["uzivatel"]["id_uzivatel"], $data['nazev']);
                } else {
                    $res = $this->db2->addNewOpinion($_POST['nazev'], $_POST['abstrakt'], $tplData["uzivatel"]["id_uzivatel"]);
                }
                //var_dump($res);
                // mam vsechny atributy - ulozim uzivatele do DB

                // byl ulozen?
                if ($res) {
                    $tplData["registration"] = "OK: Názor byl přidán do databáze.";
                } else {
                    $tplData["registration"] = "CHYBA: Uložení názoru se nezdařilo.";
                }
            } else {
                // nemam vsechny atributy
                $tplData["registration"] = "CHYBA: Nebyly přijaty požadované atributy názoru.";
            }
        }


        return $tplData;
    }

    /**
     * Pokud byly na server odeslany soubory, tak je ulozi do adresare data
     * @param $pole
     */
    function prijmySoubory(){
        //// mam adresar data?
        $adr = "app/Models/DATA";
        // neni souborem
        if(is_file($adr)){
            echo "Nelze vytvořit adresář DATA.<br>";
        }
        // neni souborem a neexistuje?
        elseif(!file_exists($adr)) {
            mkdir($adr);
        }
        // nemam adresar data?
        if(!is_dir($adr)){
            echo "Adresář DATA nelze použít.<br>";
            return null; // konec funkce
        }
        $pole = null;
        // ziskam nazev souboru, slozim mu celou cestu a ziskam z ni priponu souboru
        $nazev = time() . basename( $_FILES["soubory"]["name"][0]);
        $celyNazev = $adr ."/". $nazev;
        // prevod nazvu z UTF-8 do cp-1250
        $celyNazev = iconv("UTF-8", "WINDOWS-1250", $celyNazev);
        $pole['nazev'] = $nazev;
        // samotny prenos
        if (move_uploaded_file($_FILES["soubory"]["tmp_name"][0], $celyNazev)) {
            $pole['zprava'] = "Soubor '$nazev' byl nahrán na server.";
        } else {
            $pole['zprava'] = "Soubor '$nazev' se nepodařilo nahrát na server.";
        }
        return $pole;
    }
}

?>