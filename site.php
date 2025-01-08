<?php

class Site {

    private $module_name;
    private $module;

    public function __construct() {
        // Liste des modules autorisés
        $modules_autorises = ["connexion", "admin", "etudiant", "professeur"];

        // Récupérer le module demandé ou rediriger par défaut sur le module "connexion"
        $this->module_name = isset($_GET['module']) ? $_GET['module'] : "connexion";

        // Vérifier si le module demandé est dans la liste des modules autorisés
        if (in_array($this->module_name, $modules_autorises)) {
            $module_path = "modules/mod_" . $this->module_name . "/module_" . $this->module_name . ".php";
            if (file_exists($module_path)) {
                require_once $module_path;
            } else {
                die("Le fichier du module est introuvable : " . htmlspecialchars($module_path));
            }
        } else {
            die("Module inexistant ou non autorisé : " . htmlspecialchars($this->module_name));
        }
    }

    public function exec_module() {
        $module_class = "Mod" . ucfirst($this->module_name); // Génère le nom de la classe du module

        // Vérifier si la classe du module existe
        if (class_exists($module_class)) {
            $this->module = new $module_class();
            $this->module->exec(); // Exécuter la logique du module
        } else {
            die("Classe du module introuvable : " . htmlspecialchars($module_class));
        }
    }

    public function get_module() {
        return $this->module;
    }
}
