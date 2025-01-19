<?php

class VueAdmin extends VueGenerique {
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
            <a href="index.php?module=admin&action=dashboard" class="active">Dashboard</a>
            <a href="index.php?module=admin&action=liste_utilisateurs">Liste des Utilisateurs</a>
            <a href="index.php?module=admin&action=form_creer_utilisateur">Créer un Utilisateur</a>
        </nav>
        <?php
    }

    public function dashboard($statistiques) {
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

            .stats-container {
                display: flex;
                justify-content: space-between;
                margin-top: 20px;
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
                margin-bottom: 10px;
            }

            .stat-icon.admin {
                color: #f39c12;
            }

            .stat-icon.professeur {
                color: #3498db;
            }

            .stat-icon.etudiant {
                color: #2ecc71;
            }
        </style>

        <div class="dashboard-container">
            <div class="dashboard-header">
                <h1>Dashboard Administrateur</h1>
                <p>Bienvenue dans votre espace de gestion.</p>
            </div>

            <div class="stats-container">
                <div class="stat-box">
                    <div class="stat-icon admin">👨‍💼</div>
                    <h3><?= htmlspecialchars($statistiques['admins'] ?? 0); ?></h3>
                    <p>Admins</p>
                </div>
                <div class="stat-box">
                    <div class="stat-icon professeur">📚</div>
                    <h3><?= htmlspecialchars($statistiques['professeurs'] ?? 0); ?></h3>
                    <p>Professeurs</p>
                </div>
                <div class="stat-box">
                    <div class="stat-icon etudiant">🎓</div>
                    <h3><?= htmlspecialchars($statistiques['etudiants'] ?? 0); ?></h3>
                    <p>Étudiants</p>
                </div>
            </div>
        </div>
        <?php
    }

    public function liste_utilisateurs($utilisateurs) {
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
            <h2>Liste des Utilisateurs</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Login</th>
                        <th>Rôle</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($utilisateurs as $utilisateur): ?>
                        <tr>
                            <td><?= htmlspecialchars($utilisateur['nom']); ?></td>
                            <td><?= htmlspecialchars($utilisateur['prenom']); ?></td>
                            <td><?= htmlspecialchars($utilisateur['login']); ?></td>
                            <td><?= htmlspecialchars($utilisateur['role']); ?></td>
                            <td>
                                <a href="index.php?module=admin&action=form_modifier_utilisateur&id=<?= $utilisateur['id_utilisateur']; ?>" class="action-btn">Modifier</a>
                                <a href="index.php?module=admin&action=supprimer_utilisateur&id=<?= $utilisateur['id_utilisateur']; ?>" class="action-btn">Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php
    }

    public function form_creer_utilisateur() {
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
            <h1>Créer un Utilisateur</h1>
            <form action="index.php?module=admin&action=creer_utilisateur" method="POST">
                <div class="form-group">
                    <label for="nom">Nom :</label>
                    <input type="text" id="nom" name="nom" placeholder="Nom de l'utilisateur" required>
                </div>
                <div class="form-group">
                    <label for="prenom">Prénom :</label>
                    <input type="text" id="prenom" name="prenom" placeholder="Prénom de l'utilisateur" required>
                </div>
                <div class="form-group">
                    <label for="login">Login :</label>
                    <input type="text" id="login" name="login" placeholder="Identifiant de connexion" required>
                </div>
                <div class="form-group">
                    <label for="mdp">Mot de Passe :</label>
                    <input type="password" id="mdp" name="mdp" placeholder="Mot de passe" required>
                </div>
                <div class="form-group">
                    <label for="role">Rôle :</label>
                    <select id="role" name="role" required>
                        <option value="etudiant">Étudiant</option>
                        <option value="professeur">Professeur</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <button type="submit" class="form-submit">Créer l'Utilisateur</button>
            </form>
        </div>
        <?php
    }

    public function form_modifier_utilisateur($utilisateur) {
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
        .form-group select {
            width: 100%;
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .form-group input[readonly] {
            background-color: #f1f1f1;
            cursor: not-allowed;
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
        <h1>Modifier un Utilisateur</h1>
        <form action="index.php?module=admin&action=modifier_utilisateur&id=<?= $utilisateur['id_utilisateur'] ?>" method="POST">
            <div class="form-group">
                <label for="nom">Nom :</label>
                <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($utilisateur['nom']) ?>" required>
            </div>
            <div class="form-group">
                <label for="prenom">Prénom :</label>
                <input type="text" id="prenom" name="prenom" value="<?= htmlspecialchars($utilisateur['prenom']) ?>" required>
            </div>
            <div class="form-group">
                <label for="login">Login :</label>
                <input type="text" id="login" name="login" value="<?= htmlspecialchars($utilisateur['login']) ?>" required>
            </div>
            <div class="form-group">
                <label for="mdp">Nouveau Mot de Passe (laisser vide pour ne pas modifier) :</label>
                <input type="password" id="mdp" name="mdp" placeholder="Entrer un nouveau mot de passe">
            </div>
            <div class="form-group">
                <label for="role">Rôle :</label>
                <select id="role" name="role" required>
                    <option value="etudiant" <?= $utilisateur['role'] === 'etudiant' ? 'selected' : '' ?>>Étudiant</option>
                    <option value="professeur" <?= $utilisateur['role'] === 'professeur' ? 'selected' : '' ?>>Professeur</option>
                    <option value="admin" <?= $utilisateur['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                </select>
            </div>
            <button type="submit" class="form-submit">Modifier l'Utilisateur</button>
        </form>
    </div>
    <?php
    }

    public function confirm_supprimer_utilisateur() {
        echo "<p>L'utilisateur a été supprimé avec succès.</p>";
    }

    public function confirm_creer_utilisateur() {
        echo "<p>L'utilisateur a été créé avec succès.</p>";
    }

    public function erreurBD() {
        echo "<p>Erreur lors de l'exécution en base de données.</p>";
    }
}
