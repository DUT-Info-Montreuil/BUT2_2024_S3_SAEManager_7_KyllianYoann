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
        $req = "SELECT role, COUNT(*) as count FROM Utilisateur GROUP BY role";
        $stmt = self::$bdd->query($req);
        $result = $stmt->fetchAll();

        foreach ($result as $row) {
            $stats[$row['role'] . 's'] = $row['count'];
        }
        $stats['admins'] = $stats['admins'] ?? 0;
        $stats['professeurs'] = $stats['professeurs'] ?? 0;
        $stats['etudiants'] = $stats['etudiants'] ?? 0;

        return $stats;
    }

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

    public function get_utilisateur($id_utilisateur) {
    $req = "SELECT * FROM Utilisateur WHERE id_utilisateur = :id";
    $stmt = self::$bdd->prepare($req);
    $stmt->execute(["id" => $id_utilisateur]);
    return $stmt->fetch();
    }
}
