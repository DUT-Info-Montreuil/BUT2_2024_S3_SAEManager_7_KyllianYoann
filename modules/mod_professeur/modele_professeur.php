<?php

class ModeleProfesseur extends Connexion {

    public function creer_livrable($titre, $description, $date_limite, $coefficient) {
        $req = "INSERT INTO Livrable (titre, description, date_limite, coefficient) VALUES (:titre, :description, :date_limite, :coefficient)";
        $stmt = self::$bdd->prepare($req);
        $stmt->execute([
            "titre" => $titre,
            "description" => $description,
            "date_limite" => $date_limite,
            "coefficient" => $coefficient
        ]);
        return $stmt->rowCount() > 0;
    }

    public function get_livrables() {
        $req = "SELECT * FROM Livrable";
        $stmt = self::$bdd->query($req);
        return $stmt->fetchAll();
    }

    public function get_rendus() {
        $req = "SELECT Rendu.*, Utilisateur.nom AS etudiant FROM Rendu JOIN Utilisateur ON Rendu.utilisateur_id = Utilisateur.id_utilisateur";
        $stmt = self::$bdd->query($req);
        return $stmt->fetchAll();
    }

    public function ajouter_feedback($rendu_id, $feedback) {
        $req = "INSERT INTO Feedback (rendu_id, contenu) VALUES (:rendu_id, :contenu)";
        $stmt = self::$bdd->prepare($req);
        $stmt->execute([
            "rendu_id" => $rendu_id,
            "contenu" => $feedback
        ]);
        return $stmt->rowCount() > 0;
    }
}
