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
        $this->action = $_GET['action'] ?? 'dashboard';

        switch ($this->action) {
            case 'dashboard':
                $this->dashboard();
                break;
            case 'form_soumettre_rendu':
                $this->form_soumettre_rendu();
                break;
            case 'soumettre_rendu':
                $this->soumettre_rendu();
                break;
            case 'details_livrable':
                $this->details_livrable();
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
        $id_livrable = $_GET['id'] ?? null;

        if ($id_livrable) {
            $livrable = $this->modele->get_livrable($id_livrable);
            $this->vue->menu();
            $this->vue->form_soumettre_rendu($livrable);
        } else {
            $livrables = $this->modele->get_livrables();
            $this->vue->menu();
            $this->vue->form_soumettre_rendu(null, $livrables);
        }
    }

    private function soumettre_rendu() {
    $id_livrable = $_POST['livrable_id'] ?? null;
    $titre_rendu = $_POST['titre'] ?? 'Rendu sans titre';
    $fichier = $_FILES['fichier'] ?? null;

    if ($id_livrable && $fichier) {
        try {
            // Appel de la méthode du modèle pour soumettre le rendu
            $result = $this->modele->soumettre_rendu($titre_rendu, $id_livrable, $fichier);

            if ($result) {
                // Redirection vers la page des détails du livrable après succès
                header("Location: index.php?module=etudiant&action=details_livrable&id=$id_livrable");
                exit;
            } else {
                echo "<p>Erreur lors de la soumission du rendu.</p>";
            }
        } catch (Exception $e) {
            echo "<p>Erreur : " . htmlspecialchars($e->getMessage()) . "</p>";
            }
        } else {
        echo "<p>Paramètres manquants pour soumettre le rendu.</p>";
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

    private function ajouter_commentaire() {
    $contenu = $_POST['contenu'] ?? null;
    $evaluation_id = $_POST['evaluation_id'] ?? null;

    if ($contenu && $evaluation_id) {
        $result = $this->modele->ajouter_commentaire($contenu, $evaluation_id);
        if ($result) {
            echo "<p>Commentaire ajouté avec succès !</p>";
        } else {
            echo "<p>Erreur lors de l'ajout du commentaire.</p>";
        }
    } else {
        echo "<p>Paramètres manquants pour ajouter le commentaire.</p>";
        }
    }
    
    private function details_livrable() { 
    // Vérifie si l'ID du livrable est passé dans l'URL
    $id_livrable = $_GET['id'] ?? null;

    if ($id_livrable) {
        // Récupère les informations du livrable
        $livrable = $this->modele->get_livrable($id_livrable);

        if ($livrable) {
            // Récupère les évaluations associées au livrable
            $evaluations = $this->modele->get_evaluations($id_livrable);

            // Récupère les feedbacks associés au livrable (utilisation de get_feedbacks_by_livrable)
            $feedbacks = $this->modele->get_feedbacks_by_livrable($id_livrable);

            // Récupère les commentaires pour chaque évaluation
            $commentaires = [];
            foreach ($evaluations as $evaluation) {
                $commentaires[$evaluation['id_evaluation']] = $this->modele->get_commentaires($evaluation['id_evaluation']);
            }

            // Affiche la vue avec les détails du livrable
            $this->vue->menu();
            $this->vue->details_livrable($livrable, $evaluations, $feedbacks, $commentaires);
        } else {
            // Si le livrable n'existe pas
            $this->vue->menu();
            echo "<p>Livrable introuvable.</p>";
        }
    } else {
        // Si l'ID du livrable n'est pas spécifié ou est invalide
        $this->vue->menu();
        echo "<p>Aucun ID de livrable spécifié.</p>";
        }
    }
}
