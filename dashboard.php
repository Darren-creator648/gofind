<?php
session_start();

// Si l'utilisateur n'est pas connecté, on le redirige vers login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require_once 'UserManager.php';
require_once 'Utilisateur.php';

// Connexion à la base
$pdo = new PDO('mysql:host=localhost;dbname=gofind', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$userManager = new UserManager($pdo);

// On récupère l'utilisateur connecté via son ID
$user = $userManager->getUserById($_SESSION['user_id']);

if (!$user) {
    // L'utilisateur n'existe pas (problème)
    die("Utilisateur non trouvé.");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard - GoFind</title>
  <link rel="stylesheet" href="dashboard.css">
</head>
<body class="dashboard-body">

  <!-- Header -->
  <header class="header">
    
    <img src="gof.jpg" alt="GoFind" class="logo">
    <span class="header-text">GoFind</span>
  </header>
  <span class="header-text">Bienvenue, <?php echo htmlspecialchars($user->getUsername()); ?> 👋</span>
  <!-- Images principales -->
  <div class="main-content">
    <div class="row">
      <img src="objet.jpg" alt="Objets" class="card" onclick="window.location.href='mesobjet.php'">
      <img src="maison.jpg" alt="Maison" class="card" onclick="window.location.href='meslogement.php'">
      <img src="trajet.jpg" alt="Covoiturage" class="card" onclick="window.location.href='mestrajet.php'">
      
    </div>
    <div class="row">
    </div>
  </div>

  <!-- Textes -->
  <div class="texts">
   
    <p class="notif" onclick="if(confirm('Voulez-vous vraiment supprimer votre compte ?')) { window.location.href='supprimer.php'; }">⚙️ Supprimer compte</p>
    <p class="notif" onclick="if(confirm('Se déconnecter ?')) { window.location.href='logout.php'; }">🚪 Déconnexion</p>
  </div>

  <!-- Navigation -->
  <nav class="bottom-nav">
    <a href="#" class="active">🏠 Home</a>
    <a href="router.php?action=goobjet">📦 Objet</a>
    <a href="router.php?action=golocation">🏡 Location</a>
    <a href="router.php?action=gocovoiturage">🚗 Covoiturage</a>
  </nav>

  <script src="dashboard_script.js"></script>
</body>
</html>
