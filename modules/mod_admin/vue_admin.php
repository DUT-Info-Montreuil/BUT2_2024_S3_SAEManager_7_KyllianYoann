<?php

class VueAdmin extends VueGenerique {
    public function __construct() {
        parent::__construct();
    }

    public function menu() {
        ?>
        <ul>
            <li><a href="index.php?module=admin&action=dashboard">Dashboard</a></li>
            <li><a href="index.php?module=admin&action=form_creer_utilisateur">Créer un utilisateur</a></li>
            <li><a href="index.php?module=admin&action=liste_utilisateurs">Liste des utilisateurs</a></li>
        </ul>
        <?php
    }

    public function dashboard() {
        ?>
        <h1>Dashboard Administrateur</h1>
        <p>Bienvenue dans le panneau d'administration.</p>
        <?php
    }

    public function form_creer_utilisateur() {
        ?>
        <h1>Créer un utilisateur</h1>
        <form action="index.php?module=admin&action=creer_utilisateur" method="POST">
            Nom : <input type="text" name="nom" required><br>
            Prénom : <input type="text" name="prenom" required><br>
            Email : <input type="email" name="email" required><br>
            Mot de passe : <input type="password" name="mot_de_passe" required><br>
            Rôle :
            <select name="role" required>
                <option value="etudiant">Étudiant</option>
                <option value="professeur">Professeur</option>
            </select><br>
            <input type="submit" value="Créer">
        </form>
        <?php
    }

    public function liste_utilisateurs($utilisateurs) {
        ?>
        <h1>Liste des utilisateurs</h1>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Email</th>
                    <th>Rôle</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($utilisateurs as $utilisateur) { ?>
                    <tr>
                        <td><?= htmlspecialchars($utilisateur["id_utilisateur"]); ?></td>
                        <td><?= htmlspecialchars($utilisateur["nom"]); ?></td>
                        <td><?= htmlspecialchars($utilisateur["prenom"]); ?></td>
                        <td><?= htmlspecialchars($utilisateur["email"]); ?></td>
                        <td><?= htmlspecialchars($utilisateur["role"]); ?></td>
                        <td>
                            <a href="index.php?module=admin&action=supprimer_utilisateur&id=<?= $utilisateur["id_utilisateur"]; ?>">Supprimer</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <?php
    }

    public function confirm_creer_utilisateur() {
        echo "<p>Utilisateur créé avec succès.</p>";
    }

    public function confirm_supprimer_utilisateur() {
        echo "<p>Utilisateur supprimé avec succès.</p>";
    }

    public function erreurBD() {
        echo "<p>Erreur lors de l'exécution en base de données.</p>";
    }
}
