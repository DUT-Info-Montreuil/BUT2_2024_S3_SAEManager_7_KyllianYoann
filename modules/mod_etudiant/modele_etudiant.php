<?php

class ModeleEtudiant extends Connexion {

    // Récupère tous les livrables disponibles
    public function get_livrables() {
        $req = "SELECT id_livrable, titre_livrable, description, date_limite, coefficient 
                FROM Livrable";
        $stmt = self::$bdd->query($req);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupère les informations du groupe de l'utilisateur
    public function get_groupe($utilisateur_id) {
        $req = self::$bdd->prepare("
            SELECT g.nom_groupe 
            FROM Groupe g
            JOIN Groupe_Utilisateur gu ON g.id_groupe = gu.groupe_id
            WHERE gu.utilisateur_id = :utilisateur_id
        ");
        $req->execute(['utilisateur_id' => $utilisateur_id]);
        return $req->fetch(PDO::FETCH_ASSOC);
    }

    // Soumet un rendu pour un livrable donné
    public function soumettre_rendu($titre, $livrable_id, $chemin) {
        $req = "INSERT INTO Rendu (titre_rendu, fichier, date_soumission, statut, utilisateur_id, livrable_id) 
                VALUES (:titre, :fichier, NOW(), 'soumis', :utilisateur_id, :livrable_id)";
        $stmt = self::$bdd->prepare($req);
        $stmt->execute([
            "titre" => $titre,
            "fichier" => $chemin,
            "utilisateur_id" => $_SESSION["utilisateur_id"],
            "livrable_id" => $livrable_id
        ]);
        return $stmt->rowCount() > 0;
    }

    // Récupère les feedbacks d'un utilisateur donné
    public function get_feedbacks($utilisateur_id) {
        $req = self::$bdd->prepare("
            SELECT Feedback.contenu, Feedback.date_feedback, 
                   Rendu.titre_rendu AS rendu_titre, 
                   Livrable.titre_livrable AS livrable_titre 
            FROM Feedback
            JOIN Rendu ON Feedback.rendu_id = Rendu.id_rendu
            JOIN Livrable ON Rendu.livrable_id = Livrable.id_livrable
            WHERE Rendu.utilisateur_id = :utilisateur_id
        ");
        $req->bindParam(':utilisateur_id', $utilisateur_id, PDO::PARAM_INT);
        $req->execute();
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupère les coefficients des livrables
    public function get_coefficients() {
        $req = "SELECT titre_livrable, coefficient 
                FROM Livrable";
        $stmt = self::$bdd->query($req);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

