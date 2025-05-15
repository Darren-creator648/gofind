<?php
session_start();

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: pagelogin.php');
    exit();
}

// Inclure le contrôleur et le modèle
require_once 'logementmanager.php';
require_once 'controlleur 1.php';

// Connexion PDO
$pdo = new PDO('mysql:host=localhost;dbname=gofind', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Initialiser le contrôleur
$controller = new LogementController($pdo);

// Récupérer les paramètres de recherche
$lieuRecherche = isset($_GET['lieu']) ? $_GET['lieu'] : '';
$prixRecherche = isset($_GET['prix']) ? $_GET['prix'] : '';
$surfaceMin = isset($_GET['surface_min']) ? $_GET['surface_min'] : '';

// Si aucun champ n’est rempli => afficher tous les logements
if (empty($lieuRecherche) && empty($prixRecherche) && empty($surfaceMin)) {
    // Affiche tout
    $logements = $controller->getAllLogements();
} else {
    // Fait une recherche filtrée
    $logements = $controller->rechercherLogement($lieuRecherche, $prixRecherche, $surfaceMin);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Rechercher un logement</title>
  <style>
    body { font-family: Arial; padding: 20px; background: #00bfff; margin: 0; }
    h1 { color: white; }
    a { color:#00bfff; text-decoration: none; font-weight: bold; }
    a:hover { text-decoration: underline; }

    .logement { 
      background: white; 
      border: 1px solid #ddd; 
      padding: 10px; 
      margin-bottom: 20px; 
      border-radius: 8px; 
      opacity: 0;
      transform: scale(0.95);
      animation: fadeInZoom 0.6s forwards;
    }
    .logement img { max-width: 200px; border-radius: 8px; }
    .logement h3 { margin: 0; color: #333; }

    .search-form { margin-bottom: 20px; }
    .search-form input { padding: 5px; margin-right: 5px; }

    input { 
      padding: 15px; 
      border: none; 
      border-radius: 8px; 
      background: #ddd; 
      font-size: 16px; 
      margin-bottom: 10px;
    }
    button { 
      border-radius: 8px; 
      background: #fff; 
      padding: 10px 20px; 
      font-size: 16px;
      border: 2px solid white;
      cursor: pointer;
      transition: background 0.3s, color 0.3s;
    }
    button:hover { 
      background-color: #39a8e0; 
      color: white; 
    }

    @keyframes fadeInZoom {
      to {
        opacity: 1;
        transform: scale(1);
      }
    }
  </style>
</head>
<body>

  <a href="dashboard.php">🔙 Retour</a>
  <h1>Recherche Logement 🏡</h1>
        
  <!-- Formulaire de recherche -->
  <form method="GET">
    <input type="text" name="lieu" placeholder="Lieu" value="<?php echo isset($_GET['lieu']) ? htmlspecialchars($_GET['lieu']) : ''; ?>">
    <input type="number" name="prix" placeholder="Prix max (€)" value="<?php echo isset($_GET['prix']) ? htmlspecialchars($_GET['prix']) : ''; ?>">
    <input type="number" name="surface_min" placeholder="Surface min (m²)" value="<?php echo isset($_GET['surface_min']) ? htmlspecialchars($_GET['surface_min']) : ''; ?>">
    <button type="submit">Rechercher</button>
  </form>

  <hr>

  <?php if (empty($logements)): ?>
    <p style="color:white;">Aucun logement trouvé.</p>
  <?php else: ?>
    <?php foreach ($logements as $index => $logement): ?>
      <div class="logement" style="animation-delay: <?php echo $index * 0.1; ?>s;">
        <img src="<?php echo htmlspecialchars($logement->getImagePath()); ?>" alt="Image logement">
        <h3><?php echo htmlspecialchars($logement->getlieu()); ?></h3>
        <p>Prix : <?php echo htmlspecialchars($logement->getprix()); ?> €</p>
        <p>Surface : <?php echo htmlspecialchars($logement->getsurface()); ?> m²</p>
        <p><strong>Publié par :</strong> 
          <a href="mailto:<?php echo htmlspecialchars($logement->getUserEmail()); ?>">
            <?php echo htmlspecialchars($logement->getUserEmail()); ?>
          </a>
        </p>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>

</body>
</html>
