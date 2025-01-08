<?php
require_once "modules/mod_connexion/controleur_connexion.php";

class ModConnexion extends ModuleGenerique {

    public function __construct() {
        parent::__construct();
        
        // Vérification si l'utilisateur est déjà connecté
        if (isset($_SESSION['login'])) {
            switch ($_SESSION['role']) {
                case 'admin':
                    header('Location: /modules/mod_admin/vue_admin.php');
                    break;
                case 'professeur':
                    header('Location: /modules/mod_professeur/vue_professeur.php');
                    break;
                case 'etudiant':
                    header('Location: /modules/mod_etudiant/vue_etudiant.php');
                    break;
                default:
                    unset($_SESSION['login']);
                    session_destroy();
                    header('Location: /modules/mod_connexion/vue_connexion.php');
                    break;
            }
            exit;
        }

        $this->controleur = new ControleurConnexion();
    }
}
