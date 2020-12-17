<?php

require_once("myAutoloader.inc.php");

// nactu vlastni nastaveni webu
require_once("settings.inc.php");


// spustim aplikaci
$app = new \websp\ApplicationStart();
$app->appStart();


?>
