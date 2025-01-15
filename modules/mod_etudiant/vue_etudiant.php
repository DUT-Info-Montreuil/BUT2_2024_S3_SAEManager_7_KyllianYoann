<?php

class VueEtudiant extends VueGenerique {
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
                padding: 15px 20px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            }

            .menu a {
                color: white;
                text-decoration: none;
                font-weight: bold;
                font-size: 18px;
                transition: color 0.3s ease;
            }

            .menu a:hover {
                color: #4cd137;
            }

            .menu a.active {
                color: #4cd137;
                border-bottom: 2px solid #4cd137;
            }
        </style>
        <nav class="menu">
            <a href="index.php?module=etudiant&action=dashboard" class="active">Tableau de Bord</a>
            <a href="index.php?module=etudiant&action=form_soumettre_rendu">Soumettre un Rendu</a>
            <a href="index.php?module=etudiant&action=consulter_feedbacks">Consulter les Feedbacks</a>
        </nav>
        <?php
    }

    public function dashboard($livrables, $groupe, $coefficients) {
        ?>
        <style>
            /* Tableau de bord */
            .dashboard-container {
                margin: 20px auto;
                max-width: 1200px;
                padding: 30px;
                background-color: #ffffff;
                border-radius: 15px;
                box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            }

            .section {
                margin-bottom: 40px;
            }

            .section h2 {
                font-size: 24px;
                margin-bottom: 15px;
                color: #2c3e50;
                border-bottom: 2px solid #f1f1f1;
                padding-bottom: 5px;
            }

            .livrable-table, .group-table, .coeff-table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 20px;
            }

            .livrable-table th, .group-table th, .coeff-table th,
            .livrable-table td, .group-table td, .coeff-table td {
                padding: 15px;
                text-align: left;
                border: 1px solid #ddd;
            }

            .livrable-table th, .group-table th, .coeff-table th {
                background-color: #4cd137;
                color: white;
            }

            .livrable-table tr:nth-child(even),
            .group-table tr:nth-child(even),
            .coeff-table tr:nth-child(even) {
                background-color: #f9f9f9;
            }

            .livrable-table tr:hover,
            .group-table tr:hover,
            .coeff-table tr:hover {
                background-color: #f1f1f1;
            }

            .action-btn {
                color: #3498db;
                text-decoration: none;
                font-weight: bold;
                margin-right: 10px;
                transition: color 0.3s ease;
            }

            .action-btn:hover {
                color: #2980b9;
            }
        </style>

        <div class="dashboard-container">
            <!-- Section des livrables -->
            <div class="section">
                <h2>Mes Livrables</h2>
                <table class="livrable-table">
                    <thead>
                        <tr>
                            <th>Titre</th>
                            <th>Date Limite</th>
                            <th>Statut</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($livrables as $livrable): ?>
                            <tr>
                                <td><?= htmlspecialchars($livrable['titre_livrable'] ?? 'N/A'); ?></td>
                                <td><?= htmlspecialchars($livrable['date_limite'] ?? 'N/A'); ?></td>
                                <td><?= htmlspecialchars($livrable['statut'] ?? 'Non spécifié'); ?></td>
                                <td>
                                    <a href="index.php?module=etudiant&action=form_soumettre_rendu&id=<?= $livrable['id_livrable']; ?>" class="action-btn">Soumettre</a>
                                    <a href="index.php?module=etudiant&action=details_livrable&id=<?= $livrable['id_livrable']; ?>" class="action-btn">Détails</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Section Groupe -->
            <div class="section">
                <h2>Mon Groupe</h2>
                <table class="group-table">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Membres</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?= htmlspecialchars($groupe['nom_groupe'] ?? 'Aucun groupe assigné'); ?></td>
                            <td>
                                <?php if (!empty($groupe['membres']) && is_array($groupe['membres'])): ?>
                                    <?php foreach ($groupe['membres'] as $membre): ?>
                                        <?= htmlspecialchars($membre); ?> <br>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    Aucun membre dans ce groupe.
                                <?php endif; ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Section Coefficients -->
            <div class="section">
                <h2>Coefficients des Matières</h2>
                <table class="coeff-table">
                    <thead>
                        <tr>
                            <th>Matière</th>
                            <th>Coefficient</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($coefficients as $coefficient): ?>
                            <tr>
                                <td><?= htmlspecialchars($coefficient['titre_livrable'] ?? 'Non spécifié'); ?></td>
                                <td><?= htmlspecialchars($coefficient['coefficient'] ?? 'Non spécifié'); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php
    }

    public function form_soumettre_rendu($livrable = null, $livrables = []) {
        ?>
        <style>
            .form-container {
                margin: 40px auto;
                max-width: 600px;
                padding: 30px;
                background-color: #f7f9fc;
                border-radius: 15px;
                box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            }

            .form-container h1 {
                font-size: 24px;
                margin-bottom: 20px;
                color: #2c3e50;
                text-align: center;
            }

            .form-group {
                margin-bottom: 20px;
            }

            .form-group label {
                display: block;
                font-size: 16px;
                margin-bottom: 5px;
                color: #2c3e50;
            }

            .form-group input,
            .form-group select {
                width: 100%;
                padding: 10px;
                border: 1px solid #ddd;
                border-radius: 8px;
                font-size: 14px;
                color: #333;
            }

            .form-submit {
                display: block;
                width: 100%;
                padding: 10px;
                background-color: #4cd137;
                color: white;
                border: none;
                border-radius: 8px;
                font-size: 16px;
                cursor: pointer;
            }

            .form-submit:hover {
                background-color: #44bd32;
            }
        </style>

        <div class="form-container">
            <h1>Soumettre un Rendu</h1>
            <?php if ($livrable): ?>
                <h2>Pour le Livrable : <?= htmlspecialchars($livrable['titre_livrable'] ?? 'Non défini'); ?></h2>  
                <p><strong>Description :</strong> <?= htmlspecialchars($livrable['description'] ?? 'Non défini'); ?></p>
                <p><strong>Coefficient :</strong> <?= htmlspecialchars($livrable['coefficient'] ?? 'Non défini'); ?></p>
                <p><strong>Date Limite :</strong> <?= htmlspecialchars($livrable['date_limite'] ?? 'Non défini'); ?></p>
            <?php endif; ?>

            <form action="index.php?module=etudiant&action=soumettre_rendu" method="POST" enctype="multipart/form-data">
                <?php if (!$livrable): ?>
                    <div class="form-group">
                        <label for="livrable_id">Choisissez un Livrable :</label>
                        <select id="livrable_id" name="livrable_id" required>
                            <?php foreach ($livrables as $l): ?>
                                <option value="<?= $l['id_livrable']; ?>"><?= htmlspecialchars($l['titre_livrable']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                <?php else: ?>
                    <input type="hidden" name="livrable_id" value="<?= $livrable['id_livrable'] ?? 'Non défini'; ?>">
                <?php endif; ?>

                <div class="form-group">
                    <label for="fichier">Fichier :</label>
                    <input type="file" id="fichier" name="fichier" required>
                </div>
                <button type="submit" class="form-submit">Soumettre</button>
            </form>
        </div>
        <?php
    }

    public function details_livrable($livrable, $rendu, $evaluations, $feedbacks, $commentaires) {
    ?>
    <style>
        .details-container {
            margin: 40px auto;
            max-width: 800px;
            padding: 30px;
            background-color: #f7f9fc;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        .details-container h1, h2, h3 {
            color: #2c3e50;
        }

        .details-container ul {
            list-style-type: none;
            padding: 0;
        }

        .details-container ul li {
            margin-bottom: 10px;
            background-color: #fff;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
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

        .form-group input[type="file"],
        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }

        .form-submit {
            padding: 10px 20px;
            background-color: #4cd137;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .form-submit:hover {
            background-color: #44bd32;
        }
    </style>

    <div class="details-container">
        <h1>Détails du Livrable : <?= htmlspecialchars($livrable['titre_livrable']); ?></h1>
        <p><strong>Description :</strong> <?= htmlspecialchars($livrable['description']); ?></p>
        <p><strong>Coefficient :</strong> <?= htmlspecialchars($livrable['coefficient']); ?></p>
        <p><strong>Date Limite :</strong> <?= htmlspecialchars($livrable['date_limite']); ?></p>

        <h2>Votre Rendu</h2>
        <?php if ($rendu): ?>
            <p><a href="<?= htmlspecialchars($rendu['fichier']); ?>" target="_blank">Télécharger le rendu</a></p>
            <form action="index.php?module=etudiant&action=modifier_rendu" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="livrable_id" value="<?= htmlspecialchars($livrable['id_livrable']); ?>">
                <div class="form-group">
                    <label for="nouveau_fichier">Modifier le Fichier :</label>
                    <input type="file" id="nouveau_fichier" name="fichier" required>
                </div>
                <button type="submit" class="form-submit">Mettre à Jour</button>
            </form>
        <?php else: ?>
            <p>Aucun rendu soumis pour ce livrable.</p>
        <?php endif; ?>

        <h2>Évaluations</h2>
        <?php foreach ($evaluations as $evaluation): ?>
            <h3>Évaluation : <?= htmlspecialchars($evaluation['type']); ?> (Note : <?= htmlspecialchars($evaluation['note']); ?>)</h3>
            <ul>
                <?php if (isset($commentaires[$evaluation['id_evaluation']])): ?>
                    <?php foreach ($commentaires[$evaluation['id_evaluation']] as $commentaire): ?>
                        <li>
                            <p><?= htmlspecialchars($commentaire['contenu']); ?></p>
                            <small><?= htmlspecialchars($commentaire['date_commentaire']); ?></small>
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li>Aucun commentaire pour cette évaluation.</li>
                <?php endif; ?>
            </ul>
            <form action="index.php?module=etudiant&action=ajouter_commentaire" method="POST">
                <input type="hidden" name="evaluation_id" value="<?= $evaluation['id_evaluation']; ?>">
                <textarea name="contenu" placeholder="Ajouter un commentaire..." required></textarea>
                <button type="submit" class="form-submit">Ajouter</button>
            </form>
        <?php endforeach; ?>

        <h2>Feedbacks</h2>
        <ul>
            <?php foreach ($feedbacks as $feedback): ?>
                <li><?= htmlspecialchars($feedback['contenu']); ?> (<?= htmlspecialchars($feedback['date_feedback']); ?>)</li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php
}

}
