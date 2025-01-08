<?php
class Connexion {
    protected static $bdd;

    /**
     * Initialise la connexion à la base de données
     */
    public static function init_connexion() {
        // Informations de connexion ( Vérifier nos identifiants qu'on met)
        $host = "database-etudiants.iut.univ-paris8.fr";
        $dbname = "dutinfopw201679";
        $user = "dutinfopw201679";
        $password = "vevazaha";

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
