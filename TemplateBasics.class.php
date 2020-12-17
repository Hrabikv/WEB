<?php

namespace websp\Views;

/**
 * Trida vypisujici HTML hlavicku a paticku stranky.
 */
class TemplateBasics implements IView {

    /** @var string PAGE_INTRODUCTION  Sablona s uvodni strankou. */
    const PAGE_INTRODUCTION = "IntroductionTemplate.tpl.php";
    /** @var string PAGE_USER_MANAGEMENT  Sablona se spravou uzivatelu. */
    const PAGE_USER_MANAGEMENT = "UserManagementTemplate.tpl.php";
    /** @var string PAGE_OPINION_MANAGEMENT  Sablona se spravou uzivatelu. */
    const PAGE_OPINION_MANAGEMENT = "OpinionManagementTemplate.tpl.php";
    /** @var string PAGE_OPINIONS  Sablona s nazory. */
    const PAGE_OPINIONS = "OpinionsTemplate.tpl.php";
    /** @var string PAGE_LOGIN  Sablona s prihlaseni/odhlaseni uzivatele. */
    const PAGE_LOGIN = "LoginTemplate.tpl.php";
    /** @var string PAGE_REGISTRATION  Sablona s registraci uzivatele. */
    const PAGE_REGISTRATION = "Registration-user.tpl.php";
    /** @var string PAGE_NEW_OPINION  Sablona s formularem pro zadani noveho nazoru. */
    const PAGE_NEW_OPINION = "NewOpinionTemplate.tpl.php";
    /** @var string PAGE_OPINION_SCORE  Sablona s formularem pro hodnoceni nazoru. */
    const PAGE_OPINION_SCORE = "OpinionScoreTemplate.tpl.php";

    /**
     * Zajisti vypsani HTML sablony prislusne stranky.
     * @param array $templateData       Data stranky.
     * @param string $pageType          Typ vypisovane stranky.
     */
    public function printOutput(array $templateData, string $pageType = self::PAGE_INTRODUCTION)
    {
        //// vypis hlavicky
        $this->getHTMLHeader($templateData);
        //// vypis sablony obsahu
        // data pro sablonu nastavim globalni
        global $tplData;
        $tplData = $templateData;
        // nactu sablonu
        require_once($pageType);

        //// vypis pacicky
        $this->getHTMLFooter();
    }

    public function getHTMLHeader(array $pageTitle) {
        ?>

        <!doctype html>

        <meta charset="utf-8">
        <!-- nastaveni viewportu je zakladem pro responzivni design i Boostrap -->
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="stylesheet" href="npm-ukazka/node_modules/bootstrap/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="npm-ukazka/node_modules/font-awesome/css/font-awesome.min.css">

        <html lang="cs">
            <head class="container">
                <meta charset='utf-8'>
                <title><?php echo $pageTitle["title"]; ?></title>

                <style>
                    nav { background-color:gray; padding:10px; }
                    nav a { margin: 0 10px; }
                    footer { padding: 10px; background-color: lightcoral; text-align: center; }
                    .alert { padding: 10px; background-color: lightblue; font-weight: bold; margin-bottom: 20px; border-radius: 10px; }
                    .masage { padding: 10px; background-color: greenyellow; font-weight: bold; margin-bottom: 20px; border-radius: 10px; }
                    .modra {background-color: lightskyblue; font-weight: bold;}
                    .zelena {background-color: lightgreen; font-weight: bold;}
                    .hlavicka {background-color: yellow; font-weight: bold;}
                    .cervena {background-color: red; font-weight: bold;}
                    .hneda {background-color: sandybrown; font-weight: bold;}
                </style>

            </head>
            <body class="container">
                <h1 class="hlavicka"><?php echo $pageTitle["title"]; ?></h1>
                    <p class="pull-right">
                        <?php
                            if (!$pageTitle["prihlasen"]) {
                                echo "<table class='text-success pull-right table-bordered'><tr><td>Uživatel:</td><th>Nepřihlášený uživatel</th></tr>"
                                ."<tr><td><a href='index.php?page=login' class='text-lightblue' >Přihlásit se</a></td></tr></table>";
                            } else {
                                $uzivatel = $pageTitle['uzivatel']['jmeno'];
                                echo "<table class='text-success table-bordered pull-right'><tr><td>Uživatel:</td><th>$uzivatel</th></tr>"
                                ."<td><a href='index.php?page=login' class='text-lightblue' >Odhlásit se</a></td></tr></table>";
                            }
                            ?>
                    </p><br><br>
                <nav>
                    <?php
                        // vypis menu
                        foreach(WEB_PAGES as $key => $pInfo){
                            if($pageTitle["prihlasen"] == false){//neprihlaseny uzivatel
                                if($key == "uvod" || $key == "nazory"){
                                    echo "<a href='index.php?page=$key' class='text-white'>$pInfo[title]</a>";
                                }
                            } else {
                                if($key != "registrace" && $key != "login")
                                if($pageTitle["uzivatel"]["id_pravo"] == "1") {//admin
                                    if($key != "hodnoceni" && $key != "novy") {
                                        echo "<a href='index.php?page=$key' class='text-white'>$pInfo[title]</a>";
                                    }
                                } else if($pageTitle["uzivatel"]["id_pravo"] == "2"){//autor
                                    if($key != "sprava_uzivatelu" && $key != "sprava_nazoru" && $key != "hodnoceni") {
                                        echo "<a href='index.php?page=$key' class='text-white'>$pInfo[title]</a>";
                                    }
                                } else {//recenzent
                                    if($key != "sprava_uzivatelu" && $key != "sprava_nazoru" && $key != "novy") {
                                        echo "<a href='index.php?page=$key' class='text-white'>$pInfo[title]</a>";
                                    }
                                }
                            }

                        }
                    ?>
                </nav>
                    <br>
        <?php
    }
    
    /**
     *  Vrati paticku stranky.
     */
    public function getHTMLFooter(){
        ?>
                <br>
                <footer>&copy; <?= date("Y-m-d") ?></footer>
            <body>
        </html>

        <?php
    }
        
}

?>