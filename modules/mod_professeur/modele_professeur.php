<?php

class ModeleProfesseur extends Connexion {

    public function creer_livrable($titre, $description, $date_limite, $coefficient, $isIndividuel, $projet_id) {
        try {
            $bdd = Connexion::get_connexion();
            $req = "INSERT INTO Livrable (titre_livrable, description, date_limite, coefficient, isIndividuel, projet_id) 
                    VALUES (:titre, :description, :date_limite, :coefficient, :isIndividuel, :projet_id)";
            $stmt = $bdd->prepare($req);
            $stmt->execute([
                "titre" => $titre,
                "description" => $description,
                "date_limite" => $date_limite,
                "coefficient" => $coefficient,
                "isIndividuel" => $isIndividuel,
                "projet_id" => $projet_id
            ]);

            // Récupérer l'ID du livrable inséré
            return $bdd->lastInsertId();
        } catch (Exception $e) {
            error_log("Erreur dans creer_livrable: " . $e->getMessage());
            return false;
        }
    }

    public function modifier_livrable($id_livrable, $titre, $description, $date_limite, $coefficient, $isIndividuel, $projet_id) {
        try {
            $bdd = Connexion::get_connexion();
            $req = "UPDATE Livrable 
                    SET titre_livrable = :titre, 
                        description = :description, 
                        date_limite = :date_limite, 
                        coefficient = :coefficient, 
                        isIndividuel = :isIndividuel, 
                        projet_id = :projet_id
                    WHERE id_livrable = :id_livrable";
            $stmt = $bdd->prepare($req);
            return $stmt->execute([
                'id_livrable' => $id_livrable,
                'titre' => $titre,
                'description' => $description,
                'date_limite' => $date_limite,
                'coefficient' => $coefficient,
                'isIndividuel' => $isIndividuel,
                'projet_id' => $projet_id
            ]);
        } catch (PDOException $e) {
            error_log("Erreur dans modifier_livrable : " . $e->getMessage());
            return false;
        }
    }

    public function supprimer_livrable($id_livrable) {
        try {
            $req = "DELETE FROM Livrable WHERE id_livrable = :id_livrable";
            $stmt = self::$bdd->prepare($req);
            $stmt->bindParam(':id_livrable', $id_livrable, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->rowCount() > 0; // Retourne true si au moins une ligne est supprimée
        } catch (Exception $e) {
            return false;
        }
    }

    // Suppression des relations projet
    public function supprimer_projet($id_projet) {
        try {
            $this->supprimer_promotions_projet($id_projet);
            $this->supprimer_responsables_projet($id_projet);
            $bdd = Connexion::get_connexion();
            $req = "DELETE FROM Projet WHERE id_projet = :id_projet";
            $stmt = $bdd->prepare($req);
            $stmt->bindParam(':id_projet', $id_projet, PDO::PARAM_INT);
            return $stmt->execute();
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

    public function creer_groupe($nom_groupe, $id_projet, $membres) {
        if (empty($nom_groupe) || !is_numeric($id_projet) || empty($membres)) {
            throw new InvalidArgumentException("Données invalides pour créer un groupe.");
        }
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
            error_log("Erreur dans creer_groupe : " . $e->getMessage());
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

    public function ajouter_fichier_livrable($id_livrable, $nom_fichier, $chemin_fichier) {
        try {
            $req = "INSERT INTO Fichier (id_livrable, nom_fichier, chemin_fichier) 
                    VALUES (:id_livrable, :nom_fichier, :chemin_fichier)";
            $stmt = self::$bdd->prepare($req);
            $stmt->execute([
                'id_livrable' => $id_livrable,
                'nom_fichier' => $nom_fichier,
                'chemin_fichier' => $chemin_fichier
            ]);
            return true;
        } catch (PDOException $e) {
            error_log("Erreur dans ajouter_fichier_livrable: " . $e->getMessage());
            return false;
        }
    } 

    public function supprimer_fichier($id_fichier) {
        try {
        $req = "DELETE FROM Fichier WHERE id_fichier = :id_fichier";
        $stmt = self::$bdd->prepare($req);
        return $stmt->execute(['id_fichier' => $id_fichier]);
        } catch (PDOException $e) {
        error_log("Erreur dans supprimer_fichier : " . $e->getMessage());
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

    public function creer_evaluation($data) {
        $sql = "INSERT INTO Evaluation (titre, note, coefficient, description, type, id_projet, id_groupe, rendu_id, evaluateur_id)
            VALUES (:titre, :note, :coefficient, :description, :type, :id_projet, :id_groupe, :rendu_id, :evaluateur_id)";
        try {
        $stmt = self::$bdd->prepare($sql);
        return $stmt->execute($data);
        } catch (PDOException $e) {
        error_log("Erreur dans creer_evaluation : " . $e->getMessage());
        return false;
        }
    }

    public function modifier_evaluation($data) {
        $sql = "UPDATE Evaluation
                SET titre = :titre, description = :description, note = :note, coefficient = :coefficient 
                WHERE id_evaluation = :id_evaluation";
        $stmt = $this->connexion->prepare($sql);
        $stmt->bindParam(':titre', $data['titre']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':note', $data['note']);
        $stmt->bindParam(':coefficient', $data['coefficient']);
        $stmt->bindParam(':id_evaluation', $data['id_evaluation'], PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function supprimer_evaluation($id_evaluation) {
        $sql = "DELETE FROM Evaluation WHERE id_evaluation = :id_evaluation";
        $stmt = $this->connexion->prepare($sql);
        $stmt->bindParam(':id_evaluation', $id_evaluation, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /////////////////////
    //      GETTERS    //
    /////////////////////

    public function getRendusByLivrable($id_livrable) {
        $sql = "SELECT r.id_rendu, r.date_soumission, r.nom_fichier, r.details, e.nom AS etudiant_nom, g.nom AS groupe_nom
            FROM Rendu r
            LEFT JOIN etudiants e ON r.etudiant_id = e.id_utilisateur
            LEFT JOIN groupes g ON r.groupe_id = g.id_groupe
            WHERE r.livrable_id = :id_livrable";
        $query = $this->connexion->prepare($sql);
        $query->execute(['id_livrable' => $id_livrable]);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }


    public function get_livrable($id_livrable) {
    try {
            // Validation de l'entrée pour s'assurer que l'identifiant est valide
            if (!is_numeric($id_livrable) || $id_livrable <= 0) {
                throw new Exception("Identifiant de livrable invalide.");
            }
            // Requête SQL pour récupérer les informations du livrable
            $req = "SELECT 
                    id_livrable, 
                    titre_livrable, 
                    description, 
                    date_limite, 
                    coefficient, 
                    isIndividuel, 
                    projet_id 
                FROM Livrable 
                WHERE id_livrable = :id_livrable";
            // Préparation et exécution de la requête
            $stmt = self::$bdd->prepare($req);
            $stmt->execute(['id_livrable' => $id_livrable]);
            // Récupération des données sous forme de tableau associatif
            $livrable = $stmt->fetch(PDO::FETCH_ASSOC);
            // Vérification si le livrable existe
            if (!$livrable) {
                throw new Exception("Livrable introuvable avec l'ID : $id_livrable.");
            }
            return $livrable;
        } catch (Exception $e) {
            // Log de l'erreur pour faciliter le débogage
            error_log("Erreur dans get_livrable: " . $e->getMessage());
            // Retourne `null` en cas d'erreur
            return null;
        }
    }

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

    public function get_professeur_id_by_projet($id_projet) {
        try {
            // Requête SQL pour obtenir l'ID du professeur associé au projet
            $req = "SELECT responsable_id FROM Projet WHERE id_projet = :id_projet";
            // Préparer la requête SQL
            $stmt = self::$bdd->prepare($req);
            // Exécuter la requête avec l'ID du projet comme paramètre
            $stmt->execute(['id_projet' => $id_projet]);
            // Récupérer le résultat de la requête
            $projet = $stmt->fetch();
            // Si un projet est trouvé, renvoyer l'ID du professeur
            if ($projet) {
                return $projet['professeur_id'];  // Retourne l'ID du professeur associé au projet
            }
            // Si aucun projet n'est trouvé, retourner false
            return false;
        } catch (Exception $e) {
            // Gérer les erreurs, ici on peut simplement retourner false
            return false;
        }
    }

    public function get_projet_par_titre($titre) {
        try {
            $req = "SELECT id_projet FROM Projet WHERE titre = :titre";
            $stmt = self::$bdd->prepare($req);
            $stmt->execute(['titre' => $titre]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur dans get_projet_par_titre: " . $e->getMessage());
            return null;
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
        try {
            // Définir la requête SQL
            $sql = "SELECT * FROM Livrable WHERE projet_id = :projet_id";
            // Préparer la requête avec l'instance PDO
            $stmt = Connexion::get_connexion()->prepare($sql);
            // Lier le paramètre à la valeur
            $stmt->bindParam(':projet_id', $id_projet, PDO::PARAM_INT);
            // Exécuter la requête
            $stmt->execute();
            // Retourner tous les résultats sous forme de tableau associatif
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Gérer les erreurs éventuelles
            error_log("Erreur dans get_livrables_par_projet : " . $e->getMessage());
            return [];
        }
    }

    public function get_fichiers_livrable($id_livrable) {
        try {
        $req = "SELECT * FROM Fichier WHERE id_livrable = :id_livrable";
        $stmt = self::$bdd->prepare($req);
        $stmt->execute(['id_livrable' => $id_livrable]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
        error_log("Erreur dans get_fichiers_livrable : " . $e->getMessage());
        return [];
        }
    }


    public function get_projets_responsable($id_professeur) {
        $req = "SELECT Projet.id_projet, Projet.titre 
                FROM Projet
                JOIN Responsable_Projet ON Projet.id_projet = Responsable_Projet.id_projet
                WHERE Responsable_Projet.id_professeur = :id_professeur";
        $stmt = self::$bdd->prepare($req);
        $stmt->execute(['id_professeur' => $id_professeur]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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

    // Récupération des données pour d'autres fonctionnalités
    public function get_evaluations_par_projet($id_projet) {
        try {
            $bdd = Connexion::get_connexion();
            $sql = "SELECT e.*, g.nom_groupe, p.titre AS projet_titre
                    FROM Evaluation e
                    LEFT JOIN Groupe g ON e.id_groupe = g.id_groupe
                    JOIN Projet p ON e.id_projet = p.id_projet
                    WHERE e.id_projet = :id_projet";
            $stmt = $bdd->prepare($sql);
            $stmt->execute(['id_projet' => $id_projet]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur dans get_evaluations_par_projet : " . $e->getMessage());
            return [];
        }
    }

    public function get_rendus_par_projet($id_projet) {
        try {
            $req = "SELECT r.*, u.nom AS etudiant_nom, u.prenom AS etudiant_prenom
                FROM Rendu r
                JOIN Utilisateur u ON r.utilisateur_id = u.id_utilisateur
                WHERE r.projet_id = :id_projet";
            $stmt = Connexion::get_connexion()->prepare($req);
            $stmt->execute(['id_projet' => $id_projet]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur dans get_rendus_par_projet : " . $e->getMessage());
            return [];
        }
    }

    public function get_evaluation($id_evaluation) {
        $sql = "SELECT * FROM evaluations WHERE id_evaluation = :id_evaluation";
        $stmt = $this->connexion->prepare($sql);
        $stmt->bindParam(':id_evaluation', $id_evaluation, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    ////////////////////////////
    //    METHODE GENERIQUE   //
    ////////////////////////////
 
    protected function executer_requete($sql, $params = []) {
        try {
            $bdd = Connexion::get_connexion(); 
            $requete = $bdd->prepare($sql);
            $requete->execute($params);
            return $requete;
        } catch (Exception $e) {
            die("Erreur lors de l'exécution de la requête : " . $e->getMessage());
        }
    }
}
