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
                        <a href="index.php?module=professeur&action=details_projet&id_projet=<?= $projet['id_projet']; ?>">Voir le Projet</a>
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
                <label for="promotion">Promotion :</label>
                <select id="promotion" name="promotion" required>
                    <option value="" disabled selected>Choisissez une promotion</option>
                    <?php foreach ($promotions as $promo): ?>
                        <option value="<?= $promo['id_promo']; ?>"><?= htmlspecialchars($promo['nom_promo']); ?></option>
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
