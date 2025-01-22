<?php
class ModeleEtudiant extends Connexion {
    
    public function ajouter_rendu($id_livrable, $etudiant_id, $description, $chemin_fichier) {
        try {
            $req = "
                INSERT INTO Rendu (id_livrable, utilisateur_id, description, chemin_fichier, date_rendu) 
                VALUES (:id_livrable, :etudiant_id, :description, :chemin_fichier, NOW())
            ";
            $stmt = self::$bdd->prepare($req);
            return $stmt->execute([
                'id_livrable' => $id_livrable,
                'etudiant_id' => $etudiant_id,
                'description' => $description,
                'chemin_fichier' => $chemin_fichier
            ]);
        } catch (PDOException $e) {
            error_log("Erreur dans ajouter_rendu: " . $e->getMessage());
            return false;
        }
    }

    public function etudiant_a_acces_projet($etudiant_id, $id_projet) {
        try {
            $req = "SELECT 1
                FROM Projet_Promotion pp
                JOIN Promotion_Utilisateur pu ON pp.id_promo = pu.id_promo
                WHERE pp.id_projet = :id_projet AND pu.id_utilisateur = :etudiant_id";
            $stmt = self::$bdd->prepare($req);
            $stmt->execute([
                'id_projet' => $id_projet,
                'etudiant_id' => $etudiant_id
            ]);
            return $stmt->fetchColumn() !== false;
        } catch (PDOException $e) {
            error_log("Erreur dans etudiant_a_acces_projet: " . $e->getMessage());
            return false;
        }
    }

    /////////////////////
    //   GETTERS ICI   //
    /////////////////////

    public function get_projets_pour_etudiant($etudiant_id) {
        try {
            $req = "
                SELECT DISTINCT Projet.*
                FROM Projet
                JOIN Projet_Promotion pp ON Projet.id_projet = pp.id_projet
                JOIN Promotion_Utilisateur pu ON pp.id_promo = pu.id_promo
                WHERE pu.id_utilisateur = :etudiant_id
            ";
            $stmt = self::$bdd->prepare($req);
            $stmt->execute(['etudiant_id' => $etudiant_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur dans get_projets_pour_etudiant: " . $e->getMessage());
            return [];
        }
    }

    public function get_projet($id_projet) {
        try {
            $req = "SELECT * FROM Projet WHERE id_projet = :id_projet";
            $stmt = self::$bdd->prepare($req);
            $stmt->execute(['id_projet' => $id_projet]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur dans get_projet: " . $e->getMessage());
            return null;
        }
    }

    public function get_groupes_par_projet($id_projet) {
        try {
            $req = "
                SELECT g.id_groupe, g.nom_groupe, GROUP_CONCAT(u.nom, ' ', u.prenom SEPARATOR ', ') AS membres
                FROM Groupe g
                LEFT JOIN Groupe_Utilisateur gu ON g.id_groupe = gu.groupe_id
                LEFT JOIN Utilisateur u ON gu.utilisateur_id = u.id_utilisateur
                WHERE g.projet_id = :id_projet
                GROUP BY g.id_groupe
            ";
            $stmt = self::$bdd->prepare($req);
            $stmt->execute(['id_projet' => $id_projet]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur dans get_groupes_par_projet: " . $e->getMessage());
            return [];
        }
    }

    public function get_livrables_par_projet($id_projet) {
        try {
            $req = "SELECT * FROM Livrable WHERE projet_id = :id_projet";
            $stmt = self::$bdd->prepare($req);
            $stmt->execute(['id_projet' => $id_projet]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur dans get_livrables_par_projet: " . $e->getMessage());
            return [];
        }
    }

    public function get_groupe_etudiant($etudiant_id, $id_projet) {
        try {
            $req = "
                SELECT g.id_groupe, g.nom_groupe
                FROM Groupe g
                JOIN Groupe_Utilisateur gu ON g.id_groupe = gu.groupe_id
                WHERE gu.utilisateur_id = :etudiant_id AND g.projet_id = :id_projet
            ";
            $stmt = self::$bdd->prepare($req);
            $stmt->execute([
                'etudiant_id' => $etudiant_id,
                'id_projet' => $id_projet
            ]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur dans get_groupe_etudiant: " . $e->getMessage());
            return null;
        }
    }

    public function get_etudiant($etudiant_id) {
        try {
            $req = "SELECT prenom, nom FROM Utilisateur WHERE id_utilisateur = :etudiant_id";
            $stmt = self::$bdd->prepare($req);
            $stmt->execute(['etudiant_id' => $etudiant_id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur dans get_etudiant: " . $e->getMessage());
            return null;
        }
    }

    public function get_livrable($id_livrable) {
        try {
            $req = "SELECT * FROM Livrable WHERE id_livrable = :id_livrable";
            $stmt = self::$bdd->prepare($req);
            $stmt->execute(['id_livrable' => $id_livrable]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur dans get_livrable: " . $e->getMessage());
            return null;
        }
    }

    public function get_rendu_etudiant($id_livrable, $etudiant_id) {
        try {
            $req = "SELECT * FROM Rendu WHERE id_livrable = :id_livrable AND utilisateur_id = :etudiant_id";
            $stmt = self::$bdd->prepare($req);
            $stmt->execute([
                'id_livrable' => $id_livrable,
                'etudiant_id' => $etudiant_id
            ]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur dans get_rendu_etudiant: " . $e->getMessage());
            return null;
        }
    }
}

?>
