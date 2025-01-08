<?php

class VueProfesseur extends VueGenerique {
    public function __construct() {
        parent::__construct();
    }

    public function menu() {
        ?>
        <ul>
            <li><a href="index.php?module=professeur&action=dashboard">Dashboard</a></li>
            <li><a href="index.php?module=professeur&action=form_creer_livrable">Créer un livrable</a></li>
            <li><a href="index.php?module=professeur&action=consulter_rendus">Consulter les rendus</a></li>
        </ul>
        <?php
    }

    public function dashboard() {
        ?>
        <h1>Dashboard Professeur</h1>
        <p>Bienvenue dans votre espace.</p>
        <?php
    }

    public function form_creer_livrable() {
        ?>
        <h1>Créer un livrable</h1>
        <form action="index.php?module=professeur&action=creer_livrable" method="POST">
            Titre : <input type="text" name="titre" required><br>
            Description : <textarea name="description" required></textarea><br>
            Date limite : <input type="date" name="date_limite" required><br>
            Coefficient : <input type="number" name="coefficient" required><br>
            <input type="submit" value="Créer">
        </form>
        <?php
    }

    public function consulter_rendus($livrables, $rendus) {
        ?>
        <h1>Consulter les rendus</h1>
        <h2>Livrables</h2>
        <ul>
            <?php foreach ($livrables as $livrable) { ?>
                <li><?= htmlspecialchars($livrable["titre"]); ?> (Date limite : <?= $livrable["date_limite"]; ?>)</li>
            <?php } ?>
        </ul>
        <h2>Rendus</h2>
        <ul>
            <?php foreach ($rendus as $rendu) { ?>
                <li>
                    <?= htmlspecialchars($rendu["titre_rendu"]); ?> soumis par <?= htmlspecialchars($rendu["etudiant"]); ?>
                    <form action="index.php?module=professeur&action=ajouter_feedback" method="POST">
                        <input type="hidden" name="rendu_id" value="<?= $rendu["id_rendu"]; ?>">
                        Feedback : <textarea name="feedback" required></textarea>
                        <button type="submit">Ajouter</button>
                    </form>
                </li>
            <?php } ?>
        </ul>
        <?php
    }

    public function confirm_creer_livrable() {
        echo "<p>Livrable créé avec succès.</p>";
    }

    public function confirm_ajouter_feedback() {
        echo "<p>Feedback ajouté avec succès.</p>";
    }

    public function erreurBD() {
        echo "<p>Erreur lors de l'exécution en base de données.</p>";
    }
}
