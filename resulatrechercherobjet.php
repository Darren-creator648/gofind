<?php
session_start();
require_once 'controlleur.php';
require_once 'objet.php';

// Connexion PDO
$pdo = new PDO('mysql:host=localhost;dbname=gofind', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Initialise le contrôleur
$controller = new ObjetController($pdo);

// Initialiser résultats
$resultats = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numeroSerie = $_POST['numeroSerie'] ?? '';
    $type = $_POST['type'] ?? '';
    $marque = $_POST['marque'] ?? '';

    // Recherche
    $resultats = $controller->rechercherObjet($numeroSerie, $type, $marque);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Résultats recherche</title>
    <link rel="stylesheet" href="rechercherobjet.css">
</head>
<body>
<div class="loader" id="loader"></div>
<script>
  const form = document.querySelector('form');
  const loader = document.getElementById('loader');

  form.addEventListener('submit', () => {
    // Démarre la barre
    loader.style.width = '30%';

    // Simule une progression
    let progress = 30;
    const interval = setInterval(() => {
      progress += 10;
      if (progress <= 90) {
        loader.style.width = progress + '%';
      } else {
        clearInterval(interval);
      }
    }, 200);
  });

  // Quand la page est chargée = barre full
  window.addEventListener('load', () => {
    loader.style.width = '100%';
    setTimeout(() => {
      loader.style.width = '0%'; // cache
    }, 500);
  });

  // Animation des résultats
  const objets = document.querySelectorAll('.objet');
  objets.forEach((el, index) => {
    setTimeout(() => {
      el.style.opacity = 1;
    }, index * 100);
  });
</script>

<header class="header">
    <span class="header-text">Résultats</span>
    <img src="gof.jpg" alt="GoFind" class="logo">
    <span class="header-text">GoFind</span>
</header>

<nav class="nav">
    <a href="declarerobjet.php">Déclarer objet</a>
    <a href="rechercher.php" class="active">Rechercher objet</a>
</nav>

<div class="div">
    <h1>Résultats :</h1>

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

    <br>
    <a href="rechercherobjet.php">🔍 Faire une autre recherche</a>
</div>
<script>
  // Quand la page est chargée
  window.addEventListener('load', () => {
    const objets = document.querySelectorAll('.objet');
    objets.forEach((el, index) => {
      setTimeout(() => {
        el.style.opacity = 1; // Active l’apparition
      }, index * 100); // Petit délai pour un effet "enchaîné"
    });
  });
</script>
