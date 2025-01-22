<?php

class ModeleProfesseur extends Connexion {

    public function creer_livrable($titre, $description, $date_limite, $coefficient, $isIndividuel) {
        try {
            $req = "INSERT INTO Livrable (titre_livrable, description, date_limite, coefficient, isIndividuel) 
                    VALUES (:titre, :description, :date_limite, :coefficient, :isIndividuel)";
            $stmt = self::$bdd->prepare($req);
            $stmt->execute([
                "titre" => $titre,
                "description" => $description,
                "date_limite" => $date_limite,
                "coefficient" => $coefficient,
                "isIndividuel" => $isIndividuel
            ]);
            return $stmt->rowCount() > 0;
        } catch (Exception $e) {
            // Log or handle the error
            return false;
        }
    }

    public function creer_projet($titre, $description, $responsables, $semestre, $coefficient) {
        try {
            $req = "INSERT INTO Projet (titre, description, semestre, coefficient) 
                    VALUES (:titre, :description, :semestre, :coefficient)";
            $stmt = self::$bdd->prepare($req);
            $stmt->execute([
                'titre' => $titre,
                'description' => $description,
                'semestre' => $semestre,
                'coefficient' => $coefficient
            ]);

            $id_projet = self::$bdd->lastInsertId();

            foreach ($responsables as $id_professeur) {
                $this->associer_projet_responsable($id_projet, $id_professeur);
            }

            return $id_projet;
        } catch (Exception $e) {
            return false;
        }
    }


    public function supprimer_projet($id_projet) {
        try {
            $this->supprimer_promotions_projet($id_projet);
            $this->supprimer_responsables_projet($id_projet);

            $req = "DELETE FROM Projet WHERE id_projet = :id_projet";
            $stmt = self::$bdd->prepare($req);
            return $stmt->execute(['id_projet' => $id_projet]);
        } catch (Exception $e) {
            return false;
        }
    }

    public function mettre_a_jour_projet($id_projet, $titre, $description, $semestre, $coefficient) {
        $req = "UPDATE Projet 
            SET titre = :titre, 
                description = :description, 
                semestre = :semestre, 
                coefficient = :coefficient 
            WHERE id_projet = :id_projet";
        $stmt = self::$bdd->prepare($req);
        return $stmt->execute([
            'id_projet' => $id_projet,
            'titre' => $titre,
            'description' => $description,
            'semestre' => $semestre,
            'coefficient' => $coefficient
        ]);
    }



    public function associer_projet_promotion($id_projet, $id_promo) {
        try {
            $req = "INSERT INTO Projet_Promotion (id_projet, id_promo) VALUES (:id_projet, :id_promo)";
            $stmt = self::$bdd->prepare($req);
            $stmt->execute([
                'id_projet' => $id_projet,
                'id_promo' => $id_promo
            ]);
            return $stmt->rowCount() > 0;
        } catch (Exception $e) {
            return false;
        }
    }


    public function associer_projet_responsable($id_projet, $id_professeur) {
        try {
            $req = "INSERT INTO Responsable_Projet (id_projet, id_professeur) VALUES (:id_projet, :id_professeur)";
            $stmt = self::$bdd->prepare($req);
            $stmt->execute([
                'id_projet' => $id_projet,
                'id_professeur' => $id_professeur
            ]);
            return $stmt->rowCount() > 0;
        } catch (Exception $e) {
            return false;
        }
    }

    public function supprimer_promotions_projet($id_projet) {
        try {
            $req = "DELETE FROM Projet_Promotion WHERE id_projet = :id_projet";
            $stmt = self::$bdd->prepare($req);
            $stmt->execute(['id_projet' => $id_projet]);
            return $stmt->rowCount() > 0;
        } catch (Exception $e) {
            return false;
        }
    }

    public function supprimer_responsables_projet($id_projet) {
        try {
            $req = "DELETE FROM Responsable_Projet WHERE id_projet = :id_projet";
            $stmt = self::$bdd->prepare($req);
            $stmt->execute(['id_projet' => $id_projet]);
            return $stmt->rowCount() > 0;
        } catch (Exception $e) {
            return false;
        }
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

    /////////////////////
    //      GETTERS    //
    /////////////////////

    public function get_projet($id_projet) {
        try {
            $req = "SELECT * FROM Projet WHERE id_projet = :id_projet";
            $stmt = self::$bdd->prepare($req);
            $stmt->execute(['id_projet' => $id_projet]);
            $projet = $stmt->fetch();

            if ($projet) {
                // Ajouter les promotions avec leurs noms
                $projet['promotions'] = $this->get_promotions_projet_avec_noms($id_projet);

                // Ajouter les responsables avec leurs noms et prénoms
                $projet['responsables'] = $this->get_responsables_projet_avec_noms($id_projet);
            }

            return $projet;
        } catch (Exception $e) {
            return false;
        }
    }

    public function get_promotions_projet($id_projet) {
        $req = "SELECT id_promo FROM Projet_Promotion WHERE id_projet = :id_projet";
        $stmt = self::$bdd->prepare($req);
        $stmt->execute(['id_projet' => $id_projet]);
        return array_column($stmt->fetchAll(), 'id_promo');
    }

    public function get_promotions_projet_avec_noms($id_projet) {
        $req = "SELECT p.nom_promo 
                FROM Promo p
                JOIN Projet_Promotion pp ON p.id_promo = pp.id_promo
                WHERE pp.id_projet = :id_projet";
        $stmt = self::$bdd->prepare($req);
        $stmt->execute(['id_projet' => $id_projet]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_responsables_projet_avec_noms($id_projet) {
        $req = "SELECT u.nom, u.prenom 
                FROM Utilisateur u
                JOIN Responsable_Projet rp ON u.id_utilisateur = rp.id_professeur
                WHERE rp.id_projet = :id_projet";
        $stmt = self::$bdd->prepare($req);
        $stmt->execute(['id_projet' => $id_projet]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_responsables_projet($id_projet) {
        $req = "SELECT id_professeur FROM Responsable_Projet WHERE id_projet = :id_projet";
        $stmt = self::$bdd->prepare($req);
        $stmt->execute(['id_projet' => $id_projet]);
        return array_column($stmt->fetchAll(), 'id_professeur');
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
        $req = "SELECT id_utilisateur, nom, prenom FROM Utilisateur WHERE id_utilisateur = :id AND role = 'professeur'";
        $stmt = self::$bdd->prepare($req);
        $stmt->execute(["id" => $id_professeur]);
        return $stmt->fetch();
    }


    public function get_livrables_par_projet($id_projet) {
        $req = "SELECT * FROM Livrable WHERE projet_id = :id_projet";
        $stmt = self::$bdd->prepare($req);
        $stmt->execute(['id_projet' => $id_projet]);
        return $stmt->fetchAll();
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

}
