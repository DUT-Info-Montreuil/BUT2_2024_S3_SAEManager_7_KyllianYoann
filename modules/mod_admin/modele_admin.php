<?php

class ModeleAdmin extends Connexion {

    public function modifier_utilisateur($id_utilisateur, $nom, $prenom, $login, $mdp, $role) {
        $req = "UPDATE Utilisateur SET nom = :nom, prenom = :prenom, login = :login, mdp = :mdp, role = :role WHERE id_utilisateur = :id";
        $stmt = self::$bdd->prepare($req);
        $stmt->execute([
            "id" => $id_utilisateur,
            "nom" => $nom,
            "prenom" => $prenom,
            "login" => $login,
            "mdp" => password_hash($mdp, PASSWORD_BCRYPT),
            "role" => $role
        ]);
        return $stmt->rowCount() > 0;
    }

    public function modifier_promo_utilisateur($id_utilisateur, $id_promo) {
        $sql_check = "SELECT id FROM Promotion_Utilisateur WHERE id_utilisateur = :id_utilisateur";
        $stmt = $this->executer_requete($sql_check, ['id_utilisateur' => $id_utilisateur]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
        // Met à jour la promotion existante
        $sql_update = "UPDATE Promotion_Utilisateur SET id_promo = :id_promo WHERE id_utilisateur = :id_utilisateur";
        $this->executer_requete($sql_update, ['id_promo' => $id_promo, 'id_utilisateur' => $id_utilisateur]);
        } else {
        // Insère une nouvelle relation utilisateur-promotion
        $sql_insert = "INSERT INTO Promotion_Utilisateur (id_promo, id_utilisateur) VALUES (:id_promo, :id_utilisateur)";
        $this->executer_requete($sql_insert, ['id_promo' => $id_promo, 'id_utilisateur' => $id_utilisateur]);
        }
    }


    public function creer_utilisateur($nom, $prenom, $login, $mdp, $role) {
        $req = "INSERT INTO Utilisateur (nom, prenom, login, mdp, role) VALUES (:nom, :prenom, :login, :mdp, :role)";
        $stmt = self::$bdd->prepare($req);
        $stmt->execute([
            "nom" => $nom,
            "prenom" => $prenom,
            "login" => $login,
            "mdp" => password_hash($mdp, PASSWORD_BCRYPT),
            "role" => $role
        ]);
        return $stmt->rowCount() > 0;
    }

    public function supprimer_utilisateur($id_utilisateur) {
        $req = "DELETE FROM Utilisateur WHERE id_utilisateur = :id";
        $stmt = self::$bdd->prepare($req);
        $stmt->execute(["id" => $id_utilisateur]);
        return $stmt->rowCount() > 0;
    }

    ////////////////////////
    //    GETTERS  ICI    //
    ////////////////////////

    public function get_promos() {
        $sql = "SELECT p.id_promo, p.nom_promo, au.annee
                FROM Promo p
                JOIN Annee_Universitaire au ON p.annee_universitaire_id = au.id_annee";
        return $this->executer_requete($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_utilisateurs() {
        $sql = "SELECT u.*, pu.id_promo, p.nom_promo
            FROM Utilisateur u
            LEFT JOIN Promotion_Utilisateur pu ON u.id_utilisateur = pu.id_utilisateur
            LEFT JOIN Promo p ON pu.id_promo = p.id_promo
            ORDER BY u.id_utilisateur ASC";
        return $this->executer_requete($sql)->fetchAll(PDO::FETCH_ASSOC);
    }


    public function get_statistiques() {
        $stats = [];

        $req = "SELECT role, COUNT(*) as count FROM Utilisateur GROUP BY role";
        $stmt = self::$bdd->query($req);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($result as $row) {
            $stats[$row['role'] . 's'] = $row['count'];
        }

        $stats['admins'] = $stats['admins'] ?? 0;
        $stats['professeurs'] = $stats['professeurs'] ?? 0;
        $stats['etudiants'] = $stats['etudiants'] ?? 0;

        return $stats;
    }

    public function get_utilisateurs_avec_promo() {
        $sql = "SELECT u.id_utilisateur, u.nom, u.prenom, u.login, u.role, p.id_promo, p.nom_promo
                FROM Utilisateur u
                LEFT JOIN Promotion_Utilisateur pu ON u.id_utilisateur = pu.id_utilisateur
                LEFT JOIN Promo p ON pu.id_promo = p.id_promo";
        return $this->executer_requete($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_utilisateur($id_utilisateur) {
        $req = "SELECT * FROM Utilisateur WHERE id_utilisateur = :id";
        $stmt = self::$bdd->prepare($req);
        $stmt->execute(["id" => $id_utilisateur]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    ////////////////////////
    //  Méthode générique //
    ////////////////////////

    private function executer_requete($sql, $params = []) {
        $stmt = self::$bdd->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
}
