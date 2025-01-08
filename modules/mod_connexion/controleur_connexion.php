<?php
require_once "modules/mod_connexion/vue_connexion.php";
require_once "modules/mod_connexion/modele_connexion.php";

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
        $this->vue->menu();
        $this->vue->form_connexion();
    }

    private function verif_connexion() {
        $this->vue->menu();

        $login = isset($_POST['login']) ? $_POST['login'] : die("Paramètre manquant");
        $mdp = isset($_POST['mdp']) ? $_POST['mdp'] : die("Paramètre manquant");

        $util = $this->modele->get_utilisateur($login);
        if ($util === false) {
            $this->vue->utilisateur_inconnu($login);
            return;
        }

        if (password_verify($mdp, $util["mdp"])) {
            $_SESSION['login'] = $login;
            $_SESSION['role'] = $util['role'];

            // Redirection en fonction du rôle
            switch ($util['role']) {
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
                    die("Rôle inconnu");
            }
        } else {
            $this->vue->echec_connexion($login);
        }
    }

    public function deconnexion() {
        session_start();
        session_unset();
        session_destroy();
        $this->vue->confirm_deconnexion();
    }
}
