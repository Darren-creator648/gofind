<?php
session_start();
require_once 'controlleur2.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: pagelogin.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Connexion PDO
$pdo = new PDO('mysql:host=localhost;dbname=gofind', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Initialise le contrôleur
$controller = new Trajetcontrolleur($pdo);

//  Si demande de suppression
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['supprimer_id'])) {
    $trajetId = (int)$_POST['supprimer_id'];
   

    // Supprime via le contrôleur
    $controller->supprimerTrajet($trajetId, $user_id);

    // Recharge
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

//  Récupère les trajets de l'utilisateur via le contrôleur
$trajets = $controller->getTrajetsByUser($user_id);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mes objets</title>
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

    .trajet img { 
        max-width: 100%; 
        border-radius: 12px; 
        margin-bottom: 10px;
    }

    .trajet h3 { 
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
  document.querySelectorAll('.trajet').forEach((el, index) => {
    el.style.animationDelay = (index * 0.2) + 's';
  });
  // Animation cascade pour les cartes
  document.querySelectorAll('.trajet').forEach((el, index) => {
    el.style.animationDelay = (index * 0.2) + 's';
  });

  // Barre de chargement
  function handleDelete(form) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cet objet ?')) {
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

    <a href="dashboard.php">🔙 Retour</a>

    <h1>📦 Mes trajets</h1>
    <div id="loader-bar"></div>

    <?php if (empty($trajets)): ?>
        <p>Aucun trajet trouvé.</p>
    <?php endif; ?>

    <?php foreach ($trajets as $trajet): ?>
        <div class="trajet">
          <h3><?= htmlspecialchars($trajet->getDepart()) ?> ➔ <?= htmlspecialchars($trajet->getDestination()) ?></h3>
          <p>Date: <?= htmlspecialchars($trajet->getJour()) ?> à <?= htmlspecialchars($trajet->getHeureDepart()) ?></p>
          <p>Places dispo: <?= htmlspecialchars($trajet->getNbrePlace()) ?></p>
          <p>Prix: <?= htmlspecialchars($trajet->getPrix()) ?> Fcfa</p>
          

            <!--  Bouton supprimer -->
            <form method="POST" action="" onsubmit="return handleDelete(this);">
                <input type="hidden" name="supprimer_id" value="<?php echo $trajet->getId(); ?>">
                <button type="submit" style="background-color: red; color: white;">Supprimer 🗑️</button>
            </form>
        </div>
    <?php endforeach; ?>

</body>
</html>
