<?php
class VueProfesseur extends VueGenerique {
    public function __construct() {
        parent::__construct();
    }

    public function menu() {
    ?>
        <style>
        .menu {
            display: flex;
            justify-content: space-around;
            align-items: center;
            background-color: #2c3e50;
            padding: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .menu a {
            color: white;
            text-decoration: none;
            font-weight: bold;
            font-size: 16px;
            padding: 10px 15px;
            border-radius: 5px;
            transition: background 0.3s ease;
        }

        .menu a:hover {
            background-color: #4cd137;
        }

        .menu a.active {
            background-color: #4cd137;
        }
        </style>
        <nav class="menu">
            <a href="index.php?module=professeur&action=dashboard" class="active">Dashboard</a>
            <a href="index.php?module=professeur&action=form_creer_projet">Créer un Projet</a>
            <a href="index.php?module=professeur&action=form_creer_livrable">Créer un Livrable</a>
            <a href="index.php?module=professeur&action=consulter_rendus">Consulter les Rendus</a>
            <a href="index.php?module=connexion&action=deconnexion">Déconnexion</a>
        </nav>
        <?php
    }

    public function dashboard($professeur_info, $statistiques, $projets) { 
        $nom = htmlspecialchars($professeur_info['nom'] ?? 'Inconnu');
        $prenom = htmlspecialchars($professeur_info['prenom'] ?? 'Inconnu');
        ?>
        <style>
        /* Styles CSS */
        .dashboard-container {
            margin: 20px auto;
            max-width: 1200px;
            padding: 30px;
            background-color: #f9f9f9;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        .dashboard-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .dashboard-header h1 {
            font-size: 32px;
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .dashboard-header p {
            font-size: 18px;
            color: #555;
        }

        .projet-card-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            margin-top: 30px;
        }

        .projet-card {
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 300px;
            margin: 15px;
            padding: 20px;
            text-align: center;
            transition: transform 0.3s ease;
        }

        .projet-card:hover {
            transform: scale(1.05);
        }

        .projet-card h3 {
            font-size: 20px;
            color: #2c3e50;
            margin-bottom: 15px;
        }

        .projet-card p {
            font-size: 14px;
            color: #555;
            margin-bottom: 15px;
        }

        .projet-card a {
            display: inline-block;
            margin-top: 10px;
            padding: 10px 20px;
            font-size: 14px;
            color: white;
            background-color: #4cd137;
            text-decoration: none;
            border-radius: 5px;
        }

        .projet-card a:hover {
            background-color: #44bd32;
        }

        .stats-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .stat-box {
            flex: 1;
            background-color: #ffffff;
            padding: 20px;
            margin: 0 10px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .stat-box h3 {
            margin: 10px 0;
            font-size: 24px;
            color: #2c3e50;
        }

        .stat-box p {
            margin: 0;
            font-size: 16px;
            color: #777;
        }

        .stat-icon {
            font-size: 40px;
            color: #4cd137;
            margin-bottom: 10px;
        }

        .message {
            text-align: center;
            font-size: 16px;
            color: green;
            margin-bottom: 20px;
        }

        .error-message {
            text-align: center;
            font-size: 16px;
            color: red;
            margin-bottom: 20px;
        }
        </style>

        <div class="dashboard-container">
            <div class="dashboard-header">
            <h1>Dashboard (<?= $nom; ?> <?= $prenom; ?>)</h1>
            <p>Bienvenue dans votre espace de gestion des projets et rendus.</p>
        </div>

            <?php if (isset($_SESSION['success'])): ?>
            <div class="message"><?= htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?></div>
            <?php elseif (isset($_SESSION['error'])): ?>
            <div class="error-message"><?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
            <?php endif; ?>

        <div class="stats-container">
            <div class="stat-box">
                <div class="stat-icon">🗂️</div>
                <h3><?= htmlspecialchars($statistiques['livrables_total'] ?? 0); ?></h3>
                <p>Livrables créés</p>
            </div>
            <div class="stat-box">
                <div class="stat-icon">📋</div>
                <h3><?= htmlspecialchars($statistiques['rendus_total'] ?? 0); ?></h3>
                <p>Rendus soumis</p>
            </div>
            <div class="stat-box">
                <div class="stat-icon">✉️</div>
                <h3><?= htmlspecialchars($statistiques['feedbacks_total'] ?? 0); ?></h3>
                <p>Feedbacks donnés</p>
            </div>
        </div>

        <div class="projet-card-container">
            <?php if (!empty($projets)): ?>
                <?php foreach ($projets as $projet): ?>
                    <div class="projet-card">
                        <h3><?= htmlspecialchars($projet['titre'] ?? 'Titre non défini'); ?></h3>
                        <a href="index.php?module=professeur&action=detail_projet&id_projet=<?= htmlspecialchars($projet['id_projet']); ?>" class="btn btn-success">
                            Voir le Projet
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Aucun projet disponible.</p>
            <?php endif; ?>
            </div>
        </div>
        <?php
    }

    public function form_creer_livrable($projets_responsable, $form_data = []) {
    ?>
        <style>
        .form-container {
            margin: 20px auto;
            max-width: 600px;
            padding: 30px;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .form-container h1 {
            font-size: 24px;
            margin-bottom: 20px;
            text-align: center;
            color: #2c3e50;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-size: 14px;
            color: #555;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .form-group input[type="file"] {
            padding: 5px;
        }

        .checkbox-container {
            display: flex;
            align-items: center;
            margin-top: 10px;
        }

        .checkbox-container input[type="checkbox"] {
            margin-right: 10px;
            transform: scale(1.2);
        }

        .form-submit {
            display: block;
            width: 100%;
            padding: 10px;
            font-size: 16px;
            color: white;
            background-color: #4cd137;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .form-submit:hover {
            background-color: #44bd32;
        }
        </style>
        <div class="form-container">
        <h1>Créer un Livrable</h1>
        <form action="index.php?module=professeur&action=creer_livrable" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="titre">Titre :</label>
                <input type="text" id="titre" name="titre" placeholder="Titre du livrable" value="<?= htmlspecialchars($form_data['titre'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="description">Description :</label>
                <textarea id="description" name="description" placeholder="Détaillez le contenu attendu" required><?= htmlspecialchars($form_data['description'] ?? ''); ?></textarea>
            </div>
            <div class="form-group">
                <label for="date_limite">Date Limite :</label>
                <input type="date" id="date_limite" name="date_limite" value="<?= htmlspecialchars($form_data['date_limite'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="coefficient">Coefficient :</label>
                <input type="number" id="coefficient" name="coefficient" placeholder="Coefficient" value="<?= htmlspecialchars($form_data['coefficient'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="projet_id">Projet :</label>
                <select id="projet_id" name="projet_id" required>
                    <option value="">Sélectionnez un projet</option>
                    <?php foreach ($projets_responsable as $projet): ?>
                        <option value="<?= htmlspecialchars($projet['id_projet']); ?>" <?= (isset($form_data['projet_id']) && $form_data['projet_id'] == $projet['id_projet']) ? 'selected' : ''; ?>>
                            <?= htmlspecialchars($projet['titre']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group checkbox-container">
                <input type="checkbox" id="is_group" name="is_group" <?= !empty($form_data['is_group']) ? 'checked' : ''; ?>>
                <label for="is_group">Est-ce un rendu groupé ?</label>
            </div>
            <div class="form-group">
                <label for="fichiers">Ajouter des fichiers :</label>
                <input type="file" id="fichiers" name="fichiers[]" multiple>
            </div>
            <button type="submit" class="form-submit">Créer le Livrable</button>
        </form>
        </div>
    <?php
    }

    public function form_modifier_livrable($livrable, $projets_responsable) {
    ?>
        <style>
        .form-container {
            margin: 20px auto;
            max-width: 600px;
            padding: 30px;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .form-container h1 {
            font-size: 24px;
            margin-bottom: 20px;
            text-align: center;
            color: #2c3e50;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-size: 14px;
            color: #555;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .form-group input[type="file"] {
            padding: 5px;
        }

        .checkbox-container {
            display: flex;
            align-items: center;
            margin-top: 10px;
        }

        .checkbox-container input[type="checkbox"] {
            margin-right: 10px;
            transform: scale(1.2);
        }

        .form-submit {
            display: block;
            width: 100%;
            padding: 10px;
            font-size: 16px;
            color: white;
            background-color: #4cd137;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .form-submit:hover {
            background-color: #44bd32;
        }
        </style>

        <div class="form-container">
        <h1>Modifier le Livrable</h1>
        <form action="index.php?module=professeur&action=valider_modification_livrable" method="POST">
            <input type="hidden" name="id_livrable" value="<?= htmlspecialchars($livrable['id_livrable']); ?>">

            <div class="form-group">
                <label for="titre">Titre :</label>
                <input type="text" id="titre" name="titre" value="<?= htmlspecialchars($livrable['titre_livrable']); ?>" required>
            </div>

            <div class="form-group">
                <label for="description">Description :</label>
                <textarea id="description" name="description" required><?= htmlspecialchars($livrable['description']); ?></textarea>
            </div>

            <div class="form-group">
                <label for="date_limite">Date Limite :</label>
                <input type="date" id="date_limite" name="date_limite" value="<?= htmlspecialchars($livrable['date_limite']); ?>" required>
            </div>

            <div class="form-group">
                <label for="coefficient">Coefficient :</label>
                <input type="number" id="coefficient" name="coefficient" value="<?= htmlspecialchars($livrable['coefficient']); ?>" required>
            </div>

            <div class="form-group">
                <label for="projet_id">Projet :</label>
                <select id="projet_id" name="projet_id" required>
                    <?php foreach ($projets_responsable as $projet): ?>
                        <option value="<?= htmlspecialchars($projet['id_projet']); ?>" <?= ($livrable['projet_id'] == $projet['id_projet']) ? 'selected' : ''; ?>>
                            <?= htmlspecialchars($projet['titre']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group checkbox-container">
                <input type="checkbox" id="is_group" name="is_group" <?= !$livrable['isIndividuel'] ? 'checked' : ''; ?>>
                <label for="is_group">Est-ce un rendu groupé ?</label>
            </div>

            <button type="submit" class="form-submit">Enregistrer les modifications</button>
            </form>
        </div>
        <?php
    }

    public function consulter_rendus($livrables, $rendus) {
    ?>
        <style>
            .table-container {
                margin: 20px auto;
                max-width: 1000px;
                padding: 30px;
                background: #ffffff;
                border-radius: 10px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            }

            .table-container h2 {
                font-size: 22px;
                margin-bottom: 20px;
                color: #2c3e50;
                border-bottom: 2px solid #f1f1f1;
                padding-bottom: 5px;
            }

            .table {
                width: 100%;
                border-collapse: collapse;
            }

            .table th,
            .table td {
                padding: 15px;
                text-align: left;
                border: 1px solid #ddd;
            }

            .table th {
                background-color: #4cd137;
                color: white;
            }

            .table tr:nth-child(even) {
                background-color: #f9f9f9;
            }

            .table tr:hover {
                background-color: #f1f1f1;
            }

            .action-btn {
                color: #3498db;
                text-decoration: none;
                font-weight: bold;
            }

            .action-btn:hover {
                color: #2980b9;
            }
        </style>
        <div class="table-container">
            <h2>Livrables</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>Date Limite</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($livrables as $livrable): ?>
                        <tr>
                            <td><?= htmlspecialchars($livrable['titre']); ?></td>
                            <td><?= htmlspecialchars($livrable['date_limite']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <h2>Rendus</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>Étudiant</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rendus as $rendu): ?>
                        <tr>
                            <td><?= htmlspecialchars($rendu['titre_rendu']); ?></td>
                            <td><?= htmlspecialchars($rendu['etudiant']); ?></td>
                            <td>
                                <form action="index.php?module=professeur&action=ajouter_feedback" method="POST">
                                    <input type="hidden" name="rendu_id" value="<?= $rendu['id_rendu']; ?>">
                                    <textarea name="feedback" placeholder="Ajouter un feedback" required></textarea>
                                    <button type="submit" class="action-btn">Envoyer</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php
    }

    public function form_creer_projet($promotions, $professeurs) {
    ?>
        <style>
        .form-container {
            margin: 20px auto;
            max-width: 600px;
            padding: 30px;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            font-family: Arial, sans-serif;
        }

        .form-container h1 {
            font-size: 24px;
            margin-bottom: 20px;
            text-align: center;
            color: #2c3e50;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: bold;
            color: #555;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 12px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
            transition: border 0.3s ease;
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            border-color: #4cd137;
            outline: none;
        }

        .form-group select[multiple] {
            height: auto;
        }

        .form-submit {
            display: block;
            width: 100%;
            padding: 12px;
            font-size: 16px;
            color: white;
            background-color: #4cd137;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-transform: uppercase;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .form-submit:hover {
            background-color: #44bd32;
        }

        .form-hint {
            font-size: 12px;
            color: #888;
            margin-top: 5px;
        }
        </style>

        <div class="form-container">
        <h1>Créer un Projet</h1>
        <form action="index.php?module=professeur&action=creer_projet" method="POST">
            <div class="form-group">
                <label for="titre">Titre :</label>
                <input type="text" id="titre" name="titre" placeholder="Titre du projet" required title="Entrez un titre pour le projet.">
            </div>
            <div class="form-group">
                <label for="description">Description :</label>
                <textarea id="description" name="description" placeholder="Détaillez le contenu du projet" required style="min-height: 100px;" title="Entrez une description détaillée."></textarea>
            </div>
            <div class="form-group">
                <label for="semestre">Semestre :</label>
                <input type="number" id="semestre" name="semestre" placeholder="Numéro du semestre (ex : 1, 2, 3...)" required title="Indiquez le semestre (numéro uniquement).">
            </div>
            <div class="form-group">
                <label for="coefficient">Coefficient :</label>
                <input type="number" id="coefficient" name="coefficient" placeholder="Coefficient du projet (ex : 1.0, 2.5...)" step="0.1" required title="Indiquez le coefficient du projet.">
            </div>
            <div class="form-group">
                <label for="promotions">Promotions :</label>
                <select id="promotions" name="promotions[]" multiple required title="Maintenez Ctrl (ou Cmd sur Mac) pour sélectionner plusieurs promotions.">
                    <?php foreach ($promotions as $promo): ?>
                        <option value="<?= htmlspecialchars($promo['id_promo']); ?>">
                            <?= htmlspecialchars($promo['nom_promo']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <p class="form-hint">Maintenez Ctrl (ou Cmd sur Mac) pour sélectionner plusieurs promotions.</p>
            </div>
            <div class="form-group">
                <label for="responsables">Professeurs responsables :</label>
                <select id="responsables" name="responsables[]" multiple required title="Maintenez Ctrl (ou Cmd sur Mac) pour sélectionner plusieurs professeurs.">
                    <?php foreach ($professeurs as $prof): ?>
                        <option value="<?= htmlspecialchars($prof['id_utilisateur']); ?>">
                            <?= htmlspecialchars($prof['nom'] . ' ' . $prof['prenom']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <p class="form-hint">Maintenez Ctrl (ou Cmd sur Mac) pour sélectionner plusieurs professeurs.</p>
            </div>
            <button type="submit" class="form-submit">Créer le Projet</button>
        </form>
        </div>
    <?php
    }

    public function form_modifier_projet($projet, $promotions, $responsables) {
    ?>
        <style>
        .form-container {
            margin: 20px auto;
            max-width: 600px;
            background-color: #f9f9f9;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .form-container h1 {
            font-size: 24px;
            color: #2c3e50;
            text-align: center;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-size: 14px;
            color: #555;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .form-group textarea {
            height: 100px;
            resize: vertical;
        }

        .form-submit {
            display: block;
            width: 100%;
            padding: 10px;
            font-size: 16px;
            font-weight: bold;
            color: white;
            background-color: #4cd137;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .form-submit:hover {
            background-color: #44bd32;
        }

        .form-group p {
            font-size: 12px;
            color: #777;
        }
        </style>

        <div class="form-container">
        <h1>Modifier un Projet</h1>
        <form action="index.php?module=professeur&action=mettre_a_jour_projet" method="POST">
            <input type="hidden" name="id_projet" value="<?= htmlspecialchars($projet['id_projet']); ?>">

            <div class="form-group">
                <label for="titre">Titre :</label>
                <input type="text" id="titre" name="titre" value="<?= htmlspecialchars($projet['titre']); ?>" required>
            </div>

            <div class="form-group">
                <label for="description">Description :</label>
                <textarea id="description" name="description" required><?= htmlspecialchars($projet['description']); ?></textarea>
            </div>

            <div class="form-group">
                <label for="semestre">Semestre :</label>
                <input type="number" id="semestre" name="semestre" value="<?= htmlspecialchars($projet['semestre'] ?? 0); ?>" required>
            </div>

            <div class="form-group">
                <label for="coefficient">Coefficient :</label>
                <input type="number" id="coefficient" name="coefficient" value="<?= htmlspecialchars($projet['coefficient'] ?? 1); ?>" step="0.1" required>
            </div>

            <div class="form-group">
                <label for="annee_universitaire">Promotions :</label>
                <select id="annee_universitaire" name="promotions[]" multiple required>
                    <?php foreach ($promotions as $promo): ?>
                        <option value="<?= htmlspecialchars($promo['id_promo']); ?>" 
                            <?= in_array($promo['id_promo'], $projet['promotions']) ? 'selected' : ''; ?>>
                            <?= htmlspecialchars($promo['nom_promo']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <p>Utilisez Ctrl (ou Cmd sur Mac) pour sélectionner plusieurs promotions.</p>
            </div>

            <div class="form-group">
                <label for="responsables">Responsables :</label>
                <select id="responsables" name="responsables[]" multiple required>
                    <?php foreach ($responsables as $responsable): ?>
                        <option value="<?= htmlspecialchars($responsable['id_utilisateur']); ?>" 
                            <?= in_array($responsable['id_utilisateur'], $projet['responsables'] ?? []) ? 'selected' : ''; ?>>
                            <?= htmlspecialchars($responsable['nom'] . ' ' . $responsable['prenom']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <p>Utilisez Ctrl (ou Cmd sur Mac) pour sélectionner plusieurs responsables.</p>
            </div>
            <button type="submit" class="form-submit">Mettre à jour</button>
        </form>
        <a href="index.php?module=professeur&action=dashboard" class="form-submit" style="background-color: #ccc; text-align: center;">Retour au tableau de bord</a>
        </div>
    <?php
    }

    public function detail_livrable($livrable) {
        // Vérification que le livrable contient les données nécessaires
        if (!$livrable || empty($livrable['id_livrable'])) {
            echo "<p style='color:red;'>Erreur : Livrable introuvable ou données incomplètes.</p>";
            return;
        }
        ?>
        <style>
        .livrable-container {
            margin: 20px auto;
            max-width: 800px;
            background-color: #f9f9f9;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            font-family: Arial, sans-serif;
        }

        .livrable-container h1 {
            font-size: 28px;
            color: #2c3e50;
            text-align: center;
            margin-bottom: 20px;
        }

        .livrable-container p {
            font-size: 16px;
            margin: 10px 0;
            line-height: 1.6;
        }

        .livrable-container .actions {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn-secondary {
            background-color: #f39c12;
            color: #ffffff;
        }

        .btn-secondary:hover {
            background-color: #e67e22;
        }

        .btn-primary {
            background-color: #3498db;
            color: #ffffff;
        }

        .btn-primary:hover {
            background-color: #2980b9;
        }
        </style>

        <div class="livrable-container">
        <h1><?= htmlspecialchars($livrable['titre_livrable']); ?></h1>
        <p><strong>Description :</strong> <?= htmlspecialchars($livrable['description'] ?? 'Non spécifiée'); ?></p>
        <p><strong>Date limite :</strong> <?= htmlspecialchars($livrable['date_limite'] ?? 'Non définie'); ?></p>
        <p><strong>Coefficient :</strong> <?= htmlspecialchars($livrable['coefficient'] ?? 'Non défini'); ?></p>
        <p><strong>Type :</strong> <?= $livrable['isIndividuel'] ? "Rendu Individuel" : "Rendu Groupé"; ?></p>
        <?php if (!empty($livrable['projet_id'])): ?>
            <p><strong>Projet :</strong> 
                <a href="index.php?module=professeur&action=detail_projet&id_projet=<?= htmlspecialchars($livrable['projet_id']); ?>">
                    Voir le projet associé
                </a>
            </p>
        <?php else: ?>
            <p><strong>Projet :</strong> Non assigné</p>
        <?php endif; ?>

        <div class="actions">
            <a href="index.php?module=professeur&action=modifier_livrable&id_livrable=<?= htmlspecialchars($livrable['id_livrable']); ?>" class="btn btn-primary">
                Modifier le livrable
            </a>
            <a href="index.php?module=professeur&action=detail_projet&id_projet=<?= htmlspecialchars($livrable['projet_id']); ?>" class="btn btn-secondary">
                Retour au projet
            </a>
            <form action="index.php?module=professeur&action=supprimer_livrable" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce livrable ?');" style="display: inline;">
                <input type="hidden" name="id_livrable" value="<?= htmlspecialchars($livrable['id_livrable']); ?>">
                <button type="submit" class="btn btn-danger">Supprimer le livrable</button>
            </form>
        </div>
        </div>
        <?php
    }

    public function detail_projet($projet, $livrables) {
    ?>
        <style>
        .projet-container {
            margin: 20px auto;
            max-width: 800px;
            background-color: #f9f9f9;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            font-family: Arial, sans-serif;
        }

        .projet-container h1 {
            font-size: 28px;
            color: #2c3e50;
            text-align: center;
            margin-bottom: 20px;
        }

        .projet-container p {
            font-size: 16px;
            margin: 10px 0;
            line-height: 1.6;
        }

        .projet-container h2 {
            font-size: 22px;
            color: #4cd137;
            margin-top: 30px;
            margin-bottom: 10px;
        }

        .projet-container table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .projet-container table th,
        .projet-container table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        .projet-container table th {
            background-color: #4cd137;
            color: white;
        }

        .projet-container table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .projet-container table tr:hover {
            background-color: #f1f1f1;
        }

        .filter-bar {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .filter-bar input {
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ddd;
            border-radius: 5px;
            width: 100%;
            max-width: 300px;
        }

        .btn-details {
            color: #3498db;
            text-decoration: none;
            font-weight: bold;
        }

        .btn-details:hover {
            text-decoration: underline;
        }

        .actions {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn-primary {
            background-color: #3498db;
            color: #ffffff;
        }

        .btn-primary:hover {
            background-color: #2980b9;
        }

        .btn-secondary {
            background-color: #f39c12;
            color: #ffffff;
        }

        .btn-secondary:hover {
            background-color: #e67e22;
        }

        .btn-danger {
            background-color: #e74c3c;
            color: #ffffff;
        }

        .btn-danger:hover {
            background-color: #c0392b;
        }
        </style>

        <div class="projet-container">
        <h1><?= htmlspecialchars($projet['titre']); ?></h1>
        <p><strong>Description :</strong> <?= htmlspecialchars($projet['description']); ?></p>
        <p><strong>Semestre :</strong> <?= htmlspecialchars($projet['semestre']); ?></p>
        <p><strong>Coefficient :</strong> <?= htmlspecialchars($projet['coefficient']); ?></p>
        <p><strong>Responsables :</strong> 
            <?= implode(', ', array_map(function ($resp) {
                return htmlspecialchars($resp['prenom'] . ' ' . $resp['nom']);
            }, $projet['responsables'] ?? [])); ?>
        </p>
        <p><strong>Promotions :</strong> 
            <?= implode(', ', array_map(function ($promo) {
                return htmlspecialchars($promo['nom_promo']);
            }, $projet['promotions'] ?? [])); ?>
        </p>

        <h2>Livrables Associés</h2>
        <div class="filter-bar">
            <input type="search" id="search-livrable" placeholder="Rechercher un livrable..." onkeyup="filterLivrables()">
        </div>
        <?php if (!empty($livrables)): ?>
            <table id="livrables-table">
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>Date Limite</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($livrables as $livrable): ?>
                        <tr>
                            <td><?= htmlspecialchars($livrable['titre_livrable']); ?></td>
                            <td><?= htmlspecialchars($livrable['date_limite']); ?></td>
                            <td>
                                <a href="index.php?module=professeur&action=detail_livrable&id_livrable=<?= htmlspecialchars($livrable['id_livrable']); ?>" class="btn-details">
                                    Détails
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Aucun livrable associé à ce projet.</p>
        <?php endif; ?>
        </div>

        <div class="actions">
        <a href="index.php?module=professeur&action=modifier_projet&id_projet=<?= htmlspecialchars($projet['id_projet']); ?>" class="btn btn-primary">
            Modifier le projet
        </a>
        <a href="index.php?module=professeur&action=gestion_groupes&id_projet=<?= htmlspecialchars($projet['id_projet']); ?>" class="btn btn-secondary">
            Gestion des Groupes
        </a>
        <form action="index.php?module=professeur&action=supprimer_projet&id_projet=<?= htmlspecialchars($projet['id_projet']); ?>" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce projet ?');">
            <button type="submit" class="btn btn-danger">Supprimer le projet</button>
        </form>
        </div>

        <script>
        function filterLivrables() {
            const searchInput = document.getElementById('search-livrable').value.toLowerCase();
            const table = document.getElementById('livrables-table');
            const rows = table.getElementsByTagName('tr');

            for (let i = 1; i < rows.length; i++) {
                const titre = rows[i].getElementsByTagName('td')[0]?.textContent.toLowerCase() || '';
                const dateLimite = rows[i].getElementsByTagName('td')[1]?.textContent.toLowerCase() || '';

                if (titre.includes(searchInput) || dateLimite.includes(searchInput)) {
                    rows[i].style.display = '';
                } else {
                    rows[i].style.display = 'none';
                }
            }
        }
        </script>
        <?php
    }


    public function gestion_groupes($id_projet, $etudiants, $groupes) {
    ?>
        <style>
        /* Conteneur principal */
        .gestion-groupes-container {
            max-width: 900px;
            margin: 20px auto;
            padding: 20px;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            font-family: Arial, sans-serif;
        }

        .gestion-groupes-container h1 {
            text-align: center;
            font-size: 28px;
            color: #2c3e50;
            margin-bottom: 20px;
        }

        /* Formulaire pour créer un groupe */
        .form-create-group {
            margin-bottom: 30px;
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .form-create-group h2 {
            font-size: 22px;
            margin-bottom: 15px;
            color: #2c3e50;
        }

        .form-create-group label {
            display: block;
            font-size: 14px;
            color: #555;
            margin-bottom: 5px;
        }

        .form-create-group input[type="text"],
        .form-create-group select {
            width: 100%;
            padding: 10px;
            font-size: 14px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .form-create-group button {
            display: block;
            width: 100%;
            padding: 10px;
            font-size: 16px;
            font-weight: bold;
            color: white;
            background-color: #4cd137;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .form-create-group button:hover {
            background-color: #44bd32;
        }

        /* Tableau des groupes */
        .table-groups {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .table-groups th,
        .table-groups td {
            text-align: left;
            padding: 12px 15px;
            border: 1px solid #ddd;
        }

        .table-groups th {
            background-color: #4cd137;
            color: white;
            font-size: 16px;
        }

        .table-groups tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .table-groups tr:hover {
            background-color: #f1f1f1;
        }

        .table-groups td a {
            color: #3498db;
            text-decoration: none;
            font-weight: bold;
        }

        .table-groups td a:hover {
            color: #2980b9;
        }

        /* Barre de recherche et filtre */
        .filter-bar {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .filter-bar input,
        .filter-bar select {
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ddd;
            border-radius: 5px;
            width: 48%;
        }
        </style>

        <div class="gestion-groupes-container">
        <h1>Gestion des Groupes</h1>

        <!-- Formulaire pour créer un groupe -->
        <div class="form-create-group">
            <h2>Créer un Groupe</h2>
            <form action="index.php?module=professeur&action=creer_groupe" method="POST">
                <input type="hidden" name="id_projet" value="<?= htmlspecialchars($id_projet); ?>">

                <label for="nom_groupe">Nom du groupe :</label>
                <input type="text" id="nom_groupe" name="nom_groupe" required>

                <label for="etudiants">Étudiants :</label>
                <select id="etudiants" name="etudiants[]" multiple required>
                    <?php foreach ($etudiants as $etudiant): ?>
                        <option value="<?= htmlspecialchars($etudiant['id_utilisateur']); ?>">
                            <?= htmlspecialchars($etudiant['prenom'] . ' ' . $etudiant['nom']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <button type="submit">Créer le Groupe</button>
            </form>
        </div>

        <!-- Barre de recherche et filtres -->
        <div class="filter-bar">
            <input type="search" id="search-groupe" placeholder="Rechercher un groupe..." onkeyup="filterTable()">
            <select id="filter-etudiant" onchange="filterTable()">
                <option value="">Tous les étudiants</option>
                <?php foreach ($etudiants as $etudiant): ?>
                    <option value="<?= htmlspecialchars($etudiant['prenom'] . ' ' . $etudiant['nom']); ?>">
                        <?= htmlspecialchars($etudiant['prenom'] . ' ' . $etudiant['nom']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Tableau des groupes -->
        <table class="table-groups" id="groupes-table">
            <thead>
                <tr>
                    <th>Nom du groupe</th>
                    <th>Membres</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($groupes as $groupe): ?>
                    <tr>
                        <td><?= htmlspecialchars($groupe['nom_groupe']); ?></td>
                        <td><?= htmlspecialchars($groupe['membres']); ?></td>
                        <td>
                            <a href="index.php?module=professeur&action=modifier_groupe&id_groupe=<?= htmlspecialchars($groupe['id_groupe']); ?>">Modifier</a>
                            <a href="index.php?module=professeur&action=supprimer_groupe&id_groupe=<?= htmlspecialchars($groupe['id_groupe']); ?>&id_projet=<?= htmlspecialchars($id_projet); ?>" 
                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce groupe ?');">
                                Supprimer
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>

        <script>
        function filterTable() {
            const searchInput = document.getElementById('search-groupe').value.toLowerCase();
            const filterSelect = document.getElementById('filter-etudiant').value.toLowerCase();
            const table = document.getElementById('groupes-table');
            const rows = table.getElementsByTagName('tr');

            for (let i = 1; i < rows.length; i++) {
                const nomGroupe = rows[i].getElementsByTagName('td')[0]?.textContent.toLowerCase() || '';
                const membres = rows[i].getElementsByTagName('td')[1]?.textContent.toLowerCase() || '';

                if (
                    (searchInput === '' || nomGroupe.includes(searchInput)) &&
                    (filterSelect === '' || membres.includes(filterSelect))
                ) {
                    rows[i].style.display = '';
                } else {
                    rows[i].style.display = 'none';
                }
            }
        }
        </script>
    <?php
    }

    public function form_modifier_groupe($groupe, $etudiants) {
    ?>
        <style>
        .form-container {
            margin: 30px auto;
            max-width: 600px;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            font-family: Arial, sans-serif;
        }

        .form-container h1 {
            font-size: 24px;
            margin-bottom: 20px;
            text-align: center;
            color: #2c3e50;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 14px;
            margin-bottom: 5px;
            color: #555;
        }

        .form-group input[type="text"],
        .form-group select {
            width: 100%;
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .form-group select {
            height: 120px;
            overflow-y: auto;
        }

        .form-group p {
            font-size: 14px;
            color: #4caf50;
            background: #e8f5e9;
            padding: 10px;
            border-radius: 5px;
            margin-top: 10px;
        }

        .form-submit {
            display: block;
            width: 100%;
            padding: 10px;
            font-size: 16px;
            color: #ffffff;
            background-color: #4caf50;
            border: none;
            border-radius: 5px;
            text-align: center;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .form-submit:hover {
            background-color: #43a047;
        }

        .form-container hr {
            border: 0;
            height: 1px;
            background: #f1f1f1;
            margin: 20px 0;
        }
        </style>

        <div class="form-container">
        <h1>Modifier le Groupe : <?= htmlspecialchars($groupe['nom_groupe']); ?></h1>
        <form action="index.php?module=professeur&action=mettre_a_jour_groupe" method="POST">
            <input type="hidden" name="id_groupe" value="<?= htmlspecialchars($groupe['id_groupe']); ?>">
            <input type="hidden" name="id_projet" value="<?= htmlspecialchars($groupe['projet_id']); ?>">

            <div class="form-group">
                <label for="nom_groupe">Nom du groupe :</label>
                <input type="text" id="nom_groupe" name="nom_groupe" 
                    value="<?= htmlspecialchars($groupe['nom_groupe']); ?>" required>
            </div>

            <div class="form-group">
                <label for="etudiants">Étudiants :</label>
                <select id="etudiants" name="etudiants[]" multiple required>
                    <?php foreach ($etudiants as $etudiant): ?>
                        <option value="<?= htmlspecialchars($etudiant['id_utilisateur']); ?>" 
                            <?= in_array($etudiant['id_utilisateur'], $groupe['membres_ids']) ? 'selected' : ''; ?>>
                            <?= htmlspecialchars($etudiant['prenom'] . ' ' . $etudiant['nom']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <hr>

            <div class="form-group">
                <p><strong>Membres actuels :</strong> 
                    <?= htmlspecialchars($groupe['membres_noms'] ?? 'Aucun'); ?>
                </p>
            </div>

            <button type="submit" class="form-submit">Mettre à jour le Groupe</button>
        </form>
        </div>
    <?php
    }

    public function confirm_creer_livrable() {
        echo "<p style='color: green; text-align: center;'>Le livrable a été créé avec succès.</p>";
        echo "<a href='index.php?module=professeur&action=form_creer_livrable' style='display: block; text-align: center; margin-top: 20px;'>Créer un autre livrable</a>";
    }


    public function confirm_ajouter_feedback() {
        echo "<p style='color: green; text-align: center;'>Le feedback a été ajouté avec succès.</p>";
    }

    public function erreurBD() {
        echo "<p style='color: red; text-align: center;'>Erreur lors de l'exécution en base de données.</p>";
    }
}
