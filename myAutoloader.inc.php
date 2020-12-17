<?php

const BASE_NAMESPACE_NAME = "websp";

const BASE_APP_DIR_NAME = "app";

const FILE_EXTENSIONS = array(".class.php", ".interface.php");

//// automaticka registrace pozadovanych trid
spl_autoload_register(function ($className){

    // upravim v nazvu tridy vychozi adresar aplikace
    $className = str_replace(BASE_NAMESPACE_NAME, BASE_APP_DIR_NAME, $className);

    if($className=="ApplicationStart") {
        $className ="app\\ApplicationStart";
    }
    // slozim celou cestu k souboru bez pripony
    $fileName = dirname(__FILE__) ."\\". $className;

    // nacitam tridu nebo interface - upravim cestu k souboru
    // zjistim, zda exituje soubor s danou tridou a dostupnou priponou
    foreach(FILE_EXTENSIONS as $ext) {
        if (file_exists($fileName . $ext)) {
            $fileName .= $ext;
            // nasel jsem, koncim
            break;
        }
    }

    // pripojim soubor s pozadovanou tridou
    require_once($fileName);

});



?>
