<?php

namespace websp\Views;

/**
 * Rozhrani pro vsechny sablony (views).
 * @package kivweb\Views
 */
interface IView {

    /**
     * Zajisti vypsani HTML sablony prislusne stranky.
     * @param array $tplData   Data stranky.
     */
    public function printOutput(array $tplData);

}

?>
