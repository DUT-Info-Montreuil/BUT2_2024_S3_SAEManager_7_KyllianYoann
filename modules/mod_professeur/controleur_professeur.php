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
        $professeur_info = $this->modele->get_professeur($_SESSION['utilisateur_id']);
        $statistiques = $this->modele->get_statistiques();
        $projets = $this->modele->get_projets_responsable($_SESSION['utilisateur_id']);

        $this->vue->menu();
        $this->vue->dashboard($professeur_info, $statistiques, $projets);
    }


    private function form_creer_projet() {
        $promotions = $this->modele->get_promotions(); // Récupérer les promotions depuis la base
        $professeurs = $this->modele->get_professeurs(); // Récupérer les professeurs disponibles
        $this->vue->menu();
        $this->vue->form_creer_projet($promotions, $professeurs);
    }

    public function creer_projet() {
        $titre = $_POST['titre'] ?? null;
        $description = $_POST['description'] ?? null;
        $id_promo = $_POST['promotion'] ?? null;
        $responsables = $_POST['responsables'] ?? [];

    if (!$titre || !$description || !$id_promo || empty($responsables)) {
        die("Tous les champs sont requis !");
    }

    $success = $this->modele->creer_projet($titre, $description, $id_promo, $responsables);

    if ($success) {
        $_SESSION['success'] = "Projet créé avec succès.";
    } else {
        $_SESSION['error'] = "Erreur lors de la création du projet.";
    }

    header("Location: index.php?module=professeur&action=dashboard");
    exit();
    }


    private function form_creer_livrable() {
        $this->vue->menu();
        $this->vue->form_creer_livrable();
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

        if ($this->modele->creer_livrable($titre, $description, $date_limite, $coefficient,$isIndividuel)) {
            //en fonction de isIndividuel si c true on continue comme dab
            if($isIndividuel==0){
            $this->vue->menu();
            $this->vue->confirm_creer_livrable();
            }
            //si c false on renvoie vers un autre form pour selectionner les groupes
                // lister tt les eleves sous forme de button de type radio pr les selectionner
                //apres sa lance une fonction créer grp qui prends en parametres la liste des eleves selectionner et tt ce qui est demander (nom de grp etc) elle sera definit das modele et appeller dans le controleur
                //(pareil que 56 61)
                //1 rediriger vers un nouveau form(permet de remplir param groupe)
                //2 //(pareil que 56 61) avc dif nom
                // 3 appel la fonction programmer elle va fortement a ca $this->modele->creer_livrable($titre, $description, $date_limite, $coefficient,$isIndividuel
                //4 penser afficher pr l'eleve pour qu'ils voyent son groupe 
        if else($isIndividuel==1){
            //form groupe
             $this->vue->menu();
            $this->vue->confirm_creer_livrable();
        }
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
