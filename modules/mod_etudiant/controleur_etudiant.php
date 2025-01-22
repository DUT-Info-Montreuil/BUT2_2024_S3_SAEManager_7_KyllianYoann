<?php
require_once "modules/mod_etudiant/modele_etudiant.php";
require_once "modules/mod_etudiant/vue_etudiant.php";

class ControleurEtudiant {
    private $modele;
    private $vue;

    public function __construct() {
        $this->modele = new ModeleEtudiant();
        $this->vue = new VueEtudiant();
    }

    public function exec() {
        $action = $_GET['action'] ?? 'dashboard';

        switch ($action) {
            case 'dashboard':
                $this->afficher_dashboard();
                break;

            case 'detail_projet':
                $this->afficher_detail_projet();
                break;

            case 'detail_livrable':
                $this->afficher_detail_livrable();
                break;

            case 'soumettre_rendu':
                $this->soumettre_rendu();
                break;

            default:
                die("Action inconnue : " . htmlspecialchars($action));
        }
    }

    private function afficher_dashboard() {
        $etudiant_id = $_SESSION['utilisateur_id'];
        $etudiant = $this->modele->get_etudiant($etudiant_id); // Nouvelle méthode dans le modèle
        $projets = $this->modele->get_projets_pour_etudiant($etudiant_id);
        $this->vue->afficher_dashboard($projets, $etudiant);
    }

    private function afficher_detail_projet() {
        $id_projet = $this->valider_entree('id_projet', FILTER_VALIDATE_INT);
        if (!$id_projet) {
            $this->rediriger("index.php?module=etudiant&action=dashboard", "Projet non spécifié.", "error");
        }

        $projet = $this->modele->get_projet($id_projet);
        $promotions = $this->modele->get_promotions_projet($id_projet);
        $responsables = $this->modele->get_responsables_projet($id_projet);
        $groupes = $this->modele->get_groupes_par_projet($id_projet); 
        $livrables = $this->modele->get_livrables_par_projet($id_projet);

        $etudiant_id = $_SESSION['utilisateur_id'];
        $groupe_etudiant = $this->modele->get_groupe_etudiant($etudiant_id, $id_projet);

        $this->vue->afficher_detail_projet($projet, $livrables, $groupes, $groupe_etudiant, $promotions, $responsables);
    }

   
    private function afficher_detail_livrable() {
        $id_livrable = $this->valider_entree('id_livrable', FILTER_VALIDATE_INT);
        if (!$id_livrable) {
            $this->rediriger("index.php?module=etudiant&action=dashboard", "Livrable non spécifié.", "error");
        }

        $livrable = $this->modele->get_livrable($id_livrable);
        $fichiers = $this->modele->get_fichiers_livrable($id_livrable); // Récupère les fichiers
        $etudiant_id = $_SESSION['utilisateur_id'];
        $rendu = $this->modele->get_rendu_etudiant($id_livrable, $etudiant_id);

        $this->vue->afficher_detail_livrable($livrable, $fichiers, $rendu);
    }

    private function soumettre_rendu() {
        $id_livrable = $_POST['id_livrable'] ?? null;
        $description = $_POST['description'] ?? null;
        $etudiant_id = $_SESSION['utilisateur_id'];

        if (!$id_livrable || !$description) {
            $this->rediriger(
                "index.php?module=etudiant&action=detail_livrable&id_livrable=" . htmlspecialchars($id_livrable),
                "Tous les champs sont obligatoires.",
                "error"
            );
        }

        $chemin_fichier = $this->gerer_fichier($_FILES['fichier'] ?? null);

        $resultat = $this->modele->ajouter_rendu($id_livrable, $etudiant_id, $description, $chemin_fichier);
        if ($resultat) {
            $_SESSION['success'] = "Rendu soumis avec succès.";
        } else {
            $_SESSION['error'] = "Erreur lors de la soumission du rendu.";
        }
        $this->rediriger("index.php?module=etudiant&action=detail_livrable&id_livrable=" . htmlspecialchars($id_livrable));
        exit();
    }

    private function valider_entree($key, $type = FILTER_SANITIZE_STRING, $method = INPUT_GET) {
        return filter_input($method, $key, $type);
    }

    private function gerer_fichier($fichier) {
        if ($fichier && $fichier['error'] === UPLOAD_ERR_OK) {
            $chemin_fichier = 'uploads/' . basename($fichier['name']);
            if (!move_uploaded_file($fichier['tmp_name'], $chemin_fichier)) {
                return null;
            }
            return $chemin_fichier;
        }
        return null;
    }

    private function rediriger($url, $message = null, $type = 'success') {
        if ($message) {
            $_SESSION[$type] = $message;
        }
        header("Location: $url");
        exit();
    }
}
