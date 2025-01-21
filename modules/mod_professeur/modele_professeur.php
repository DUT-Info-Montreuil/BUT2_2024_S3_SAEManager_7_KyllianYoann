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
    
    public function creer_projet($titre, $description, $id_promo, $responsables) {
    // Étape 1 : Insérer le projet dans la table Projet
    $req = "INSERT INTO Projet (titre, description, id_promo) VALUES (:titre, :description, :id_promo)";
    $stmt = self::$bdd->prepare($req);
    $stmt->execute([
        'titre' => $titre,
        'description' => $description,
        'id_promo' => $id_promo
    ]);

    // Récupérer l'ID du projet créé
    $id_projet = self::$bdd->lastInsertId();

    // Étape 2 : Associer les professeurs responsables au projet
    $req_assoc = "INSERT INTO Responsable_Projet (id_projet, id_professeur) VALUES (:id_projet, :id_professeur)";
    $stmt_assoc = self::$bdd->prepare($req_assoc);
    foreach ($responsables as $id_professeur) {
        $stmt_assoc->execute([
            'id_projet' => $id_projet,
            'id_professeur' => $id_professeur
        ]);
    }

    // Retourner vrai si le projet a été créé et des responsables ont été associés
    return $stmt->rowCount() > 0 && count($responsables) > 0;
    }


    public function get_projets_responsable($id_prof) {
    $req = "SELECT p.id_projet, p.titre, p.description 
            FROM Projet p
            JOIN Responsable_Projet rp ON p.id_projet = rp.id_projet
            WHERE rp.id_professeur = :id_prof";
    $stmt = self::$bdd->prepare($req);
    $stmt->execute(['id_prof' => $id_prof]);
    return $stmt->fetchAll();
    }


    public function get_promotions() {
        $req = "SELECT * FROM Promo";
        $stmt = self::$bdd->query($req);
        return $stmt->fetchAll();
    }

    public function get_professeurs() {
        $req = "SELECT id_utilisateur, nom, prenom FROM Utilisateur WHERE role = 'professeur'";
        $stmt = self::$bdd->query($req);
        return $stmt->fetchAll();
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

    public function get_details_projet($id_projet) {
        $req = "SELECT * FROM Projet WHERE id_projet = :id_projet";
        $stmt = self::$bdd->prepare($req);
        $stmt->execute(['id_projet' => $id_projet]);
        return $stmt->fetch();
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
