<?php
require_once "composants/menu/vue_menu.php";

class ControleurMenu {
    private $vue;

    public function __construct() {
        $this->vue = new VueMenu();
    }

    public function afficher_menu() {
        // Récupérer le rôle de l'utilisateur connecté
        $role = isset($_SESSION["role"]) ? $_SESSION["role"] : "visiteur";

        // Générer le menu
        $this->vue->afficher($role);
    }
}
