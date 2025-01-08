<?php
require_once "modules/mod_etudiant/modele_etudiant.php";
require_once "modules/mod_etudiant/vue_etudiant.php";

class ControleurEtudiant {
    private $modele;
    private $vue;
    private $action;

    public function __construct() {
        $this->modele = new ModeleEtudiant();
        $this->vue = new VueEtudiant();
    }

    public function exec() {
        $this->action = isset($_GET["action"]) ? $_GET["action"] : "dashboard";

        switch ($this->action) {
            case "dashboard":
                $this->dashboard();
                break;
            case "form_soumettre_rendu":
                $this->form_soumettre_rendu();
                break;
            case "soumettre_rendu":
                $this->soumettre_rendu();
                break;
            case "consulter_feedbacks":
                $this->consulter_feedbacks();
                break;
            default:
                die("Action inexistante");
        }
    }

    private function dashboard() {
        $livrables = $this->modele->get_livrables();
        $this->vue->menu();
        $this->vue->dashboard($livrables);
    }

    private function form_soumettre_rendu() {
        $livrables = $this->modele->get_livrables();
        $this->vue->menu();
        $this->vue->form_soumettre_rendu($livrables);
    }

    private function soumettre_rendu() {
        $titre = isset($_POST["titre"]) ? $_POST["titre"] : die("Paramètre manquant");
        $livrable_id = isset($_POST["livrable_id"]) ? $_POST["livrable_id"] : die("Paramètre manquant");
        $fichier = isset($_FILES["fichier"]) ? $_FILES["fichier"] : die("Fichier manquant");

        $chemin = "uploads/" . basename($fichier["name"]);
        if (move_uploaded_file($fichier["tmp_name"], $chemin)) {
            if ($this->modele->soumettre_rendu($titre, $livrable_id, $chemin)) {
                $this->vue->menu();
                $this->vue->confirm_soumettre_rendu();
            } else {
                $this->vue->menu();
                $this->vue->erreurBD();
            }
        } else {
            $this->vue->menu();
            $this->vue->erreur_upload();
        }
    }

    private function consulter_feedbacks() {
        $feedbacks = $this->modele->get_feedbacks($_SESSION["user_id"]);
        $this->vue->menu();
        $this->vue->consulter_feedbacks($feedbacks);
    }
}
