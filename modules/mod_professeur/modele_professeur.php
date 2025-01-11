<?php

class ModeleProfesseur extends Connexion {

    public function creer_livrable($titre, $description, $date_limite, $coefficient) {
        $req = "INSERT INTO Livrable (titre_livrable, description, date_limite, coefficient) VALUES (:titre, :description, :date_limite, :coefficient)";
        $stmt = self::$bdd->prepare($req);
        $stmt->execute([
            "titre" => $titre,
            "description" => $description,
            "date_limite" => $date_limite,
            "coefficient" => $coefficient
        ]);
        return $stmt->rowCount() > 0;
    }

    public function get_professeur($id_professeur) {
        $req = "SELECT nom, prenom FROM Utilisateur WHERE id_utilisateur = :id AND role = 'professeur'";
        $stmt = self::$bdd->prepare($req);
        $stmt->execute(["id" => $id_professeur]);
        return $stmt->fetch();
    }

    public function get_statistiques() {
        $statistiques = [];

        // Total des livrables
        $req = "SELECT COUNT(*) as total FROM Livrable";
        $stmt = self::$bdd->query($req);
        $statistiques['livrables_total'] = $stmt->fetch()['total'] ?? 0;

        // Total des rendus
        $req = "SELECT COUNT(*) as total FROM Rendu";
        $stmt = self::$bdd->query($req);
        $statistiques['rendus_total'] = $stmt->fetch()['total'] ?? 0;

        // Total des feedbacks
        $req = "SELECT COUNT(*) as total FROM Feedback";
        $stmt = self::$bdd->query($req);
        $statistiques['feedbacks_total'] = $stmt->fetch()['total'] ?? 0;

        return $statistiques;
    }

    public function get_livrables() {
        $req = "SELECT titre_livrable AS titre, date_limite FROM Livrable";
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
