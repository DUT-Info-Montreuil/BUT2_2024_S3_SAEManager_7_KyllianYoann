<?php

class ModeleEtudiant extends Connexion {

    public function soumettre_rendu($titre, $livrable_id, $fichier) {
    // Vérifie si le fichier est bien téléchargé
    if (isset($fichier['tmp_name']) && is_uploaded_file($fichier['tmp_name'])) {
        // Chemin où le fichier sera enregistré
        $chemin = 'uploads/' . basename($fichier['name']);

        // Déplace le fichier dans le répertoire des uploads
        if (move_uploaded_file($fichier['tmp_name'], $chemin)) {
            // Préparation de la requête SQL
            $req = "INSERT INTO Rendu (titre_rendu, fichier, date_soumission, statut, utilisateur_id, livrable_id) 
                    VALUES (:titre, :fichier, NOW(), 'soumis', :utilisateur_id, :livrable_id)";
            $stmt = self::$bdd->prepare($req);

            // Exécution de la requête avec les paramètres
            $stmt->execute([
                "titre" => $titre,
                "fichier" => $chemin, // Le chemin du fichier
                "utilisateur_id" => $_SESSION["utilisateur_id"],
                "livrable_id" => $livrable_id
            ]);

            return $stmt->rowCount() > 0;
        } else {
            throw new Exception("Échec du déplacement du fichier.");
        }
    } else {
        throw new Exception("Fichier non valide ou non téléchargé.");
        }
    }

    public function ajouter_commentaire($contenu, $evaluation_id) {
    $req = "INSERT INTO Commentaire (contenu, evaluation_id) VALUES (:contenu, :evaluation_id)";
    $stmt = self::$bdd->prepare($req);
    $stmt->execute([
        "contenu" => $contenu,
        "evaluation_id" => $evaluation_id
    ]);
    return $stmt->rowCount() > 0;
    }

    public function get_livrables() {
        $req = "SELECT id_livrable, titre_livrable, description, date_limite, coefficient 
            FROM Livrable";
        $stmt = self::$bdd->query($req);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_livrable($id_livrable) {
        $req = "SELECT * FROM Livrable WHERE id_livrable = :id";
        $stmt = self::$bdd->prepare($req);
        $stmt->execute(['id' => $id_livrable]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
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

    public function get_evaluations($id_livrable) {
    $req = "SELECT * FROM Evaluation WHERE rendu_id IN (
                SELECT id_rendu FROM Rendu WHERE livrable_id = :id_livrable
            )";
    $stmt = self::$bdd->prepare($req);
    $stmt->execute(['id_livrable' => $id_livrable]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    public function get_commentaires($id_evaluation) {
    $req = "SELECT * FROM Commentaire WHERE evaluation_id = :id_evaluation";
    $stmt = self::$bdd->prepare($req);
    $stmt->execute(['id_evaluation' => $id_evaluation]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


  
    public function get_feedbacks_by_livrable($id_livrable) {
    $req = self::$bdd->prepare("
        SELECT Feedback.contenu, Feedback.date_feedback, 
               Rendu.titre_rendu AS rendu_titre, 
               Livrable.titre_livrable AS livrable_titre 
        FROM Feedback
        JOIN Rendu ON Feedback.rendu_id = Rendu.id_rendu
        JOIN Livrable ON Rendu.livrable_id = Livrable.id_livrable
        WHERE Livrable.id_livrable = :id_livrable
    ");
    $req->bindParam(':id_livrable', $id_livrable, PDO::PARAM_INT);
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

