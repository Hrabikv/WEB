<?php
//////////////////////////////////////////////////////////////////
/////////////////  Globalni nastaveni aplikace ///////////////////
//////////////////////////////////////////////////////////////////

//// Pripojeni k databazi ////

/** Adresa serveru. */
use websp\Controllers\IntroductionController;
use websp\Controllers\LoginController;
use websp\Controllers\NewOpinionController;
use websp\Controllers\OpinionManagementController;
use websp\Controllers\OpinionsController;
use websp\Controllers\OpinionScoreController;
use websp\Controllers\RegistrationController;
use websp\Controllers\UserManagementController;
use websp\Views\TemplateBasics;

define("DB_SERVER","localhost"); // https://students.kiv.zcu.cz
/** Nazev databaze. */
define("DB_NAME","WEBsp");
/** Uzivatel databaze. */
define("DB_USER","root");
/** Heslo uzivatele databaze */
define("DB_PASS","");


//// Nazvy tabulek v DB ////

/** Tabulka s názory na rok 2020. */
define("TABLE_OPINIONS", "orionlogin_clanky");
/** Tabulka s uzivateli. */
define("TABLE_USER", "orionlogin_uzivatel");
/** Tabulka s pravy uživalele */
define("TABLE_PRAVO", "orionlogin_pravo");


//// Dostupne stranky webu ////

/** Adresar kontroleru. */
const DIRECTORY_CONTROLLERS = "app\Controllers";

/** Klic defaultni webove stranky. */
const DEFAULT_WEB_PAGE_KEY = "uvod";

/** Dostupne webove stranky. */
const WEB_PAGES = array(
    //// Uvodni stranka ////
    "uvod" => array(
        "title" => "Fórum 2020",

        "controller_class_name" => IntroductionController::class,

        "view_class_name" => TemplateBasics::class,

        "template_type" => TemplateBasics::PAGE_INTRODUCTION,
    ),
    //// KONEC: Uvodni stranka ////

    /// Názory ///
    "nazory" => array(
        "title" => "Názory",

        "controller_class_name" => OpinionsController::class,

        "view_class_name" => TemplateBasics::class,

        "template_type" => TemplateBasics::PAGE_OPINIONS,
    ),
    /// KONEC: Názory ///

    //// Nový názor ////
    "novy" => array(
        "title" => "Nový názor",

        "controller_class_name" => NewOpinionController::class,

        "view_class_name" => TemplateBasics::class,

        "template_type" => TemplateBasics::PAGE_NEW_OPINION,
    ),
    //// KONEC: Nový názor ////

    //// Hodnoceci ////
    "hodnoceni" => array(
        "title" => "Hodnocení",

        "controller_class_name" => OpinionScoreController::class,

        "view_class_name" => TemplateBasics::class,

        "template_type" => TemplateBasics::PAGE_OPINION_SCORE,
    ),
    //// KONEC: Hodnoceci ////

    /// Login ///
    "login"=> array(
    "title" => "Přihlášení",

    "controller_class_name" => LoginController::class,

    "view_class_name" => TemplateBasics::class,

    "template_type" => TemplateBasics::PAGE_LOGIN,
    ),
    //// KONEC: Login ////

    //// Registrace ////
    "registrace"=> array(
        "title" => "Registrace",

        "controller_class_name" => RegistrationController::class,

        "view_class_name" => TemplateBasics::class,

        "template_type" => TemplateBasics::PAGE_REGISTRATION,
    ),
    //// KONEC: Registrace ////

    //// Sprava uzivatelu ////
    "sprava_uzivatelu" => array(
        "title" => "Správa uživatelů",

        "controller_class_name" => UserManagementController::class,

        "view_class_name" => TemplateBasics::class,

        "template_type" => TemplateBasics::PAGE_USER_MANAGEMENT,
    ),
    //// KONEC: Sprava uzivatelu ////

    //// Sprava názorů ////
    "sprava_nazoru" => array(
        "title" => "Správa názorů",

        "controller_class_name" => OpinionManagementController::class,

        "view_class_name" => TemplateBasics::class,

        "template_type" => TemplateBasics::PAGE_OPINION_MANAGEMENT,
    )
    //// KONEC: Sprava názorů ////
);


