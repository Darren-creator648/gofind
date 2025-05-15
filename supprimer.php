<?php
session_start();

// Si l'utilisateur n'est pas connecté, on le redirige vers login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require_once 'UserManager.php';
require_once 'Utilisateur.php';
require_once 'controlleuruser.php';

// Connexion à la base
$pdo = new PDO('mysql:host=localhost;dbname=gofind', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


// Initialise le contrôleur
$controller = new Usercontrolleur($pdo);

// On récupère l'utilisateur connecté via son ID
$user = $controller->getUserById($_SESSION['user_id']);

if (!$user) {
    // L'utilisateur n'existe pas (problème)
    die("Utilisateur non trouvé.");
}
    // Récupération de l'ID utilisateur depuis la session
    $userId = $_SESSION['user_id'];

    // ✅ Supprime le compte utilisateur
    $controller->supprimerUtilisateur($userId);

    // ✅ Déconnecte la session
    session_destroy();

    // ✅ Redirige vers l'accueil ou login
    header('Location: page_accueil.php');
    exit()
?>
