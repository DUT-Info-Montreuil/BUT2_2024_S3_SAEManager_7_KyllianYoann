<?php
require_once "modules/mod_admin/modele_admin.php";
require_once "modules/mod_admin/vue_admin.php";

class ControleurAdmin {
    private $modele;
    private $vue;
    private $action;

    public function __construct() {
        $this->modele = new ModeleAdmin();
        $this->vue = new VueAdmin();
    }

    public function exec() {
        $this->action = isset($_GET["action"]) ? $_GET["action"] : "dashboard";

        switch ($this->action) {
            case "dashboard":
                $this->dashboard();
                break;
            case "form_creer_utilisateur":
                $this->form_creer_utilisateur();
                break;
            case "creer_utilisateur":
                $this->creer_utilisateur();
                break;
            case "supprimer_utilisateur":
                $this->supprimer_utilisateur();
                break;
            case "liste_utilisateurs":
                $this->liste_utilisateurs();
                break;
            default:
                die("Action inexistante");
        }
    }

    private function dashboard() {
        $this->vue->menu();
        $this->vue->dashboard();
    }

    private function form_creer_utilisateur() {
        $this->vue->menu();
        $this->vue->form_creer_utilisateur();
    }

    private function creer_utilisateur() {
        $nom = isset($_POST["nom"]) ? $_POST["nom"] : die("Paramètre manquant");
        $prenom = isset($_POST["prenom"]) ? $_POST["prenom"] : die("Paramètre manquant");
        $email = isset($_POST["email"]) ? $_POST["email"] : die("Paramètre manquant");
        $mot_de_passe = isset($_POST["mot_de_passe"]) ? $_POST["mot_de_passe"] : die("Paramètre manquant");
        $role = isset($_POST["role"]) ? $_POST["role"] : die("Paramètre manquant");

        if ($this->modele->creer_utilisateur($nom, $prenom, $email, $mot_de_passe, $role)) {
            $this->vue->menu();
            $this->vue->confirm_creer_utilisateur();
        } else {
            $this->vue->menu();
            $this->vue->erreurBD();
        }
    }

    private function supprimer_utilisateur() {
        $id_utilisateur = isset($_GET["id"]) ? $_GET["id"] : die("Paramètre manquant");

        if ($this->modele->supprimer_utilisateur($id_utilisateur)) {
            $this->vue->menu();
            $this->vue->confirm_supprimer_utilisateur();
        } else {
            $this->vue->menu();
            $this->vue->erreurBD();
        }
    }

    private function liste_utilisateurs() {
        $utilisateurs = $this->modele->get_utilisateurs();
        $this->vue->menu();
        $this->vue->liste_utilisateurs($utilisateurs);
    }
}
