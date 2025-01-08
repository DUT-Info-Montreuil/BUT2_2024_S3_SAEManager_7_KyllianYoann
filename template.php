<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($titre) ? htmlspecialchars($titre) : "Mon Application MVC" ?></title>
    <link rel="stylesheet" href="public/assets/styles.css">
</head>
<body>
    <header>
        <?= $menu_html ?? "<p>Menu non disponible.</p>"; ?>
    </header>

    <main>
        <?= $module_html ?? "<p>Aucun contenu disponible.</p>"; ?>
    </main>

    <footer>
        <?= $footer_html ?? "<p>Footer non disponible.</p>"; ?>
    </footer>
</body>
</html>
