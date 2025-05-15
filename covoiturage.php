<?php
session_start();
require_once 'TrajetManager.php';
require_once 'controlleur2.php';
require_once 'Trajet.php';
require_once 'UserManager.php';

$pdo = new PDO('mysql:host=localhost;dbname=gofind', 'root', ''); // adapte ton DSN ici
$trajetManager = new TrajetManager($pdo);
$userManager = new UserManager($pdo);
// Initialise le contrôleur
$controller = new Trajetcontrolleur($pdo);

// Contrôleur : si formulaire soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérifie que l'utilisateur est connecté
    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit();
    }

    $depart = $_POST['Depart'] ?? '';
    $destination = $_POST['Destination'] ?? '';
    $heure = $_POST['heure'] ?? '';
    $jour = $_POST['Date'] ?? '';
    $nbre_place = $_POST['nbre_place'] ?? 0;
    $prix = $_POST['prix'] ?? 0;
    $userId = $_SESSION['user_id'];

    // Crée le trajet
    $trajet = new Trajet($depart, $destination, $heure, $jour, $nbre_place, $prix, $userId);
    $controller->ajouterTrajet($trajet);

    // Redirection avec succès
    header('Location: covoiturage.php?success=1');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Covoiturage</title>
    <link rel="stylesheet" href="rechercherobjet.css">
</head>
<body>
  <!-- Header -->
  <header class="header">
    <span class="header-text">Covoiturage</span>
    <img src="gof.jpg" alt="GoFind" class="logo">
    <span class="header-text">GoFind</span>
  </header>

  <nav class="nav">
    <a href="#" class="active">Publier trajet</a>
    <a href="recherchertrajet.php">Rechercher trajet</a> 
  </nav>

  <div class="div">
  <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
  <div id="successMessage" style="
      background-color: #d4edda;
      color: #155724;
      padding: 10px;
      margin: 10px;
      border: 1px solid #c3e6cb;
      border-radius: 5px;
      text-align: center;
  ">
    ✅ Trajet publié avec succès !
  </div>
  <?php endif; ?>

    <form class="formtrajet" action="" method="POST" enctype="multipart/form-data">
        <input type="text" name="Depart" id="Depart" placeholder="Départ" required>
        <input type="text" name="Destination" id="Destination" placeholder="Destination" required>
        <input type="time" name="heure" id="heure" placeholder="Heure" required>
        <input type="date" name="Date" id="Date" placeholder="Date" required>
        <input type="number" name="nbre_place" id="nbre_place" placeholder="Nombre de places" required min="1">
        <input type="number" name="prix" id="prix" placeholder="Prix" required min="0" step="0.01">
        
        <button type="submit" class="btn">Publier</button>
    </form>
  </div>

  <nav class="bottom-nav">
    <a href="dashboard.php">🏠 Home</a>
    <a href="router.php?action=goobjet">📦 Objet</a>
    <a href="router.php?action=golocation">🏡 Location</a>
    <a href="#" class="active">🚗 Covoiturage</a>
  </nav>

  <script src="objet.js"></script>
</body>
</html>
