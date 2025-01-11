<?php

class ModeleAdmin extends Connexion {
    public function get_utilisateurs() {
        $req = "SELECT * FROM Utilisateur";
        $stmt = self::$bdd->query($req);
        return $stmt->fetchAll();
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

    public function get_statistiques() {
        $stats = [];

        // Comptage des utilisateurs par rôle
        $req = "SELECT role, COUNT(*) as count FROM Utilisateur GROUP BY role";
        $stmt = self::$bdd->query($req);
        $result = $stmt->fetchAll();

        foreach ($result as $row) {
            $stats[$row['role'] . 's'] = $row['count'];
        }

        // Assurez-vous que toutes les clés sont présentes
        $stats['admins'] = $stats['admins'] ?? 0;
        $stats['professeurs'] = $stats['professeurs'] ?? 0;
        $stats['etudiants'] = $stats['etudiants'] ?? 0;

        return $stats;
    }
}
