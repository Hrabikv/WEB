<?php

namespace websp\Controllers;

/**
 * Rozhrani pro vsechny ovladace (kontrolery).
 */
interface IController {

    /**
     * Zajisti vypsani prislusne stranky.
     *
     * @param string $pageTitle     Nazev stanky.
     * @return array
     */
    public function show(string $pageTitle):array;

}

?>