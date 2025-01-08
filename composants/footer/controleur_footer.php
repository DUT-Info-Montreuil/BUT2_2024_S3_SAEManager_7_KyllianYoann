<?php
require_once "composants/footer/vue_footer.php";

class ControleurFooter {
    private $vue;

    public function __construct() {
        $this->vue = new VueFooter();
    }

    public function afficher_footer() {
        $this->vue->afficher();
    }
}
