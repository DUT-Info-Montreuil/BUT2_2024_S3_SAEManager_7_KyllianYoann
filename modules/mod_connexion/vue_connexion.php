<?php
class VueConnexion extends VueGenerique {
    public function __construct() {
        parent::__construct();
    }

    public function form_connexion() {
        ?>
        <h1>Connexion</h1>
        <form action="index.php?module=connexion&action=verif_connexion" method="POST" class="form-connexion">
            <div>
                <label for="login">Identifiant :</label>
                <input type="text" id="login" name="login" required>
            </div>
            <div>
                <label for="mdp">Mot de passe :</label>
                <input type="password" id="mdp" name="mdp" required>
            </div>
            <button type="submit">Se connecter</button>
        </form>
        <?php
        // Gestion des erreurs
        if (isset($_SESSION['error'])) {
            echo '<p class="error">' . htmlspecialchars($_SESSION['error']) . '</p>';
            unset($_SESSION['error']); // Supprimer l'erreur après affichage
        }
    }

    public function menu() {
        ?>
        <nav>
            <a href="index.php?module=connexion&action=form_connexion">Connexion</a>
        </nav>
        <?php
    }

    public function confirm_connexion($login) {
        ?>
        <p>Connexion réussie ! Bienvenue, <?= htmlspecialchars($login); ?>.</p>
        <?php
    }

    public function echec_connexion($login) {
        ?>
        <p class="error">Échec de la connexion pour l'utilisateur <?= htmlspecialchars($login); ?>.</p>
        <?php
    }

    public function utilisateur_inconnu($login) {
        ?>
        <p class="error">Utilisateur inconnu : <?= htmlspecialchars($login); ?>.</p>
        <?php
    }

    public function confirm_deconnexion() {
        ?>
        <p>Vous avez été déconnecté(e) avec succès.</p>
        <?php
    }
}
