<?php

class ModeleEtudiant extends Connexion {

    public function get_livrables() {
        $req = "SELECT * FROM Livrable";
        $stmt = self::$bdd->query($req);
        return $stmt->fetchAll();
    }

    public function soumettre_rendu($titre, $livrable_id, $chemin) {
        $req = "INSERT INTO Rendu (titre_rendu, fichier, date_soumission, statut, utilisateur_id, livrable_id) 
                VALUES (:titre, :fichier, NOW(), 'soumis', :utilisateur_id, :livrable_id)";
        $stmt = self::$bdd->prepare($req);
        $stmt->execute([
            "titre" => $titre,
            "fichier" => $chemin,
            "utilisateur_id" => $_SESSION["user_id"],
            "livrable_id" => $livrable_id
        ]);
        return $stmt->rowCount() > 0;
    }

    public function get_feedbacks($etudiant_id) {
        $req = "SELECT Feedback.contenu, Livrable.titre AS livrable 
                FROM Feedback 
                JOIN Rendu ON Feedback.rendu_id = Rendu.id_rendu 
                JOIN Livrable ON Rendu.livrable_id = Livrable.id_livrable 
                WHERE Rendu.utilisateur_id = :etudiant_id";
        $stmt = self::$bdd->prepare($req);
        $stmt->execute(["etudiant_id" => $etudiant_id]);
        return $stmt->fetchAll();
    }
}
