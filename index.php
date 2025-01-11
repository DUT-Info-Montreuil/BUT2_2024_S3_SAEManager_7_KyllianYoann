<?php
session_start();

// Activer l'affichage des erreurs pour le débogage
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inclusion des classes nécessaires
require_once "connexion.php";
require_once "site.php";
require_once "vue_generique.php";
require_once "module_generique.php";

// Initialisation de la connexion
Connexion::init_connexion();

// Création et exécution du module
$site = new Site();
$site->exec_module();

// Préparation des variables pour le template
$titre = $site->get_module()->get_title();
$module_html = $site->get_module()->get_affichage();

// Inclusion du template
include_once "template.php";
