<?php

class ModeleAdmin extends Connexion {

    public function creer_utilisateur($nom, $prenom, $email, $mot_de_passe, $role) {
        $req = "INSERT INTO Utilisateur (nom, prenom, email, mot_de_passe, role) VALUES (:nom, :prenom, :email, :mot_de_passe, :role)";
        $stmt = self::$bdd->prepare($req);
        $stmt->execute([
            "nom" => $nom,
            "prenom" => $prenom,
            "email" => $email,
            "mot_de_passe" => password_hash($mot_de_passe, PASSWORD_BCRYPT),
            "role" => $role
        ]);
        return $stmt->rowCount() > 0;
    }

    public function supprimer_utilisateur($id_utilisateur) {
        $req = "DELETE FROM Utilisateur WHERE id_utilisateur = :id_utilisateur";
        $stmt = self::$bdd->prepare($req);
        $stmt->execute(["id_utilisateur" => $id_utilisateur]);
        return $stmt->rowCount() > 0;
    }

    public function get_utilisateurs() {
        $req = "SELECT * FROM Utilisateur";
        $stmt = self::$bdd->query($req);
        return $stmt->fetchAll();
    }
}
