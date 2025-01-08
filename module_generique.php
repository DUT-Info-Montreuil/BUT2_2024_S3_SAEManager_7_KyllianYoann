<?php

class ModuleGenerique {
    private $affichage; // Contenu généré par le module
    protected $title;   // Titre de la page/module
    protected $controleur; // Contrôleur spécifique au module

    public function __construct() {
        $this->title = ""; // Titre par défaut
        $this->affichage = ""; // Affichage vide par défaut
        ob_start(); // Démarre le buffer de sortie
    }

    /**
     * Exécute le contrôleur et capture l'affichage généré
     */
    public function exec() {
        $this->controleur->exec(); // Appelle la méthode exec du contrôleur
        $this->affichage = ob_get_clean(); // Capture le contenu généré
    }

    /**
     * Retourne le contenu généré par le module
     *
     * @return string
     */
    public function get_affichage() {
        return $this->affichage;
    }

    /**
     * Définit le titre du module
     *
     * @param string $title
     */
    public function set_title($title) {
        $this->title = $title;
    }

    /**
     * Retourne le titre du module
     *
     * @return string
     */
    public function get_title() {
        return $this->title;
    }

    /**
     * Ajoute du contenu supplémentaire à l'affichage
     *
     * @param string $contenu
     */
    public function ajouter_affichage($contenu) {
        $this->affichage .= $contenu; // Ajoute au contenu existant
    }
}
