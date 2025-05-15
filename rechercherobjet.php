<?php
session_start();
require_once 'controlleur.php'; // contrôleur avec ObjetController
require_once 'objet.php';       // classe Objet

// Connexion PDO
$pdo = new PDO('mysql:host=localhost;dbname=gofind', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Initialise le contrôleur
$controller = new ObjetController($pdo);

// Résultats de recherche
$resultats = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numeroSerie = $_POST['numeroSerie'] ?? '';
    $type = $_POST['type'] ?? '';
    $marque = $_POST['marque'] ?? '';

    //  Recherche via le contrôleur
    $resultats = $controller->rechercherObjet($numeroSerie, $type, $marque);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Objet</title>
    <link rel="stylesheet" href="rechercherobjet.css">
</head>
<body>
  <!-- Header -->
  <header class="header">
    <span class="header-text">Objet</span>
    <img src="gof.jpg" alt="GoFind" class="logo">
    <span class="header-text">GoFind</span>
  </header>

  <nav class="nav">
    <a href="declarerobjet.php">Déclarer objet</a>
    <a href="#" class="active">Rechercher objet</a> 
  </nav>

  <div class="div">

    <form class="formObjet" action="resulatrechercherobjet.php" method="POST">
      <input type="text" name="numeroSerie" id="numeroSerie" placeholder="Numéro de série" required>
      <input type="text" name="type" id="type" placeholder="Type" required>
      <input type="text" name="marque" id="marque" placeholder="Marque" required>
      <button type="submit" class="btn">Rechercher</button>
    </form>

    <!--  Résultats de recherche -->
    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
      <h2>Résultats :</h2>
      <?php if (empty($resultats)): ?>
        <p>Aucun objet trouvé.</p>
      <?php else: ?>
        <?php foreach ($resultats as $objet): ?>
          <div class="objet">
            <img src="./<?php echo htmlspecialchars($objet->getImagePath()); ?>" alt="Image objet" style="max-width: 200px;">
            <h3>Numéro Série: <?php echo htmlspecialchars($objet->getNumeroSerie()); ?></h3>
            <p>Type: <?php echo htmlspecialchars($objet->getType()); ?></p>
            <p>Marque: <?php echo htmlspecialchars($objet->getMarque()); ?></p>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    <?php endif; ?>

  </div>

  <nav class="bottom-nav">
  <a href="dashboard.php">🏠 Home</a>
    <a href="#" class="active">📦 Objet</a>
    <a href="router.php?action=golocation">🏡 Location</a>
    <a href="router.php?action=gocovoiturage">🚗 Covoiturage</a>
  </nav>
  <script src="rechercherobjet.js"></script>
</body>
</html>