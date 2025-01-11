<?php
require_once "modules/mod_professeur/modele_professeur.php";
require_once "modules/mod_professeur/vue_professeur.php";

class ControleurProfesseur {
    private $modele;
    private $vue;
    private $action;

    public function __construct() {
        $this->modele = new ModeleProfesseur();
        $this->vue = new VueProfesseur();
    }

    public function exec() {
        $this->action = isset($_GET["action"]) ? $_GET["action"] : "dashboard";

        switch ($this->action) {
            case "dashboard":
                $this->dashboard();
                break;
            case "form_creer_livrable":
                $this->form_creer_livrable();
                break;
            case "creer_livrable":
                $this->creer_livrable();
                break;
            case "consulter_rendus":
                $this->consulter_rendus();
                break;
            case "ajouter_feedback":
                $this->ajouter_feedback();
                break;
            default:
                die("Action inexistante");
        }
    }

    private function dashboard() {
        // Obtenir les informations du professeur
        $professeur_info = $this->modele->get_professeur($_SESSION['utilisateur_id']);
        
        // Obtenir les statistiques
        $statistiques = $this->modele->get_statistiques();

        $this->vue->menu();
        $this->vue->dashboard($professeur_info, $statistiques);
    }

    private function form_creer_livrable() {
        $this->vue->menu();
        $this->vue->form_creer_livrable();
    }

    private function creer_livrable() {
        $titre = isset($_POST["titre"]) ? $_POST["titre"] : die("Paramètre manquant");
        $description = isset($_POST["description"]) ? $_POST["description"] : die("Paramètre manquant");
        $date_limite = isset($_POST["date_limite"]) ? $_POST["date_limite"] : die("Paramètre manquant");
        $coefficient = isset($_POST["coefficient"]) ? $_POST["coefficient"] : die("Paramètre manquant");

        if ($this->modele->creer_livrable($titre, $description, $date_limite, $coefficient)) {
            $this->vue->menu();
            $this->vue->confirm_creer_livrable();
        } else {
            $this->vue->menu();
            $this->vue->erreurBD();
        }
    }

    private function consulter_rendus() {
        $livrables = $this->modele->get_livrables();
        $rendus = $this->modele->get_rendus();

        $this->vue->menu();
        $this->vue->consulter_rendus($livrables, $rendus);
    }

    private function ajouter_feedback() {
        $rendu_id = isset($_POST["rendu_id"]) ? $_POST["rendu_id"] : die("Paramètre manquant");
        $feedback = isset($_POST["feedback"]) ? $_POST["feedback"] : die("Paramètre manquant");

        if ($this->modele->ajouter_feedback($rendu_id, $feedback)) {
            $this->vue->menu();
            $this->vue->confirm_ajouter_feedback();
        } else {
            $this->vue->menu();
            $this->vue->erreurBD();
        }
    }
}
