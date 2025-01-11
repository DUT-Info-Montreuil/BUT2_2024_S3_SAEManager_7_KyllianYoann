<?php
class Connexion {
    protected static $bdd;

    /**
     * Initialise la connexion à la base de données
     */
    public static function init_connexion() {
        // Informations de connexion ( Vérifier nos identifiants qu'on met)
        $host = "sql200.infinityfree.com";
        $dbname = "if0_38080369_saemanager7";
        $user = "if0_38080369";
        $password = "Anosun77";

        try {
            // Création du Data Source Name (DSN)
            $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8";

            // Initialisation de l'instance PDO
            self::$bdd = new PDO($dsn, $user, $password);

            // Configuration des options PDO
            self::$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Exceptions sur erreurs
            self::$bdd->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); // Mode de récupération par défaut
        } catch (PDOException $e) {
            // Gestion des erreurs de connexion
            die("Erreur de connexion à la base de données : " . $e->getMessage());
        }
    }

    /**
     * Retourne l'instance PDO pour exécuter des requêtes
     *
     * @return PDO
     */
    public static function get_connexion() {
        if (self::$bdd === null) {
            self::init_connexion(); // Initialise la connexion si elle n'est pas encore faite
        }
        return self::$bdd;
    }
}
