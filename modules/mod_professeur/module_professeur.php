<?php
require_once "modules/mod_professeur/controleur_professeur.php";

class ModProfesseur extends ModuleGenerique {
    public function __construct() {
        parent::__construct();
        $this->controleur = new ControleurProfesseur();
    }
}
