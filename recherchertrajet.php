<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Trajet</title>
  <link rel="stylesheet" href="rechercherobjet.css">
</head>
<body>

  <!-- Header -->
  <header class="header">
    <span class="header-text">Trajet</span>
    <img src="gof.jpg" alt="GoFind" class="logo">
    <span class="header-text">GoFind</span>
  </header>

  <nav class="nav">
    <a href="covoiturage.php">Publier trajet</a>
    <a href="#" class="active">Rechercher trajet</a> 
  </nav>

  <div class="div">

    <form class="formTrajet" action="resultatrecherchertrajet.php" method="POST">
      <input type="text" name="depart" id="depart" placeholder="Départ" required>
      <input type="text" name="destination" id="destination" placeholder="Destination" required>
      <input type="date" name="jour" id="jour" placeholder="Jour">
      <input type="number" step="0.01" name="prix_max" id="prix_max" placeholder="Prix maximum">
      <button type="submit" class="btn">Rechercher</button>
    </form>

  </div>

  <nav class="bottom-nav">
    <a href="dashboard.php">🏠 Home</a>
    <a href="rechercherobjet.php">📦 Objet</a>
    <a href="location.php">🏡 Location</a>
    <a href="#" class="active">🚗 Covoiturage</a>
  </nav>

</body>
</html>
