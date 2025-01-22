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
        case "form_creer_projet":
            $this->form_creer_projet();
            break;
        case "creer_projet": 
            $this->creer_projet();
            break;
        case "modifier_projet":
            $this->modifier_projet();
            break;
        case "mettre_a_jour_projet":
            $this->mettre_a_jour_projet();
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
        case "detail_projet":
            $this->detail_projet();
            break;
        case "gestion_groupes":
            $this->gestion_groupes();
            break;
        case "creer_groupe":
            $this->creer_groupe();
            break;
        case "modifier_groupe":
            $this->modifier_groupe();
            break;
        case "mettre_a_jour_groupe":
            $this->mettre_a_jour_groupe();
            break;
        case "supprimer_groupe":
            $this->supprimer_groupe();
            break;
        default:
            die("Action inexistante : " . htmlspecialchars($this->action));
        }
    }

    private function dashboard() {
        $professeur_info = $this->modele->get_professeur($_SESSION['utilisateur_id']);
        $statistiques = $this->modele->get_statistiques();
        $projets = $this->modele->get_projets_responsable($_SESSION['utilisateur_id']);

        $this->vue->menu();
        $this->vue->dashboard($professeur_info, $statistiques, $projets);
    }

    private function detail_projet() {
        $id_projet = $_GET['id_projet'] ?? null; // Récupère l'ID du projet depuis l'URL
        if (!$id_projet) {
            die("Projet non spécifié !");
        }

        // Récupérer les informations du projet et ses livrables
        $projet = $this->modele->get_projet($id_projet);
        $livrables = $this->modele->get_livrables_par_projet($id_projet);

        if (!$projet) {
            die("Projet introuvable !");
        }

        $this->vue->menu();
        $this->vue->detail_projet($projet, $livrables);
    }



    private function form_creer_projet() {
        $promotions = $this->modele->get_promotions(); // Récupérer les promotions depuis la base
        $professeurs = $this->modele->get_professeurs(); // Récupérer les professeurs disponibles
        $this->vue->menu();
        $this->vue->form_creer_projet($promotions, $professeurs);
    }

    private function creer_projet() {
        $titre = $_POST['titre'] ?? null;
        $description = $_POST['description'] ?? null;
        $semestre = $_POST['semestre'] ?? null;
        $coefficient = $_POST['coefficient'] ?? null;
        $promotions = $_POST['promotions'] ?? []; // Récupère les promotions sélectionnées
        $responsables = $_POST['responsables'] ?? [];

        if (!$titre || !$description || !$semestre || !$coefficient || empty($promotions) || empty($responsables)) {
            die("Tous les champs sont requis !");
        }

        $id_projet = $this->modele->creer_projet($titre, $description, $responsables, $semestre, $coefficient);

        if ($id_projet) {
            // Associer les promotions au projet
            foreach ($promotions as $id_promo) {
                $this->modele->associer_projet_promotion($id_projet, $id_promo);
            }
            $_SESSION['success'] = "Projet créé avec succès.";
        } else {
            $_SESSION['error'] = "Erreur lors de la création du projet.";
        }

        header("Location: index.php?module=professeur&action=dashboard");
        exit();
    }

    private function supprimer_projet() {
        $id_projet = $_GET['id_projet'] ?? null; // Récupérer l'ID du projet
        if (!$id_projet) {
            die("Projet non spécifié !");
        }

        // Supprimer le projet
        if ($this->modele->supprimer_projet($id_projet)) {
            $_SESSION['success'] = "Projet supprimé avec succès.";
        } else {
            $_SESSION['error'] = "Erreur lors de la suppression du projet.";
        }

        // Rediriger vers le tableau de bord après la suppression
        header("Location: index.php?module=professeur&action=dashboard");
        exit();
    }

    private function modifier_projet() {
        $id_projet = $_GET['id_projet'] ?? null; // Récupérer l'ID du projet
        if (!$id_projet) {
            die("Projet non spécifié !");
        }

        $projet = $this->modele->get_projet($id_projet); // Récupérer les données du projet
        if (!$projet) {
            die("Projet introuvable !");
        }

        $promotions = $this->modele->get_promotions(); // Récupérer les promotions
        $responsables = $this->modele->get_professeurs(); // Récupérer les professeurs

        $this->vue->menu();
        $this->vue->form_modifier_projet($projet, $promotions, $responsables); // Appeler la vue avec les données
    }

    private function mettre_a_jour_projet() {
        if (!isset($_SESSION['utilisateur_id'])) {
            header("Location: index.php?module=connexion&action=login");
            exit();
        }

        // Récupération des données du formulaire
        $id_projet = $_POST['id_projet'] ?? null;
        $titre = $_POST['titre'] ?? null;
        $description = $_POST['description'] ?? null;
        $semestre = $_POST['semestre'] ?? null;
        $coefficient = $_POST['coefficient'] ?? null;
        $promotions = $_POST['promotions'] ?? [];
        $responsables = $_POST['responsables'] ?? [];

        // Validation des données
        if (!$id_projet || !$titre || !$description || !$semestre || !$coefficient || empty($promotions) || empty($responsables)) {
            $_SESSION['error'] = "Tous les champs sont obligatoires, y compris les promotions et les responsables.";
            header("Location: index.php?module=professeur&action=dashboard");
            exit();
        }

        // Mise à jour du projet
        $result = $this->modele->mettre_a_jour_projet($id_projet, $titre, $description, $semestre, $coefficient);
        if ($result) {
            // Gestion des promotions : suppression et ajout
            $this->modele->supprimer_promotions_projet($id_projet);
            foreach ($promotions as $promo_id) {
                $this->modele->associer_projet_promotion($id_projet, $promo_id);
            }

            // Gestion des responsables : suppression et ajout
            $this->modele->supprimer_responsables_projet($id_projet);
            foreach ($responsables as $resp_id) {
                $this->modele->associer_projet_responsable($id_projet, $resp_id);
            }

            $_SESSION['success'] = "Projet mis à jour avec succès.";
        } else {
            $_SESSION['error'] = "Erreur lors de la mise à jour du projet.";
        }

        // Redirection après la mise à jour
        header("Location: index.php?module=professeur&action=dashboard");
        exit();
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
        $isIndividuel = isset($_POST["isIndividuel"]) ? $_POST["isIndividuel"] : die("Paramètre manquant");
        $isIndividuel = ($isIndividuel==="True") ? true:false;

            // Booléen IsIndividuel rajouté par Yoann
        if ($this->modele->creer_livrable($titre, $description, $date_limite, $coefficient,$isIndividuel)) {
            // Explication :  isIndividuel = true : Crée un livrable individuel normalement. 
            // isIndividuel = false : Redirige vers un formulaire pour sélectionner des étudiants. Crée un groupe avec la liste des étudiants sélectionnés. Associe le groupe au livrable.
            $this->vue->menu();
            $this->vue->confirm_creer_livrable();
        } else {
            $this->vue->menu();
            $this->vue->erreurBD();
        }
    }

    private function gestion_groupes() {
        $id_projet = $_GET['id_projet'] ?? null;
        if (!$id_projet) {
            die("Projet non spécifié !");
        }

        // Vérifie si le professeur connecté est responsable de ce projet
        $responsable = $this->modele->est_responsable_projet($id_projet, $_SESSION['utilisateur_id']);
        if (!$responsable) {
            die("Accès non autorisé. (Vérification responsable)");
        }

        // Récupérer les étudiants des promotions du projet
        $etudiants = $this->modele->get_etudiants_promotions($id_projet);
        // Récupérer les groupes existants pour ce projet
        $groupes = $this->modele->get_groupes_par_projet($id_projet);

        // Afficher la vue
        $this->vue->menu();
        $this->vue->gestion_groupes($id_projet, $etudiants, $groupes);
    }

    private function creer_groupe() {
        // Vérifiez que l'utilisateur est connecté
        if (!isset($_SESSION['utilisateur_id'])) {
            die("Accès non autorisé.");
        }

        // Récupérez les données du formulaire
        $id_projet = $_POST['id_projet'] ?? null;
        $nom_groupe = $_POST['nom_groupe'] ?? null;
        $etudiants = $_POST['etudiants'] ?? [];

        // Vérifiez que les champs requis sont remplis
        if (!$id_projet || !$nom_groupe || empty($etudiants)) {
            die("Tous les champs sont obligatoires.");
        }

        // Créez le groupe dans le modèle
        if ($this->modele->creer_groupe($nom_groupe, $id_projet, $etudiants)) {
            $_SESSION['success'] = "Groupe créé avec succès.";
        } else {
            $_SESSION['error'] = "Erreur lors de la création du groupe.";
        }

        // Redirigez vers la gestion des groupes
        header("Location: index.php?module=professeur&action=gestion_groupes&id_projet=" . urlencode($id_projet));
        exit();
    }

    private function modifier_groupe() {
        if (!isset($_SESSION['utilisateur_id'])) {
            die("Accès non autorisé.");
        }

        $id_groupe = $_GET['id_groupe'] ?? null;

        if (!$id_groupe) {
            die("Groupe non spécifié !");
        }

        // Récupérer les informations du groupe
        $groupe = $this->modele->get_groupe($id_groupe);
        $id_projet = $groupe['projet_id'] ?? null;

        if (!$groupe) {
            die("Groupe introuvable !");
        }

        // Récupérer les étudiants disponibles pour ce projet
        $etudiants_disponibles = $this->modele->get_etudiants_promotions($id_projet);

        // Récupérer les membres actuels du groupe
        $membres_actuels = $this->modele->get_membres_groupe($id_groupe);

        // Fusionner les listes pour que les membres actuels soient dans la liste des étudiants disponibles
        foreach ($membres_actuels as $membre) {
            if (!in_array($membre, $etudiants_disponibles)) {
                $etudiants_disponibles[] = $membre;
            }
        }

        // Ajouter les IDs des membres actuels au groupe pour faciliter leur sélection dans la vue
        $groupe['membres_ids'] = array_column($membres_actuels, 'id_utilisateur');

        $this->vue->menu();
        $this->vue->form_modifier_groupe($groupe, $etudiants_disponibles);
    }

    private function mettre_a_jour_groupe() {
        if (!isset($_SESSION['utilisateur_id'])) {
            die("Accès non autorisé.");
        }

        $id_groupe = $_POST['id_groupe'] ?? null;
        $id_projet = $_POST['id_projet'] ?? null;
        $nom_groupe = $_POST['nom_groupe'] ?? null;
        $etudiants = $_POST['etudiants'] ?? [];

        if (!$id_groupe || !$nom_groupe || empty($etudiants)) {
            die("Tous les champs sont obligatoires.");
        }

        // Mettre à jour le groupe dans le modèle
        if ($this->modele->mettre_a_jour_groupe($id_groupe, $nom_groupe, $etudiants)) {
            $_SESSION['success'] = "Groupe mis à jour avec succès.";
        } else {
            $_SESSION['error'] = "Erreur lors de la mise à jour du groupe.";
        }

        // Redirigez vers la gestion des groupes
        header("Location: index.php?module=professeur&action=gestion_groupes&id_projet=" . urlencode($id_projet));
        exit();
    }

    private function supprimer_groupe() {
        if (!isset($_SESSION['utilisateur_id'])) {
            die("Accès non autorisé.");
        }

        $id_groupe = $_GET['id_groupe'] ?? null;

        if (!$id_groupe) {
            die("Groupe non spécifié !");
        }

        // Supprimer le groupe
        if ($this->modele->supprimer_groupe($id_groupe)) {
            $_SESSION['success'] = "Groupe supprimé avec succès.";
        } else {
            $_SESSION['error'] = "Erreur lors de la suppression du groupe.";
        }

        // Rediriger vers la gestion des groupes
        header("Location: index.php?module=professeur&action=gestion_groupes&id_projet=" . $_GET['id_projet']);
        exit();
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
