<?php
require_once "modules/mod_admin/controleur_admin.php";

class ModAdmin extends ModuleGenerique {
    public function __construct() {
        parent::__construct();
        $this->controleur = new ControleurAdmin();
    }
}
