<?php
session_start();
require_once 'TrajetManager.php';
require_once 'Trajet.php';
require_once 'controlleur2.php';
require_once 'UserManager.php';
require_once 'Utilisateur.php';

// Connexion PDO
$pdo = new PDO('mysql:host=localhost;dbname=gofind', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


// Initialise le contrôleur
$controller = new Trajetcontrolleur($pdo);
$trajetsTrouves = [];
$userManager = new UserManager($pdo);

// On récupère l'utilisateur connecté via son ID
$user = $userManager->getUserById($_SESSION['user_id']);

if (!$user) {
    // L'utilisateur n'existe pas (problème)
    die("Utilisateur non trouvé.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $depart = $_POST['depart'] ?? '';
    $destination = $_POST['destination'] ?? '';
    $jour = $_POST['jour'] ?? '';
    $prixMax = !empty($_POST['prix_max']) ? (float)$_POST['prix_max'] : 1000000;

    $trajetsTrouves = $controller->rechercherTrajetAvecEmail($depart, $destination, $jour, $prixMax);
}
//  Si demande de reservation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reserver_id'])) {
  $trajetId = (int)$_POST['reserver_id'];
  $nbre_place = (int)$_POST['nbre_place'];
  $user_id = $_SESSION['user_id'];
  $username = $_POST['username'];
  // reserver via le contrôleur
  $controller->reservertrajet($trajetId,$nbre_place,$user_id,$username);

  // Recharge
  header("Location: recherchertrajet.php");
  exit();
}
  


?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Résultats Trajet</title>
  
</head>
<body>
<style>
        body { 
        font-family: Arial, sans-serif; 
        padding: 20px; 
        background: #00bfff; 
        margin: 0;
    }

    h1 {
        color: white;
        text-align: center;
    }

    a {
        color: white;
        text-decoration: none;
        font-weight: bold;
    }

    a:hover {
        text-decoration: underline;
    }

    .trajet {
        background: white;
        border: 1px solid #ddd;
        padding: 15px;
        margin: 20px auto;
        border-radius: 16px;
        max-width: 400px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        opacity: 0;
        transform: translateY(20px);
        animation: fadeInUp 0.6s forwards;
    }

    .trajet  h3 { 
        margin: 0 0 10px 0; 
        color: #333;
    }

     .trajet p {
        margin: 5px 0;
        color: #555;
    }

    button {
        border-radius: 8px;
        background: #ddd;
        padding: 8px 16px;
        border: none;
        cursor: pointer;
        font-weight: bold;
        transition: background 0.3s, transform 0.2s;
    }

    button:hover {
        background-color: #39a8e0;
        transform: scale(1.05);
    }

    button:active {
        transform: scale(0.95);
    }

    /* Animation */
    @keyframes fadeInUp {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Barre de chargement */
    #loader-bar {
    position: fixed;
    top: 0;
    left: 0;
    width: 0%;
    height: 4px;
    background: white;
    z-index: 9999;
    transition: width 0.4s ease;
    }


    </style>
</head>
<body>
<script>
  // Animation en cascade
  document.querySelectorAll('.trajet ').forEach((el, index) => {
    el.style.animationDelay = (index * 0.2) + 's';
  });
  // Animation cascade pour les cartes
  document.querySelectorAll('.trajet ').forEach((el, index) => {
    el.style.animationDelay = (index * 0.2) + 's';
  });

  // Barre de chargement
  function handleDelete(form) {
    if (confirm('Êtes-vous sûr de vouloir reserver ce trajet  ?')) {
      startLoader();
      return true;
    }
    return false;
  }

  function startLoader() {
    let loader = document.getElementById('loader-bar');
    loader.style.width = '0%';
    setTimeout(() => loader.style.width = '100%', 50);
  }
</script>
<div>

<h2>Résultats :</h2>
<?php if (empty($trajetsTrouves)): ?>
  <p>Aucun trajet trouvé.</p>
<?php else: ?>
  <?php foreach ($trajetsTrouves as $trajet): ?>
    <div class="trajet">
      <h3><?= htmlspecialchars($trajet->getDepart()) ?> ➔ <?= htmlspecialchars($trajet->getDestination()) ?></h3>
      <p>Date: <?= htmlspecialchars($trajet->getJour()) ?> à <?= htmlspecialchars($trajet->getHeureDepart()) ?></p>
      <p>Places dispo: <?= htmlspecialchars($trajet->getNbrePlace()) ?></p>
      <p>Prix: <?= htmlspecialchars($trajet->getPrix()) ?> Fcfa</p>
      <p>Contact conducteur: <?= htmlspecialchars($trajet->getUserEmail()) ?></p>
      <!--  Bouton Reserver -->
      <form method="POST" action=""onsubmit="return handleDelete(this);">
            <input type="hidden" name="reserver_id" value="<?php echo $trajet->getId(); ?>">
            <input type="hidden" name="nbre_place" value="<?php echo htmlspecialchars($trajet->getNbrePlace()); ?>">
            <input type="hidden" name="username" value="<?php echo htmlspecialchars($user->getUsername()); ?>">
            <button type="submit" style="background-color: red; color: white;">Reserver</button>
        </form>
      
    </div>
  <?php endforeach; ?>
<?php endif; ?>


<a href="recherchertrajet.php">🔙 Retour</a>


<div id="loader-bar"></div>





</div>

  <!-- Header 
  <header class="header">
    <span class="header-text">Résultats Trajet</span>
    <img src="gof.jpg" alt="GoFind" class="logo">
    <span class="header-text">GoFind</span>
  </header>

  <nav class="nav">
    <a href="covoiturage.php">Publier trajet</a>
    <a href="recherchertrajet.php" class="active">Rechercher trajet</a> 
  </nav>

  <div>

    <h2>Résultats :</h2>
    <?php if (empty($trajetsTrouves)): ?>
      <p>Aucun trajet trouvé.</p>
    <?php else: ?>
      <?php foreach ($trajetsTrouves as $trajet): ?>
        <div class="trajet">
          <h3><?= htmlspecialchars($trajet->getDepart()) ?> ➔ <?= htmlspecialchars($trajet->getDestination()) ?></h3>
          <p>Date: <?= htmlspecialchars($trajet->getJour()) ?> à <?= htmlspecialchars($trajet->getHeureDepart()) ?></p>
          <p>Places dispo: <?= htmlspecialchars($trajet->getNbrePlace()) ?></p>
          <p>Prix: <?= htmlspecialchars($trajet->getPrix()) ?> Fcfa</p>
          <p>Contact conducteur: <?= htmlspecialchars($trajet->getUserEmail()) ?></p>
          <button class="btn">Reserver</button>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>

  </div>

  <nav class="bottom-nav">
    <a href="dashboard.php">🏠 Home</a>
    <a href="rechercherobjet.php">📦 Objet</a>
    <a href="location.php">🏡 Location</a>
    <a href="recherchertrajet.php" class="active">🚗 Covoiturage</a>
  </nav>-->

</body>
</html>
