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
            font-size: 16px;
            padding: 10px 15px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .menu a:hover {
            background-color: #4cd137;
        }

        .menu a.active {
            background-color: #4cd137;
        }
        </style>

        <nav class="menu">
            <a href="index.php?module=etudiant&action=dashboard" class="active">Tableau de Bord</a>
            <a href="index.php?module=etudiant&action=consulter_feedbacks">Consulter les Feedbacks</a>
            <a href="index.php?module=etudiant&action=consulter_notifications">Notifications</a>
            <a href="index.php?module=etudiant&action=messagerie">Messagerie</a>
            <a href="index.php?module=connexion&action=deconnexion">Déconnexion</a>
        </nav>
        <?php
    }

    public function afficher_dashboard($projets, $etudiant) {
        $this->menu();
        ?>
        <style>
            .dashboard-container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            font-family: Arial, sans-serif;
        }

        .dashboard-container h1 {
            font-size: 28px;
            text-align: center;
            color: #2c3e50;
            margin-bottom: 30px;
        }

        .projet-card {
            display: inline-block;
            width: 30%;
            margin: 10px 1%;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s ease;
        }

        .projet-card:hover {
            transform: scale(1.03);
        }

        .projet-card h3 {
            font-size: 20px;
            color: #4cd137;
            margin: 15px;
        }

        .projet-card p {
            font-size: 14px;
            color: #555;
            margin: 0 15px 15px 15px;
            line-height: 1.6;
        }

        .projet-card a {
            display: block;
            text-align: center;
            padding: 10px;
            background-color: #4cd137;
            color: white;
            text-decoration: none;
            font-weight: bold;
            border-bottom-left-radius: 10px;
            border-bottom-right-radius: 10px;
            transition: background-color 0.3s ease;
        }

        .projet-card a:hover {
            background-color: #44bd32;
        }
        </style>

        <div class="dashboard-container">
            <h1>Bienvenue dans votre espace étudiant, <?= htmlspecialchars($etudiant['prenom'] . ' ' . $etudiant['nom']); ?></h1>
            <h2>Projets :</h2>
            <?php if (empty($projets)): ?>
                <p>Aucun projet n'est disponible pour le moment.</p>
            <?php else: ?>
                <?php foreach ($projets as $projet): ?>
                    <div class="projet-card">
                        <h3><?= htmlspecialchars($projet['titre']); ?></h3>
                        <p><?= htmlspecialchars($projet['description']); ?></p>
                        <a href="index.php?module=etudiant&action=detail_projet&id_projet=<?= htmlspecialchars($projet['id_projet']); ?>">Voir les détails</a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <?php
    }

    public function afficher_detail_projet($projet, $livrables, $groupes, $groupe_etudiant) {
        $this->menu();
        ?>
        <style>
        .projet-detail-container {
            max-width: 1000px;
            margin: 20px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            font-family: Arial, sans-serif;
        }

        .projet-detail-container h1 {
            font-size: 28px;
            color: #2c3e50;
            margin-bottom: 20px;
        }

        .section-title {
            font-size: 20px;
            margin-top: 30px;
            color: #4cd137;
            border-bottom: 2px solid #4cd137;
            padding-bottom: 5px;
        }

        .livrable-table, .groupe-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .livrable-table th, .groupe-table th, .livrable-table td, .groupe-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        .livrable-table th, .groupe-table th {
            background-color: #4cd137;
            color: white;
        }

        .livrable-table tr:nth-child(even), .groupe-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .livrable-table tr:hover, .groupe-table tr:hover {
            background-color: #f1f1f1;
        }

        .btn-primary {
            display: inline-block;
            padding: 10px 15px;
            font-size: 14px;
            color: white;
            background-color: #3498db;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }

        .btn-primary:hover {
            background-color: #2980b9;
        }
        </style>

        <div class="projet-detail-container">
            <h1><?= htmlspecialchars($projet['titre']); ?></h1>
            <p><strong>Description :</strong> <?= htmlspecialchars($projet['description']); ?></p>
            <p><strong>Semestre :</strong> <?= htmlspecialchars($projet['semestre']); ?></p>

            <div>
                <h2 class="section-title">Votre Groupe de Projet</h2>
                <?php if ($groupe_etudiant): ?>
                    <p>Vous êtes dans le groupe : <strong><?= htmlspecialchars($groupe_etudiant['nom_groupe']); ?></strong></p>
                <?php else: ?>
                    <p>Vous n'êtes pas encore assigné à un groupe.</p>
                <?php endif; ?>
            </div>

            <div>
                <h2 class="section-title">Livrables</h2>
                <div class="filter-bar">
                    <input type="text" id="search-livrable" placeholder="Rechercher un livrable..." onkeyup="filterLivrables()">
                </div>
                <table class="livrable-table" id="livrable-table">
                    <thead>
                        <tr>
                            <th>Titre</th>
                            <th>Date Limite</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($livrables as $livrable): ?>
                            <tr>
                                <td><?= htmlspecialchars($livrable['titre_livrable']); ?></td>
                                <td><?= htmlspecialchars($livrable['date_limite']); ?></td>
                                <td>
                                    <a href="index.php?module=etudiant&action=detail_livrable&id_livrable=<?= htmlspecialchars($livrable['id_livrable']); ?>" class="btn-primary">
                                        Voir les détails
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <script>
            function filterLivrables() {
                const input = document.getElementById("search-livrable");
                const filter = input.value.toLowerCase();
                const table = document.getElementById("livrable-table");
                const rows = table.getElementsByTagName("tr");

                for (let i = 1; i < rows.length; i++) {
                    const titreCell = rows[i].getElementsByTagName("td")[0];
                    if (titreCell) {
                        const titre = titreCell.textContent || titreCell.innerText;
                        rows[i].style.display = titre.toLowerCase().includes(filter) ? "" : "none";
                    }
                }
            }
        </script>
        <?php
    }

    public function afficher_detail_livrable($livrable, $rendu) {
        $this->menu();
        ?>
        <style>
        .livrable-detail-container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            font-family: Arial, sans-serif;
        }

        .livrable-detail-container h1 {
            font-size: 28px;
            color: #2c3e50;
            margin-bottom: 20px;
        }

        .rendu-section {
            margin-top: 30px;
            border-top: 2px solid #4cd137;
            padding-top: 15px;
        }

        .rendu-section form {
            margin-top: 20px;
        }

        .btn-submit {
            background-color: #4cd137; /* Vert d'origine */
            color: white;
            font-size: 16px;
            font-weight: bold;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
        }

        .btn-submit:hover {
            background-color: #44bd32; /* Légèrement plus foncé au survol */
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            font-size: 14px;
            color: #555;
            display: block;
            margin-bottom: 5px;
        }

        .form-group input, .form-group textarea {
            width: 100%;
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .form-group button {
            background-color: #4cd137;
            color: white;
            font-size: 16px;
            font-weight: bold;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .form-group button:hover {
            background-color: #44bd32;
        }
        </style>

            <div class="livrable-detail-container">
            <h1>Détails du Livrable : <?= htmlspecialchars($livrable['titre_livrable']); ?></h1>
            <p><strong>Description :</strong> <?= htmlspecialchars($livrable['description']); ?></p>
            <p><strong>Date Limite :</strong> <?= htmlspecialchars($livrable['date_limite']); ?></p>

            <div class="rendu-section">
            <h2>Votre Rendu</h2>
            <?php if ($rendu): ?>
                <p>
                    <a href="<?= htmlspecialchars($rendu['fichier']); ?>" target="_blank" class="btn btn-modify">Télécharger le Rendu</a>
                </p>
                <form action="index.php?module=etudiant&action=modifier_rendu" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="livrable_id" value="<?= htmlspecialchars($livrable['id_livrable']); ?>">
                    <div class="form-group">
                        <label for="nouveaux_fichiers">Modifier les Fichiers :</label>
                        <input type="file" id="nouveaux_fichiers" name="fichiers[]" multiple>
                    </div>
                    <button type="submit" class="form-group button">Mettre à Jour</button>
                </form>
                <form action="index.php?module=etudiant&action=supprimer_rendu" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce rendu ?');">
                    <input type="hidden" name="livrable_id" value="<?= htmlspecialchars($livrable['id_livrable']); ?>">
                    <button type="submit" class="btn btn-delete">Supprimer le Rendu</button>
                </form>
                <?php else: ?>
                <p>Aucun rendu soumis pour ce livrable.</p>
                <form action="index.php?module=etudiant&action=soumettre_rendu" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="livrable_id" value="<?= htmlspecialchars($livrable['id_livrable']); ?>">
                    <div class="form-group">
                        <label for="description">Description :</label>
                        <textarea name="description" id="description" rows="5" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="fichiers">Ajouter des Fichiers :</label>
                        <input type="file" id="fichiers" name="fichiers[]" multiple required>
                    </div class="form-group">
                    <div >
                        <p> Vous pouvez modifier ou supprimer le rendu qu'une fois existant.
                    </div >
                    <button type="submit" class="btn btn-submit">Soumettre</button>
                </form>
                <?php endif; ?>
            </div>
        </div>
    <?php
    }
}

?>
