<?php
class VueConnexion extends VueGenerique {
    public function __construct() {
        parent::__construct();
    }

    public function form_connexion() {
        ?>
        <!DOCTYPE html>
        <html lang="fr">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Page de Connexion</title>
            <style>
                /* Style général */
                body {
                    font-family: Arial, sans-serif;
                    margin: 0;
                    padding: 0;
                    background: linear-gradient(135deg, #4c8bf5, #6a11cb);
                    color: #333;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    height: 100vh;
                }
    
                /* Conteneur principal */
                .login-container {
                    background: #fff;
                    padding: 30px 40px;
                    border-radius: 8px;
                    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
                    width: 100%;
                    max-width: 400px;
                    text-align: center;
                }
    
                .input-group {
                    margin-bottom: 15px;
                    text-align: left;
                }
    
                .input-group label {
                    font-size: 14px;
                    display: block;
                    margin-bottom: 5px;
                    color: #555;
                }
    
                .input-group input {
                    width: 100%;
                    padding: 10px;
                    border: 1px solid #ddd;
                    border-radius: 5px;
                    font-size: 14px;
                    color: #333;
                }
    
                .btn {
                    width: 100%;
                    padding: 10px;
                    background: #6a11cb;
                    color: #fff;
                    border: none;
                    border-radius: 5px;
                    font-size: 16px;
                    cursor: pointer;
                    transition: background 0.3s ease;
                }
    
                .btn:hover {
                    background: #4c8bf5;
                }
    
                .footer-links {
                    margin-top: 15px;
                }
    
                .footer-links a {
                    color: #6a11cb;
                    text-decoration: none;
                    font-size: 14px;
                    margin: 0 5px;
                }
    
                .footer-links a:hover {
                    text-decoration: underline;
                }
    
                .error {
                    color: #ff4d4d;
                    font-size: 14px;
                    margin-top: 10px;
                }
            </style>
        </head>
        <body>
            <div class="login-container">
                <div class="login-form">
                    <h1>Connexion SAE Manager</h1>
                    <form action="index.php?module=connexion&action=verif_connexion" method="POST">
                        <div class="input-group">
                            <label for="login">Identifiant</label>
                            <input type="text" id="login" name="login" placeholder="Votre identifiant" required>
                        </div>
                        <div class="input-group">
                            <label for="mdp">Mot de Passe</label>
                            <input type="password" id="mdp" name="mdp" placeholder="Votre mot de passe" required>
                        </div>
                        <button type="submit" class="btn">Se connecter</button>
                    </form>
                    <?php
                    // Gestion des erreurs
                    if (isset($_SESSION['error'])) {
                        echo '<p class="error">' . htmlspecialchars($_SESSION['error']) . '</p>';
                        unset($_SESSION['error']); // Supprime l'erreur après affichage
                    }
                    ?>
                </div>
            </div>
        </body>
        </html>
        <?php
    }    
    public function confirm_connexion($login) {
        echo "<p>Connexion réussie ! Bienvenue, " . htmlspecialchars($login) . ".</p>";
        header("Refresh: 2; url=index.php?module={$_SESSION['role']}&action=dashboard");
        exit;
    }
    

    public function echec_connexion($login) {
        // Affichage d'un message d'erreur
        echo "<p class='error'>Échec de la connexion pour l'utilisateur " . htmlspecialchars($login) . ".</p>";
    }

    public function confirm_deconnexion() {
        echo "<p>Vous avez été déconnecté(e) avec succès.</p>";
        header("Refresh: 2; url=http://saemanager7.infy.uk/");
        exit();
    }
}

