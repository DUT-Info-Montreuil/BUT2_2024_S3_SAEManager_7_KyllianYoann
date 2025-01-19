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
            case "form_modifier_utilisateur":
                $this->form_modifier_utilisateur();
                break;
            case "modifier_utilisateur":
                $this->modifier_utilisateur();
                break;
            case "liste_utilisateurs":
                $this->liste_utilisateurs();
                break;
            default:
                die("Action inexistante");
        }
    }

    private function dashboard() {
        $statistiques = $this->modele->get_statistiques();
        $this->vue->menu();
        $this->vue->dashboard($statistiques);
    }

    private function form_creer_utilisateur() {
        $this->vue->menu();
        $this->vue->form_creer_utilisateur();
    }

    private function creer_utilisateur() {
        $nom = isset($_POST["nom"]) ? $_POST["nom"] : die("Paramètre nom manquant");
        $prenom = isset($_POST["prenom"]) ? $_POST["prenom"] : die("Paramètre prenom manquant");
        $login = isset($_POST["login"]) ? $_POST["login"] : die("Paramètre login manquant");
        $mdp = isset($_POST["mdp"]) ? $_POST["mdp"] : die("Paramètre mdp manquant");
        $role = isset($_POST["role"]) ? $_POST["role"] : die("Paramètre role manquant");

        if ($this->modele->creer_utilisateur($nom, $prenom, $login, $mdp, $role)) {
            $this->vue->menu();
            $this->vue->confirm_creer_utilisateur();
        } else {
            $this->vue->menu();
            $this->vue->erreurBD();
        }
    }

    private function supprimer_utilisateur() {
        $id_utilisateur = isset($_GET["id"]) ? $_GET["id"] : die("Paramètre id manquant");

        if ($this->modele->supprimer_utilisateur($id_utilisateur)) {
            $this->vue->menu();
            $this->vue->confirm_supprimer_utilisateur();
        } else {
            $this->vue->menu();
            $this->vue->erreurBD();
        }
    }

    private function form_modifier_utilisateur() {
    $id_utilisateur = isset($_GET["id"]) ? $_GET["id"] : die("Paramètre id manquant");
    $utilisateur = $this->modele->get_utilisateur($id_utilisateur);
     if ($utilisateur) {
        $this->vue->menu();
        $this->vue->form_modifier_utilisateur($utilisateur);
    } else {
        $this->vue->menu();
        $this->vue->erreurBD();
        }
    }

    private function modifier_utilisateur() {
    $id_utilisateur = isset($_GET["id"]) ? $_GET["id"] : die("Paramètre id manquant");
    $nom = isset($_POST["nom"]) ? $_POST["nom"] : die("Paramètre nom manquant");
    $prenom = isset($_POST["prenom"]) ? $_POST["prenom"] : die("Paramètre prenom manquant");
    $login = isset($_POST["login"]) ? $_POST["login"] : die("Paramètre login manquant");
    $role = isset($_POST["role"]) ? $_POST["role"] : die("Paramètre role manquant");
    $mdp = isset($_POST["mdp"]) && $_POST["mdp"] !== "" ? $_POST["mdp"] : null;

    if ($this->modele->modifier_utilisateur($id_utilisateur, $nom, $prenom, $login, $mdp ?? null, $role)) {
        $this->vue->menu();
        echo "<p>L'utilisateur a été modifié avec succès.</p>";
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
