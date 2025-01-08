<?php
session_start();

// Inclusion des classes nécessaires
require_once "connexion.php";
require_once "site.php";
require_once "composants/menu/controleur_menu.php";
require_once "composants/footer/controleur_footer.php";

// Initialisation de la connexion
Connexion::init_connexion();

// Création et exécution du module
$site = new Site();
$site->exec_module();

// Préparation des variables pour le template
$titre = $site->get_module()->get_title();
$module_html = $site->get_module()->get_affichage();

// Génération du menu
$menuControleur = new ControleurMenu();
ob_start();
$menuControleur->afficher_menu();
$menu_html = ob_get_clean();

// Génération du footer
$footerControleur = new ControleurFooter();
ob_start();
$footerControleur->afficher_footer();
$footer_html = ob_get_clean();

// Inclusion du template
include_once "template.php";
