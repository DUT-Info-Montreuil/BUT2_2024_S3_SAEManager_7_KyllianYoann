<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($titre) ? htmlspecialchars($titre) : "Mon Application MVC" ?></title>
    <link rel="stylesheet" href="public/assets/styles.css">
</head>
<body>
    <?php 
    // Condition pour afficher ou masquer le header
    if (!(!isset($_GET['module']) || $_GET['module'] === 'connexion')): ?>
        <header>
           
        </header>
    <?php endif; ?>

    <main>
        <?= $module_html ?? "<p>Aucun contenu disponible.</p>"; ?>
    </main>

    <?php 
    // Condition pour afficher ou masquer le footer
    if (!(!isset($_GET['module']) || $_GET['module'] === 'connexion')): ?>
        <footer>
            <p>&copy; <?= date('Y'); ?> SAE Manager. Tous droits réservés à Kyllian et Yoann.</p>
        </footer>
    <?php endif; ?>
</body>
</html>
