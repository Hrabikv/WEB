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

    const PAGE_USER_PROFILE = "UserProfileTemplate.tpl.php";

    const PAGE_MY_OPINIONS = "MyOpinionsTemplate.tpl.php";
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

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

        <html lang="cs">
            <head>
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

            <body class="container-fluid">
            <?php
            //var_dump($pageTitle);
            ?>
                <h1 class="hlavicka container-fluid"><?php echo $pageTitle["title"]; ?></h1>

                <nav class="navbar navbar-inverse">
                    <div class="container-fluid">
                        <div class="navbar-header">
                            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>
                            <a class="navbar-brand" href="index.php">Forum 2020</a>
                        </div>
                        <div class="collapse navbar-collapse" id="myNavbar">
                            <ul class="nav navbar-nav">
                                <?php
                                // vypis menu
                                foreach(WEB_PAGES as $key => $pInfo){
                                    if($pageTitle["prihlasen"] == false){//neprihlaseny uzivatel
                                        if($key == "nazory"){
                                            echo "<li><a href='index.php?page=$key' class='text-white'>$pInfo[title]</a></li>";
                                        }
                                    } else {
                                        if($key != "registrace" && $key != "login" && $key != "uvod")
                                            if($pageTitle["uzivatel"]["id_pravo"] == "1") {//admin
                                                if($key != "hodnoceni" && $key != "novy" && $key != "moje"
                                                    && $key != "sprava_uzivatele") {
                                                    echo "<li><a href='index.php?page=$key' class='text-white'>$pInfo[title]</a></li>";
                                                }
                                            } else if($pageTitle["uzivatel"]["id_pravo"] == "2"){//autor
                                                if($key != "sprava_uzivatelu" && $key != "sprava_nazoru"
                                                    && $key != "hodnoceni" && $key != "sprava_uzivatele"
                                                    && $key != "sprava_nazoru") {
                                                    echo "<li><a href='index.php?page=$key' class='text-white'>$pInfo[title]</a></li>";
                                                }
                                            } else {//recenzent
                                                if($key != "sprava_uzivatelu" && $key != "sprava_nazoru"
                                                    && $key != "novy" && $key != "moje" && $key != "sprava_uzivatele"
                                                    && $key != "sprava_nazoru") {
                                                    echo "<li><a href='index.php?page=$key' class='text-white'>$pInfo[title]</a></li>";
                                                }
                                            }
                                    }
                                }
                                ?>
                            </ul>
                            <ul class="nav navbar-nav navbar-right">
                                <?php
                            if (!$pageTitle["prihlasen"]) {
                                echo "<li><a href='index.php?page=login'><span class='glyphicon glyphicon-log-in'></span> Prihlásit se</a></li>";
                            } else {
                                $uzivatel = $pageTitle['uzivatel']['jmeno'];
                                echo "<li><a class='text-white' href='index.php?page=sprava_uzivatele'>Přihlášený uživatel: <b>$uzivatel</b></a></li>
                                      <li><a href='index.php?page=login'><span class='glyphicon glyphicon-log-out'></span> Odhlásit se</a></li>";
                            }
                            ?>

                            </ul>
                        </div>
                    </div>
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
                <footer class="container-fluid">&copy; <?= date("Y-m-d") ?> Václav Hrabík</footer>
            <body>
        </html>

        <?php
    }
        
}

?>