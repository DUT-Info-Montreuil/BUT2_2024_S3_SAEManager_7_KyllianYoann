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
                        <h3><?= htmlspecialchars($projet['titre']); ?></h3>
                        <p><?= htmlspecialchars($projet['description']); ?></p>
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



    public function form_creer_livrable() {
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
            .form-group textarea {
                width: 100%;
                padding: 10px;
                font-size: 14px;
                border: 1px solid #ccc;
                border-radius: 5px;
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
            <form action="index.php?module=professeur&action=creer_livrable" method="POST">
                <div class="form-group">
                    <label for="titre">Titre :</label>
                    <input type="text" id="titre" name="titre" placeholder="Titre du livrable" required>
                </div>
                <div class="form-group">
                    <label for="description">Description :</label>
                    <textarea id="description" name="description" placeholder="Détaillez le contenu attendu" required></textarea>
                </div>
                <div class="form-group">
                    <label for="date_limite">Date Limite :</label>
                    <input type="date" id="date_limite" name="date_limite" required>
                </div>
                <div class="form-group">
                    <label for="coefficient">Coefficient :</label>
                    <input type="number" id="coefficient" name="coefficient" placeholder="Coefficient" required>
                </div>
                <div class="form-group">
                <input type="radio" id="True" name="isIndividuel" value="True">
                <label for="True">Individuel</label>

                <input type="radio" id="False" name="isIndividuel" value="False">
                <label for="False">Groupe</label>
                </div>
                <button type="submit" class="form-submit">Créer le Livrable</button>
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
            <h1>Créer un Projet</h1>
            <form action="index.php?module=professeur&action=creer_projet" method="POST">
                <div class="form-group">
                    <label for="titre">Titre :</label>
                    <input type="text" id="titre" name="titre" placeholder="Titre du projet" required>
                </div>
                <div class="form-group">
                    <label for="description">Description :</label>
                    <textarea id="description" name="description" placeholder="Détaillez le contenu du projet" required></textarea>
                </div>
                <div class="form-group">
                    <label for="semestre">Semestre :</label>
                    <input type="number" id="semestre" name="semestre" placeholder="Numéro du semestre (ex : 1, 2, 3...)" required>
                </div>
                <div class="form-group">
                    <label for="coefficient">Coefficient :</label>
                    <input type="number" id="coefficient" name="coefficient" placeholder="Coefficient du projet (ex : 1.0, 2.5...)" step="0.1" required>
                </div>
                <div class="form-group">
                    <label for="promotions">Promotions :</label>
                    <select id="promotions" name="promotions[]" multiple required>
                        <?php foreach ($promotions as $promo): ?>
                            <option value="<?= htmlspecialchars($promo['id_promo']); ?>">
                                <?= htmlspecialchars($promo['nom_promo']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="responsables">Professeurs responsables :</label>
                    <select id="responsables" name="responsables[]" multiple required>
                        <?php foreach ($professeurs as $prof): ?>
                            <option value="<?= $prof['id_utilisateur']; ?>">
                                <?= htmlspecialchars($prof['nom'] . ' ' . $prof['prenom']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
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

        .projet-container ul {
            list-style-type: none;
            padding: 0;
        }

        .projet-container ul li {
            background-color: #ffffff;
            margin: 10px 0;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
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
            <?php if (!empty($projet['responsables'])): ?>
                <?= implode(', ', array_map(function ($resp) {
                    return htmlspecialchars($resp['prenom'] . ' ' . $resp['nom']);
                }, $projet['responsables'])); ?>
            <?php else: ?>
                Non spécifié
            <?php endif; ?>
        </p>

        <p><strong>Promotions :</strong> 
            <?php if (!empty($projet['promotions'])): ?>
                <?= implode(', ', array_map(function ($promo) {
                    return htmlspecialchars($promo['nom_promo']);
                }, $projet['promotions'])); ?>
            <?php else: ?>
                Non spécifié
            <?php endif; ?>
        </p>


        <h2>Livrables associés</h2>
        <ul>
            <?php foreach ($livrables as $livrable): ?>
                <li>
                    <strong><?= htmlspecialchars($livrable['titre_livrable']); ?> :</strong> 
                    <?= htmlspecialchars($livrable['description']); ?> (Date limite : <?= htmlspecialchars($livrable['date_limite']); ?>)
                </li>
            <?php endforeach; ?>
        </ul>

            <div class="actions">
            <a href="index.php?module=professeur&action=modifier_projet&id_projet=<?= htmlspecialchars($projet['id_projet']); ?>" class="btn btn-primary">
                Modifier le projet
            </a>
            <a href="index.php?module=professeur&action=gestion_groupes&id_projet=<?= htmlspecialchars($projet['id_projet']); ?>" class="btn btn-secondary">
                Gestion des Groupes
            </a>
            <form action="index.php?module=professeur&action=supprimer_projet&id_projet=<?= htmlspecialchars($projet['id_projet']); ?>" method="GET" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce projet ?');">
                <button type="submit" class="btn btn-danger">Supprimer le projet</button>
            </form>
            </div>
        </div>
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
    }

    public function confirm_ajouter_feedback() {
        echo "<p style='color: green; text-align: center;'>Le feedback a été ajouté avec succès.</p>";
    }

    public function erreurBD() {
        echo "<p style='color: red; text-align: center;'>Erreur lors de l'exécution en base de données.</p>";
    }
}
