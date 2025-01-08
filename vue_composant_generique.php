<?php

class VueCompGenerique {

    protected $affichage;

    public function __construct() {
        $this->affichage = ""; // Initialisation de l'affichage
    }

    /**
     * Retourne le contenu actuel de l'affichage
     *
     * @return string
     */
    public function getAffichage() {
        return $this->affichage;
    }

    /**
     * Ajoute du contenu à l'affichage existant
     *
     * @param string $contenu Le contenu à ajouter
     */
    public function ajouterAffichage($contenu) {
        $this->affichage .= $contenu; // Concatène le contenu
    }

    /**
     * Remplace le contenu actuel de l'affichage
     *
     * @param string $contenu Le nouveau contenu
     */
    public function setAffichage($contenu) {
        $this->affichage = $contenu; // Remplace entièrement le contenu
    }

    /**
     * Affiche directement le contenu actuel
     */
    public function afficher() {
        echo $this->affichage;
    }
}
