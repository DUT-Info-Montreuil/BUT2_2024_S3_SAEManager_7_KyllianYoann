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
        case "supprimer_projet":
            if (isset($_GET['id_projet'])) {
                $id_projet = intval($_GET['id_projet']);
                $this->supprimer_projet($id_projet);
            } else {
                $_SESSION['error'] = "ID du projet manquant.";
                header("Location: index.php?module=professeur&action=dashboard");
            }
            break;
        case "gestion_groupes":
            $this->gestion_groupes();
            break;
        case "gestion_evaluations":
            $this->gestion_evaluations();
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
        case 'detail_livrable':
            if (isset($_GET['id_livrable'])) {
                $id_livrable = intval($_GET['id_livrable']);
                $livrable = $this->modele->get_livrable($id_livrable);
                $fichiers = $this->modele->get_fichiers_livrable($id_livrable); // Récupère les fichiers associés
                if ($livrable) {
                    $this->vue->detail_livrable($livrable, $fichiers);
                } else {
                    $_SESSION['error'] = "Livrable introuvable.";
                    header("Location: index.php?module=professeur&action=dashboard");
                }
            } else {
                $_SESSION['error'] = "ID du livrable manquant.";
                header("Location: index.php?module=professeur&action=dashboard");
            }
            break;
        case 'modifier_livrable':
            $this->modifier_livrable();
            break;
        case 'valider_modification_livrable': 
            $this->valider_modification_livrable();
            break;
        case 'supprimer_livrable': 
            if (isset($_POST['id_livrable'])) {
                $this->supprimer_livrable();
            } else {
                $_SESSION['error'] = "ID du livrable manquant.";
                header("Location: index.php?module=professeur&action=dashboard");
            }
            break;
        case "gestion_evaluations":
            $this->gestion_evaluations();
            break;
        case 'form_creer_evaluation':
            $this->form_creer_evaluation();
            break;
        case 'creer_evaluation':
            $this->creer_evaluation();
            break;
        case "detail_evaluation":
            if (isset($_GET['id_evaluation'])) {
                $id_evaluation = intval($_GET['id_evaluation']);
                $this->detail_evaluation($id_evaluation);
            } else {
                $_SESSION['error'] = "ID de l'évaluation manquant.";
                header("Location: index.php?module=professeur&action=dashboard");
            }
            break;
        case "form_modifier_evaluation":
            if (isset($_GET['id_evaluation'])) {
                $id_evaluation = intval($_GET['id_evaluation']);
                $this->form_modifier_evaluation($id_evaluation);
            } else {
                $_SESSION['error'] = "ID de l'évaluation manquant.";
                header("Location: index.php?module=professeur&action=dashboard");
            }
            break;
        case "modifier_evaluation":
            if (isset($_POST['id_evaluation'])) {
                $this->modifier_evaluation($_POST);
            } else {
                $_SESSION['error'] = "ID de l'évaluation manquant.";
                header("Location: index.php?module=professeur&action=dashboard");
            }
            break;
        case "supprimer_evaluation":
            if (isset($_GET['id_evaluation'])) {
                $id_evaluation = intval($_GET['id_evaluation']);
                $this->supprimer_evaluation($id_evaluation);
            } else {
                $_SESSION['error'] = "ID de l'évaluation manquant.";
                header("Location: index.php?module=professeur&action=dashboard");
            }
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

    public function detail_projet() {
        $this->vue->menu();
        $id_projet = $_GET['id_projet'] ?? null;

        if (!$id_projet) {
            header("Location: index.php?module=professeur&action=dashboard");
            exit();
        }
        // Récupérer les détails du projet
        $projet = $this->modele->get_projet($id_projet);
        if (!$projet) {
            $_SESSION['error'] = "Projet introuvable.";
            header("Location: index.php?module=professeur&action=dashboard");
            exit();
        }
        // Récupérer les livrables associés
        $livrables = $this->modele->get_livrables_par_projet($id_projet);
        // Appeler la vue pour afficher les détails du projet
        $this->vue->detail_projet($projet, $livrables);
    }

    public function detail_livrable() {
        // Vérifier si un identifiant de livrable est passé
        $id_livrable = $_GET['id_livrable'] ?? null;
        if (!$id_livrable) {
            $_SESSION['error'] = "Aucun identifiant de livrable fourni.";
            header("Location: index.php?module=professeur&action=dashboard");
            exit();
        }
        // Récupérer les détails du livrable à partir du modèle
        $livrable = $this->modele->get_livrable($id_livrable);
        if (!$livrable) {
            $_SESSION['error'] = "Livrable introuvable.";
            header("Location: index.php?module=professeur&action=dashboard");
            exit();
        }
        // Afficher la vue pour les détails du livrable
        $this->vue->menu();
        $this->vue->detail_livrable($livrable);
    }
    
    private function form_creer_livrable() {
        // Récupérer les projets pour lesquels le professeur est responsable
        $projets_responsable = $this->modele->get_projets_responsable($_SESSION['utilisateur_id']);
        // Afficher le menu et appeler la vue correspondante
        $this->vue->menu();
        $this->vue->form_creer_livrable($projets_responsable);
    }

    public function modifier_livrable() {
        // Vérifie si l'ID du livrable est passé dans les paramètres
        if (!isset($_GET['id_livrable']) || empty($_GET['id_livrable'])) {
        $_SESSION['error'] = "ID du livrable manquant.";
        header("Location: index.php?module=professeur&action=dashboard");
        exit();
        }

        $id_livrable = intval($_GET['id_livrable']);

        // Vérifie si le formulaire a été soumis
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $titre = $_POST['titre'] ?? null;
        $description = $_POST['description'] ?? null;
        $date_limite = $_POST['date_limite'] ?? null;
        $coefficient = $_POST['coefficient'] ?? null;
        $projet_id = $_POST['projet_id'] ?? null;
        $isIndividuel = isset($_POST['is_group']) ? 0 : 1;

        // Validation des champs obligatoires
        if (!$titre || !$description || !$date_limite || !$coefficient || !$projet_id) {
            $_SESSION['error'] = "Tous les champs sont obligatoires.";
            header("Location: index.php?module=professeur&action=modifier_livrable&id_livrable=$id_livrable");
            exit();
        }

        // Mise à jour des informations du livrable dans la base de données
        $resultat = $this->modele->modifier_livrable($id_livrable, $titre, $description, $date_limite, $coefficient, $isIndividuel, $projet_id);
        if ($resultat) {
            // Suppression des fichiers sélectionnés
            if (!empty($_POST['fichiers_supprimes'])) {
                foreach ($_POST['fichiers_supprimes'] as $id_fichier) {
                    if (is_numeric($id_fichier)) {
                        $this->modele->supprimer_fichier((int)$id_fichier);
                    }
                }
            }

            // Ajout de nouveaux fichiers
            if (!empty($_FILES['fichiers']['name'][0])) {
                $this->upload_fichiers($id_livrable, $_FILES['fichiers']);
            }

            // Confirmation de la modification
            $_SESSION['success'] = "Livrable modifié avec succès.";
            header("Location: index.php?module=professeur&action=detail_livrable&id_livrable=$id_livrable");
            exit();
        } else {
            $_SESSION['error'] = "Erreur lors de la modification du livrable.";
            header("Location: index.php?module=professeur&action=modifier_livrable&id_livrable=$id_livrable");
            exit();
            }
        }

        // Récupération des informations pour afficher le formulaire
        $livrable = $this->modele->get_livrable($id_livrable);
        if (!$livrable) {
        $_SESSION['error'] = "Livrable introuvable.";
        header("Location: index.php?module=professeur&action=dashboard");
        exit();
        }

        // Récupération des fichiers associés au livrable
        $fichiers = $this->modele->get_fichiers_livrable($id_livrable);

        // Récupération des projets associés au professeur
        $projets_responsable = $this->modele->get_projets_responsable($_SESSION['utilisateur_id']);

        // Appel à la vue pour afficher le formulaire
        $this->vue->form_modifier_livrable($livrable, $fichiers, $projets_responsable);
    }


    public function valider_modification_livrable() {
        $id_livrable = $_POST['id_livrable'] ?? null;
        $titre = $_POST['titre'] ?? null;
        $description = $_POST['description'] ?? null;
        $date_limite = $_POST['date_limite'] ?? null;
        $coefficient = $_POST['coefficient'] ?? null;
        $projet_id = $_POST['projet_id'] ?? null;
        $isIndividuel = isset($_POST['is_group']) ? 0 : 1;
        if (!$id_livrable || !$titre || !$description || !$date_limite || !$coefficient || !$projet_id) {
            $_SESSION['error'] = "Tous les champs sont obligatoires.";
            header("Location: index.php?module=professeur&action=modifier_livrable&id_livrable=$id_livrable");
            exit();
        }
        $result = $this->modele->modifier_livrable($id_livrable, $titre, $description, $date_limite, $coefficient, $isIndividuel, $projet_id);
        if ($result) {
            $_SESSION['success'] = "Livrable modifié avec succès.";
        } else {
            $_SESSION['error'] = "Erreur lors de la modification du livrable.";
        }
        header("Location: index.php?module=professeur&action=dashboard");
        exit();
    }

    public function supprimer_livrable() {
        // Vérifie si l'ID du livrable est passé en POST
        if (!isset($_POST['id_livrable']) || empty($_POST['id_livrable'])) {
            $_SESSION['error'] = "ID du livrable manquant.";
            header("Location: index.php?module=professeur&action=dashboard");
            exit();
        }
        $id_livrable = intval($_POST['id_livrable']);
        // Appelle le modèle pour supprimer le livrable
        if ($this->modele->supprimer_livrable($id_livrable)) {
            $_SESSION['success'] = "Le livrable a été supprimé avec succès.";
        } else {
            $_SESSION['error'] = "Erreur lors de la suppression du livrable.";
        }
        // Redirection vers le tableau de bord ou autre page pertinente
        header("Location: index.php?module=professeur&action=dashboard");
        exit();
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

    public function creer_livrable() {
        // Récupération des données du formulaire
        $titre = $_POST['titre'] ?? null;
        $description = $_POST['description'] ?? null;
        $date_limite = $_POST['date_limite'] ?? null;
        $coefficient = $_POST['coefficient'] ?? null;
        $projet_titre = $_POST['projet_titre'] ?? null;
        $isIndividuel = isset($_POST['is_group']) ? 0 : 1;

        // Validation des champs obligatoires
        if (!$titre || !$description || !$date_limite || !$coefficient || !$projet_titre) {
        $_SESSION['error'] = "Tous les champs sont obligatoires.";
        $form_data = [
            'titre' => $titre,
            'description' => $description,
            'date_limite' => $date_limite,
            'coefficient' => $coefficient,
            'projet_titre' => $projet_titre,
            'is_group' => !$isIndividuel,
        ];
        $this->form_creer_livrable($this->modele->get_projets_responsable($_SESSION['utilisateur_id']), $form_data);
        return;
        }

        // Récupération de l'ID du projet via le titre
        $projet = $this->modele->get_projet_par_titre($projet_titre);
        if (!$projet) {
        $_SESSION['error'] = "Projet introuvable.";
        header("Location: index.php?module=professeur&action=dashboard");
        exit();
        }
        $projet_id = $projet['id_projet'];

        // Ajout du livrable
        $id_livrable = $this->modele->creer_livrable($titre, $description, $date_limite, $coefficient, $isIndividuel, $projet_id);
        if ($id_livrable) {
        // Gestion des fichiers (s'il y en a)
        if (!empty($_FILES['fichiers']['name'][0])) {
            $this->upload_fichiers($id_livrable, $_FILES['fichiers']);
        }

        // Utilisation de la fonction confirm_creer_livrable pour afficher le message de succès
        $this->confirm_creer_livrable();
        } else {
        $_SESSION['error'] = "Erreur lors de la création du livrable.";
        $this->form_creer_livrable($this->modele->get_projets_responsable($_SESSION['utilisateur_id']), [
            'titre' => $titre,
            'description' => $description,
            'date_limite' => $date_limite,
            'coefficient' => $coefficient,
            'projet_titre' => $projet_titre,
            'is_group' => !$isIndividuel,
        ]);
        }
    }

    public function confirm_creer_livrable() {
        $this->vue->menu();
        echo "<p style='color: green; text-align: center;'>Le livrable a été créé avec succès.</p>";
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

    private function upload_fichiers($id_livrable, $fichiers) {
        foreach ($fichiers['name'] as $index => $nom_fichier) {
        // Chemin pour sauvegarder les fichiers
        $chemin_fichier = "uploads/livrables/" . uniqid() . "_" . basename($nom_fichier);

        // Déplacement du fichier téléchargé
        if (move_uploaded_file($fichiers['tmp_name'][$index], $chemin_fichier)) {
            // Appeler la méthode du modèle pour associer le fichier au livrable
            $this->modele->ajouter_fichier_livrable($id_livrable, $nom_fichier, $chemin_fichier);
        } else {
            // Gérer les erreurs si le fichier n'est pas déplacé correctement
            $_SESSION['error'] = "Erreur lors du téléchargement du fichier : $nom_fichier";
            }
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

    public function gestion_evaluations() {
        $id_projet = $_GET['id_projet'] ?? null;
        if (!$id_projet) {
        die("Projet non spécifié.");
        }

        $responsable = $this->modele->est_responsable_projet($id_projet, $_SESSION['utilisateur_id']);
        if (!$responsable) {
            die("Accès non autorisé. (Vérification responsable)");
        }

        $this->vue->menu();
        $etudiants = $this->modele->get_etudiants_promotions($id_projet);
        $groupes = $this->modele->get_groupes_par_projet($id_projet);
        $evaluations = $this->modele->get_evaluations_par_projet($id_projet); // Méthode à créer
        $projet = $this->modele->get_projet($id_projet); // Récupère les infos du projet
        $this->vue->gestion_evaluations($projet, $evaluations); // Méthode vue
    }

    public function form_creer_evaluation() {
        $id_projet = $_GET['id_projet'] ?? null;
        if (!$id_projet) {
            die("Projet non spécifié.");
        }
        $groupes = $this->modele->get_groupes_par_projet($id_projet);
        $rendus = $this->modele->get_rendus_par_projet($id_projet);
        $projet = $this->modele->get_projet($id_projet);
        $this->vue->menu();
        $this->vue->form_creer_evaluation($projet, $groupes, $rendus);
    }

    public function creer_evaluation() {
        $data = [
        'titre' => $_POST['titre'] ?? null,
        'note' => $_POST['note'] ?? null,
        'coefficient' => $_POST['coefficient'] ?? 1.0,
        'description' => $_POST['description'] ?? null,
        'type' => $_POST['type'] ?? 'individuel',
        'id_projet' => $_POST['id_projet'] ?? null,
        'id_groupe' => $_POST['id_groupe'] ?? null,
        'rendu_id' => $_POST['rendu_id'] ?? null,
        'evaluateur_id' => $_SESSION['id_utilisateur']
        ];

        if ($this->modele->creer_evaluation($data)) {
        $_SESSION['success'] = "Évaluation créée avec succès.";
        } else {
        $_SESSION['error'] = "Erreur lors de la création de l'évaluation.";
        }

        header("Location: index.php?module=professeur&action=gestion_evaluations&id_projet=" . $data['id_projet']);
        exit();
    }

    public function detail_evaluation() {
        $id_evaluation = $_GET['id_evaluation'] ?? null;

        if (!$id_evaluation) {
            $_SESSION['error'] = "ID de l'évaluation manquant.";
            header("Location: index.php?module=professeur&action=gestion_evaluations");
            exit();
        }
        $evaluation = $this->modele->get_evaluation($id_evaluation);
        if (!$evaluation) {
            $_SESSION['error'] = "Évaluation introuvable.";
            header("Location: index.php?module=professeur&action=gestion_evaluations");
            exit();
        }
        $this->vue->menu();
        $this->vue->detail_evaluation($evaluation);
    }

    public function form_modifier_evaluation() {
        $id_evaluation = $_GET['id_evaluation'] ?? null;
        if (!$id_evaluation) {
            $_SESSION['error'] = "ID de l'évaluation manquant.";
            header("Location: index.php?module=professeur&action=gestion_evaluations");
            exit();
        }
        $evaluation = $this->modele->get_evaluation($id_evaluation);
        if (!$evaluation) {
            $_SESSION['error'] = "Évaluation introuvable.";
            header("Location: index.php?module=professeur&action=gestion_evaluations");
            exit();
        }
        $this->vue->menu();
        $this->vue->form_modifier_evaluation($evaluation);
    }

    public function supprimer_evaluation() {
        $id_evaluation = $_GET['id_evaluation'] ?? null;

        if (!$id_evaluation) {
            $_SESSION['error'] = "ID de l'évaluation manquant.";
            header("Location: index.php?module=professeur&action=gestion_evaluations");
            exit();
        }
        if ($this->modele->supprimer_evaluation($id_evaluation)) {
            $_SESSION['success'] = "Évaluation supprimée avec succès.";
        } else {
            $_SESSION['error'] = "Erreur lors de la suppression de l'évaluation.";
        }
        header("Location: index.php?module=professeur&action=gestion_evaluations");
        exit();
    }
}
