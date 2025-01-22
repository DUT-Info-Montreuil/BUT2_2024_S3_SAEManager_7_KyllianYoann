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

    // Créer un groupe
    public function creer_groupe($nom_groupe, $id_projet, $membres) {
        try {
            $req = "INSERT INTO Groupe (nom_groupe, projet_id) VALUES (:nom_groupe, :id_projet)";
            $stmt = self::$bdd->prepare($req);
            $stmt->execute([
                'nom_groupe' => $nom_groupe,
                'id_projet' => $id_projet
            ]);

            $id_groupe = self::$bdd->lastInsertId();

            foreach ($membres as $id_etudiant) {
                $this->ajouter_utilisateur_groupe($id_groupe, $id_etudiant);
            }

            return $id_groupe;
        } catch (Exception $e) {
            return false;
        }
    }

    public function supprimer_groupe($id_groupe) {
        try {
            // Supprimer les relations entre les utilisateurs et le groupe
            $req1 = "DELETE FROM Groupe_Utilisateur WHERE groupe_id = :id_groupe";
            $stmt1 = self::$bdd->prepare($req1);
            $stmt1->execute(['id_groupe' => $id_groupe]);

            // Supprimer le groupe lui-même
            $req2 = "DELETE FROM Groupe WHERE id_groupe = :id_groupe";
            $stmt2 = self::$bdd->prepare($req2);
            return $stmt2->execute(['id_groupe' => $id_groupe]);
        } catch (Exception $e) {
            return false;
        }
    }

    public function mettre_a_jour_groupe($id_groupe, $nom_groupe, $membres) {
        try {
            // Mettre à jour le nom du groupe
            $req = "UPDATE Groupe SET nom_groupe = :nom_groupe WHERE id_groupe = :id_groupe";
            $stmt = self::$bdd->prepare($req);
            $stmt->execute([
                'id_groupe' => $id_groupe,
                'nom_groupe' => $nom_groupe
            ]);

            // Supprimer les membres existants du groupe
            $req = "DELETE FROM Groupe_Utilisateur WHERE groupe_id = :id_groupe";
            $stmt = self::$bdd->prepare($req);
            $stmt->execute(['id_groupe' => $id_groupe]);

            // Ajouter les nouveaux membres au groupe
            foreach ($membres as $id_etudiant) {
                $this->ajouter_utilisateur_groupe($id_groupe, $id_etudiant);
            }

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    // Ajouter un utilisateur à un groupe
    public function ajouter_utilisateur_groupe($id_groupe, $id_utilisateur) {
        $req = "INSERT INTO Groupe_Utilisateur (groupe_id, utilisateur_id) VALUES (:groupe_id, :utilisateur_id)";
        $stmt = self::$bdd->prepare($req);
        return $stmt->execute([
            'groupe_id' => $id_groupe,
            'utilisateur_id' => $id_utilisateur
        ]);
    }

    public function est_responsable_projet($id_projet, $id_professeur) {
        $req = "SELECT COUNT(*) as is_responsable
                FROM Responsable_Projet
                WHERE id_projet = :id_projet AND id_professeur = :id_professeur";
        $stmt = self::$bdd->prepare($req);
        $stmt->execute([
            'id_projet' => $id_projet,
            'id_professeur' => $id_professeur
        ]);
        return $stmt->fetch()['is_responsable'] > 0;
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

    // Récupérer les étudiants des promotions associées au projet
    public function get_etudiants_promotions($id_projet) {
        // Récupérer les étudiants déjà assignés
        $etudiants_assignes = $this->get_etudiants_assignes($id_projet);

        // Construire une clause `NOT IN` uniquement si des étudiants sont déjà assignés
        $clause_exclusion = "";
        if (!empty($etudiants_assignes)) {
            $placeholders = implode(',', array_fill(0, count($etudiants_assignes), '?'));
            $clause_exclusion = "AND u.id_utilisateur NOT IN ($placeholders)";
        }

        // Construire la requête SQL
        $req = "SELECT u.id_utilisateur, u.nom, u.prenom 
                FROM Utilisateur u
                JOIN Promotion_Utilisateur pu ON u.id_utilisateur = pu.id_utilisateur
                JOIN Projet_Promotion pp ON pu.id_promo = pp.id_promo
                WHERE pp.id_projet = ? 
                AND u.role = 'etudiant' $clause_exclusion";

        // Préparer la requête
        $stmt = self::$bdd->prepare($req);

        // Préparer les paramètres pour `execute`
        $params = [$id_projet];
        if (!empty($etudiants_assignes)) {
            $params = array_merge($params, $etudiants_assignes);
        }

        // Exécuter la requête
        $stmt->execute($params);

        // Retourner les résultats
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_membres_groupe($id_groupe) {
        $req = "SELECT u.id_utilisateur, u.nom, u.prenom 
                FROM Utilisateur u
                JOIN Groupe_Utilisateur gu ON u.id_utilisateur = gu.utilisateur_id
                WHERE gu.groupe_id = :id_groupe";
        $stmt = self::$bdd->prepare($req);
        $stmt->execute(['id_groupe' => $id_groupe]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }   

    public function get_etudiants_assignes($id_projet) {
        $req = "SELECT gu.utilisateur_id 
                FROM Groupe_Utilisateur gu
                JOIN Groupe g ON gu.groupe_id = g.id_groupe
                WHERE g.projet_id = :id_projet";
        $stmt = self::$bdd->prepare($req);
        $stmt->execute(['id_projet' => $id_projet]);
        return array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'utilisateur_id');
    }

    // Récupérer les groupes associés à un projet
    public function get_groupes_par_projet($id_projet) {
        $req = "SELECT g.id_groupe, g.nom_groupe, GROUP_CONCAT(u.nom, ' ', u.prenom SEPARATOR ', ') AS membres
                FROM Groupe g
                LEFT JOIN Groupe_Utilisateur gu ON g.id_groupe = gu.groupe_id
                LEFT JOIN Utilisateur u ON gu.utilisateur_id = u.id_utilisateur
                WHERE g.projet_id = :id_projet
                GROUP BY g.id_groupe";
        $stmt = self::$bdd->prepare($req);
        $stmt->execute(['id_projet' => $id_projet]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_groupe($id_groupe) {
        $req = "SELECT g.id_groupe, g.nom_groupe, g.projet_id, 
                    GROUP_CONCAT(u.id_utilisateur) AS membres_ids,
                    GROUP_CONCAT(CONCAT(u.prenom, ' ', u.nom) SEPARATOR ', ') AS membres_noms
                FROM Groupe g
                LEFT JOIN Groupe_Utilisateur gu ON g.id_groupe = gu.groupe_id
                LEFT JOIN Utilisateur u ON gu.utilisateur_id = u.id_utilisateur
                WHERE g.id_groupe = :id_groupe
                GROUP BY g.id_groupe";
        $stmt = self::$bdd->prepare($req);
        $stmt->execute(['id_groupe' => $id_groupe]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
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
