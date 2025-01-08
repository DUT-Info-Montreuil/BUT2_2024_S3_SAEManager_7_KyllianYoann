<?php
class VueGenerique {

    public function __construct() {
        ob_start(); // Démarrage du buffer de sortie
    }

    /**
     * Méthode pour afficher une vue avec un template optionnel
     *
     * @param string $content La vue à afficher
     */
    public function render($content) {
        // Capture le contenu généré
        $buffer = ob_get_clean();

        // Inclut un template si on a besoin
        include "template.php"; // fait attention si on a la template.php qui existe
    }

    /**
     * Méthode pour afficher un message global d'erreur ou de succès
     *
     * @param string $message
     * @param string $type (success|error)
     */
    public function displayMessage($message, $type = 'success') {
        echo "<div class='message {$type}'>" . htmlspecialchars($message) . "</div>";
    }
}
