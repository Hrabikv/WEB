<?php

namespace websp\Models;
use PDO;
use phpDocumentor\Parser\File;

/**
 * Trida spravujici databazi.
 */
class OpinionModel {

    /** @var PDO $pdo Objekt pracujici s databazi prostrednictvim PDO. */
    private $pdo;

    /**
     * Inicializace pripojeni k databazi.
     */
    public function __construct()
    {
        // inicializace DB
        $this->pdo = new PDO("mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
        // vynuceni kodovani UTF-8
        $this->pdo->exec("set names utf8");
    }

    ///////////////////  Obecne funkce  ////////////////////////////////////////////

    /**
     *  Provede dotaz a bud vrati ziskana data, nebo pri chybe ji vypise a vrati null.
     *
     *  @param string $dotaz        SQL dotaz.
     *  @return PDOStatement|null    Vysledek dotazu.
     */
    private function executeQuery(string $dotaz)
    {
        // vykonam dotaz
        $res = $this->pdo->query($dotaz);
        // pokud neni false, tak vratim vysledek, jinak null
        if ($res) {
            // neni false
            return $res;
        } else {
            // je false - vypisu prislusnou chybu a vratim null
            $error = $this->pdo->errorInfo();
            echo $error[2];
            return null;
        }
    }

    public function selectFromTable(string $tableName, string $whereStatement = "", string $orderByStatement = ""):array {
        // slozim dotaz
        $q = "SELECT * FROM ".$tableName
            .(($whereStatement == "") ? "" : " WHERE $whereStatement")
            .(($orderByStatement == "") ? "" : " ORDER BY $orderByStatement");
        // provedu ho a vratim vysledek
        $obj = $this->executeQuery($q);
        // pokud je null, tak vratim prazdne pole
        if($obj == null){
            return [];
        }
        // projdu jednotlive ziskane radky tabulky
        /*while($row = $vystup->fetch(PDO::FETCH_ASSOC)){
            $pole[] = $row['login'].'<br>';
        }*/
        // prevedu vsechny ziskane radky tabulky na pole
        return $obj->fetchAll();
    }

    /**
     * Dle zadane podminky maze radky v prislusne tabulce.
     *
     * @param string $tableName         Nazev tabulky.
     * @param string $whereStatement    Podminka mazani.
     */
    public function deleteFromTable(string $tableName, string $whereStatement){
        // slozim dotaz
        $q = "DELETE FROM $tableName WHERE $whereStatement";
        // provedu ho a vratim vysledek
        $obj = $this->executeQuery($q);
        if($obj == null){
            return false;
        } else {
            return true;
        }
    }

    /**
     * Jednoduchy zapis do prislusne tabulky.
     *
     * @param string $tableName         Nazev tabulky.
     * @param string $insertStatement   Text s nazvy sloupcu pro insert.
     * @param string $insertValues      Text s hodnotami pro prislusne sloupce.
     * @return bool                     Vlozeno v poradku?
     */
    public function insertIntoTable(string $tableName, string $insertStatement, string $insertValues):bool {
        // slozim dotaz
        $q = "INSERT INTO $tableName($insertStatement) VALUES ($insertValues)";
        // provedu ho a vratim uspesnost vlozeni
        $obj = $this->executeQuery($q);
        if($obj == null){
            return false;
        } else {
            return true;
        }
    }

    /**
     * Jednoducha uprava radku databazove tabulky.
     *
     * @param string $tableName                     Nazev tabulky.
     * @param string $updateStatementWithValues     Cela cast updatu s hodnotami.
     * @param string $whereStatement                Cela cast pro WHERE.
     * @return bool                                 Upraveno v poradku?
     */
    public function updateInTable(string $tableName, string $updateStatementWithValues, string $whereStatement) {
        // slozim dotaz
        $q = "UPDATE $tableName SET $updateStatementWithValues WHERE $whereStatement";
        // provedu ho a vratim vysledek
        $obj = $this->executeQuery($q);
        if($obj == null){
            return false;
        } else {
            return true;
        }
    }

    /// KONEC: Obecné fukce ///

    /// Správa názorů ///

    /**
     * Vytvoří nový názor v databázi
     *
     * @param string $nazev     název názoru
     * @param string $abstrakt  abstrakt názoru
     * @param int $id_uzivatel  je cizim klicem do tabulky s uzivateli
     * @return bool             Vlozeno v pořádku?
     */
    public function addNewOpinion(string $nazev, string $abstrakt, int $id_uzivatel) {
        $nazev = htmlspecialchars($nazev);
        $abstrakt = htmlspecialchars($abstrakt);
        $id_uzivatel = htmlspecialchars($id_uzivatel);

        $q = "INSERT INTO ".TABLE_OPINIONS."(nazev, abstrakt, id_uzivatel) 
        VALUES (:nazev, :abstrakt, :uzivatel)";
        $vystup = $this->pdo->prepare($q);
        $vystup->bindValue(":nazev", $nazev);
        $vystup->bindValue(":abstrakt", $abstrakt);
        $vystup->bindValue(":uzivatel", $id_uzivatel);
        return $vystup->execute();
    }

    /**
     * Vytvoří nový názor v databázi
     *
     * @param string $nazev         název názoru
     * @param string $abstrakt      abstrakt názoru
     * @param int $id_uzivatel      je cizim klicem do tabulky s uzivateli
     * @param string $nazev_soubor  nazev prilozeneho souboru
     * @return bool                 Vlozeno v pořádku?
     */
    public function addNewOpinionPlus(string $nazev, string $abstrakt, int $id_uzivatel, string $nazev_soubor) {
        $nazev = htmlspecialchars($nazev);
        $abstrakt = htmlspecialchars($abstrakt);
        $id_uzivatel = htmlspecialchars($id_uzivatel);
        $nazev_soubor = htmlspecialchars($nazev_soubor);

        $q = "INSERT INTO ".TABLE_OPINIONS."(nazev, abstrakt, id_uzivatel, nazev_souboru) 
        VALUES (:nazev, :abstrakt, :uzivatel, :soubor)";
        $vystup = $this->pdo->prepare($q);
        $vystup->bindValue(":nazev", $nazev);
        $vystup->bindValue(":abstrakt", $abstrakt);
        $vystup->bindValue(":uzivatel", $id_uzivatel);
        $vystup->bindValue(":soubor", $nazev_soubor);
        return $vystup->execute();
    }

    /**
     *  Smaze daný názor z DB.
     *  @param int $opinionId  ID nazoru.
     */
    public function deleteOpinion(int $opinionId) {
        $tableName = TABLE_OPINIONS;
        $whereStatement = "id_clanky=".$opinionId;
        return $this->deleteFromTable($tableName, $whereStatement);
    }

    /**
     * Vrátí seznam všech názorů
     *
     * @return array Obseh tabulky názory
     */
    public function getAllOpinions():array {
        $q = "SELECT * FROM " .TABLE_OPINIONS;

        return $this->pdo->query($q)->fetchAll();
    }

    /**
     * Vpřidá danému názoru recenzenta
     *
     * @param int $clanek   názor, který zadaný recenzent má hodnotit
     * @param int $new      ID recenzenta
     * @return bool         Povedlo se?
     */
   public function addRecenzentToOpiion(int $clanek, int $new) {
        $data = $this->getAllOpinions();
        //var_dump($data);
        // slozim cast s hodnotami
        $updateStatementWithValues = null;
        foreach ($data as $d) {
            if($d['id_clanky'] == $clanek) {
                if($d['recenzent_1'] == null || $d['recenzent_1'] == -1) {
                    $updateStatementWithValues = "recenzent_1='$new'";
                } else if($d['recenzent_2'] == null || $d['recenzent_2'] == -1){
                    $updateStatementWithValues = "recenzent_2='$new'";
                } else {
                    $updateStatementWithValues = "recenzent_3='$new'";
                }
            }
        }
        // podminka
        $whereStatement = "id_clanky=$clanek";
        // provedu update
        return $this->updateInTable(TABLE_OPINIONS, $updateStatementWithValues, $whereStatement);
    }

    /**
     * Přidá článku zadané oznamkování
     *
     * @param int $clanek       ID nazoru
     * @param int $score        znamka zadana recenzentem
     * @param int $recenzent    ID recenzenta
     * @return bool             povedlo se?
     */
    public function addScoreToOpiion(int $clanek, int $score, int $recenzent) {
        $data = $this->getAllOpinions();
        //var_dump($new);
        // slozim cast s hodnotami
        $updateStatementWithValues = null;
        foreach ($data as $d) {
            if($d['id_clanky'] == $clanek) {
                if($d['recenzent_1'] == $recenzent) {
                    $updateStatementWithValues = "hodnoceni_1='$score'";
                } else if($d['recenzent_2'] == $recenzent){
                    $updateStatementWithValues = "hodnoceni_2='$score'";
                } else {
                    $updateStatementWithValues = "hodnoceni_3='$score'";
                }
            }

        }
        // podminka
        $whereStatement = "id_clanky=$clanek";
        // provedu update
        return $this->updateInTable(TABLE_OPINIONS, $updateStatementWithValues, $whereStatement);
    }

    public function removeRecenzentFromOpinion(int $clanek, int $recenzent) {
        $data = $this->getAllOpinions();
        //var_dump($data);
        // slozim cast s hodnotami
        $updateStatementWithValues1 = null;
        $updateStatementWithValues2 = null;
        $nul = -1;
        foreach ($data as $d) {
            if($d['id_clanky'] == $clanek) {
                if($d['recenzent_1'] == $recenzent) {
                    $updateStatementWithValues1 = "recenzent_1='$nul'";
                    $updateStatementWithValues2 = "hodnoceni_1='$nul'";
                } else if($d['recenzent_2'] == $recenzent){
                    $updateStatementWithValues1 = "recenzent_2='$nul'";
                    $updateStatementWithValues2 = "hodnoceni_2='$nul'";
                } else {
                    $updateStatementWithValues1 = "recenzent_3='$nul'";
                    $updateStatementWithValues2 = "hodnoceni_3='$nul'";
                }
            }
        }
        // podminka
        $whereStatement = "id_clanky=$clanek";
        // provedu update
        $povedlo = $this->updateInTable(TABLE_OPINIONS, $updateStatementWithValues1, $whereStatement);
        if($povedlo) {
            return $this->updateInTable(TABLE_OPINIONS, $updateStatementWithValues2, $whereStatement);
        } else {
            return false;
        }

    }

}