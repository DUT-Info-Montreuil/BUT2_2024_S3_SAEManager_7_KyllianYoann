<?php

class ComposantGenerique {

    protected $controleur; // Instance du contrôleur associé au composant

    public function __construct($controleur = null) {
        // Initialisation du contrôleur si fourni
        if ($controleur !== null) {
            $this->setControleur($controleur);
        }
    }

    /**
     * Définit le contrôleur associé au composant
     *
     * @param object $controleur Instance du contrôleur
     */
    public function setControleur($controleur) {
        if (method_exists($controleur, 'getVue')) {
            $this->controleur = $controleur;
        } else {
            throw new Exception("Le contrôleur fourni n'a pas de méthode 'getVue'.");
        }
    }

    /**
     * Récupère l'affichage généré par la vue du contrôleur
     *
     * @return string Contenu HTML généré par la vue
     * @throws Exception Si le contrôleur ou la vue n'est pas défini
     */
    public function getAffichage() {
        if ($this->controleur === null) {
            throw new Exception("Aucun contrôleur n'est associé à ce composant.");
        }

        $vue = $this->controleur->getVue();
        if ($vue === null || !method_exists($vue, 'getAffichage')) {
            throw new Exception("La vue associée au contrôleur n'est pas valide ou n'a pas de méthode 'getAffichage'.");
        }

        return $vue->getAffichage();
    }
}
