<?php

class VueEtudiant extends VueGenerique {
    public function __construct() {
        parent::__construct();
    }

    public function menu() {
        ?>
        <ul>
            <li><a href="index.php?module=etudiant&action=dashboard">Dashboard</a></li>
            <li><a href="index.php?module=etudiant&action=form_soumettre_rendu">Soumettre un rendu</a></li>
            <li><a href="index.php?module=etudiant&action=consulter_feedbacks">Consulter les feedbacks</a></li>
        </ul>
        <?php
    }

    public function dashboard($livrables) {
        ?>
        <h1>Dashboard Étudiant</h1>
        <h2>Livrables disponibles</h2>
        <ul>
            <?php foreach ($livrables as $livrable) { ?>
                <li><?= htmlspecialchars($livrable["titre"]); ?> (Date limite : <?= $livrable["date_limite"]; ?>)</li>
            <?php } ?>
        </ul>
        <?php
    }

    public function form_soumettre_rendu($livrables) {
        ?>
        <h1>Soumettre un rendu</h1>
        <form action="index.php?module=etudiant&action=soumettre_rendu" method="POST" enctype="multipart/form-data">
            Titre : <input type="text" name="titre" required><br>
            Livrable :
            <select name="livrable_id" required>
                <?php foreach ($livrables as $livrable) { ?>
                    <option value="<?= $livrable["id_livrable"]; ?>"><?= htmlspecialchars($livrable["titre"]); ?></option>
                <?php } ?>
            </select><br>
            Fichier : <input type="file" name="fichier" required><br>
            <input type="submit" value="Soumettre">
        </form>
        <?php
    }

    public function consulter_feedbacks($feedbacks) {
        ?>
        <h1>Feedbacks</h1>
        <ul>
            <?php foreach ($feedbacks as $feedback) { ?>
                <li>
                    Livrable : <?= htmlspecialchars($feedback["livrable"]); ?><br>
                    Feedback : <?= htmlspecialchars($feedback["contenu"]); ?>
                </li>
            <?php } ?>
        </ul>
        <?php
    }

    public function confirm_soumettre_rendu() {
        echo "<p>Rendu soumis avec succès.</p>";
    }

    public function erreurBD() {
        echo "<p>Erreur lors de l'exécution en base de données.</p>";
    }

    public function erreur_upload() {
        echo "<p>Erreur lors du téléchargement du fichier.</p>";
    }
}
