<?php
require_once "modules/mod_etudiant/controleur_etudiant.php";

class ModEtudiant extends ModuleGenerique {
    public function __construct() {
        parent::__construct();
        $this->controleur = new ControleurEtudiant();
    }
}
