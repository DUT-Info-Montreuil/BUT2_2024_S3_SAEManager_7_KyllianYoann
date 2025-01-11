<?php
require_once "modules/mod_connexion/modele_connexion.php";
require_once "modules/mod_connexion/vue_connexion.php";

class ControleurConnexion {
    private $modele;
    private $vue;
    private $action;

    public function __construct() {
        $this->modele = new ModeleConnexion();
        $this->vue = new VueConnexion();
    }

    public function exec() {
        $this->action = isset($_GET["action"]) ? $_GET["action"] : "form_connexion";

        switch ($this->action) {
            case "form_connexion":
                $this->form_connexion();
                break;
            case "verif_connexion":
                $this->verif_connexion();
                break;
            case "deconnexion":
                $this->deconnexion();
                break;
            default:
                die("Action inexistante");
        }
    }

    private function form_connexion() {
        // Affiche le formulaire de connexion
        $this->vue->form_connexion();
    }

    private function verif_connexion() {
        // Récupérer les données du formulaire
        $login = isset($_POST['login']) ? $_POST['login'] : die("Paramètre manquant : login");
        $mdp = isset($_POST['mdp']) ? $_POST['mdp'] : die("Paramètre manquant : mot de passe");

        // Vérifier si l'utilisateur existe
        $utilisateur = $this->modele->get_utilisateur($login);

        if ($utilisateur === false) {
            $_SESSION['error'] = "Utilisateur inconnu.";
            $this->vue->echec_connexion($login);
            return;
        }

        // Vérification du mot de passe
        if (password_verify($mdp, $utilisateur["mdp"])) {
            $_SESSION['utilisateur_id'] = $utilisateur["id_utilisateur"];
            $_SESSION['role'] = $utilisateur["role"];
            $this->vue->confirm_connexion($login);
        } else {
            $_SESSION['error'] = "Mot de passe incorrect.";
            $this->vue->echec_connexion($login);
        }
    }

    private function deconnexion() {
        // Détruire la session utilisateur
        session_destroy();
        $this->vue->confirm_deconnexion();
    }
}
