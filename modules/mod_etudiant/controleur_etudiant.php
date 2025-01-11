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
        // Récupère les données pour la vue dashboard
        if (!isset($_SESSION["utilisateur_id"])) {
            die("Erreur : Vous devez être connecté pour accéder au tableau de bord.");
        }
        $utilisateur_id = $_SESSION["utilisateur_id"];

        $livrables = $this->modele->get_livrables();
        $groupe = $this->modele->get_groupe($utilisateur_id);
        $coefficients = $this->modele->get_coefficients();

        // Affiche le tableau de bord
        $this->vue->dashboard($livrables, $groupe, $coefficients);
    }

    private function form_soumettre_rendu() {
        // Récupère les livrables pour le formulaire
        $livrables = $this->modele->get_livrables();
        $this->vue->form_soumettre_rendu($livrables);
    }

    private function soumettre_rendu() {
        // Récupérer les données du formulaire
        $titre = isset($_POST["titre"]) ? $_POST["titre"] : die("Paramètre manquant : titre");
        $livrable_id = isset($_POST["livrable_id"]) ? $_POST["livrable_id"] : die("Paramètre manquant : livrable_id");
        $fichier = isset($_FILES["fichier"]) ? $_FILES["fichier"] : die("Paramètre manquant : fichier");

        // Déplacer le fichier vers le dossier uploads
        $chemin = "uploads/" . basename($fichier["name"]);
        if (move_uploaded_file($fichier["tmp_name"], $chemin)) {
            if ($this->modele->soumettre_rendu($titre, $livrable_id, $chemin)) {
                $this->vue->confirm_soumettre_rendu();
            } else {
                $this->vue->erreurBD();
            }
        } else {
            $this->vue->erreur_upload();
        }
    }

    private function consulter_feedbacks() {
        // Vérifie si l'utilisateur est connecté
        if (!isset($_SESSION["utilisateur_id"])) {
            die("Erreur : Vous devez être connecté pour consulter vos feedbacks.");
        }
        $utilisateur_id = $_SESSION["utilisateur_id"];
        $feedbacks = $this->modele->get_feedbacks($utilisateur_id);

        // Affiche les feedbacks
        $this->vue->consulter_feedbacks($feedbacks);
    }
}
