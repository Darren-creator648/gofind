<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: pagelogin.php');
    exit();
}

require_once 'logementmanager.php';
require_once 'controlleur 1.php';
require_once 'logement.php';

// Connexion PDO
$pdo = new PDO('mysql:host=localhost;dbname=gofind', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Initialise le contrôleur
$controller = new LogementController($pdo);

$user_id = $_SESSION['user_id'];

// 🔥 Si demande de suppression
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['supprimer_id'])) {
    $logementId = (int)$_POST['supprimer_id'];
    $imagePath = $_POST['image_path'];

    // Supprime dans la BDD via le contrôleur
    $controller->supprimerLogement($logementId, $user_id);

    // Supprime aussi l'image du serveur
    if (file_exists($imagePath)) {
        unlink($imagePath);
    }

    // Recharge la page après suppression
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// 🔎 Recherche
$lieuRecherche = isset($_GET['lieu']) ? $_GET['lieu'] : '';
$prixRecherche = isset($_GET['prix']) ? $_GET['prix'] : '';

// Par défaut : on liste les logements de l'utilisateur
$logements = $controller->listerLogements($user_id);

// Si recherche effectuée, on filtre via rechercherLogement()
if (!empty($lieuRecherche) || !empty($prixRecherche)) {
    // Par défaut si vide
    $prixMax = !empty($prixRecherche) ? (float)$prixRecherche : PHP_INT_MAX;
    $surfaceMin = 0; // tu peux aussi rajouter un input pour surface si tu veux

    $logements = $controller->rechercherLogement($lieuRecherche, $prixMax, $surfaceMin);

    // ⚠️ Filtrer ceux qui appartiennent à cet utilisateur seulement
    $logements = array_filter($logements, function($logement) use ($user_id) {
        return $logement->getUserId() == $user_id;
    });
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mes Logements</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            padding: 20px;
            background: #00bfff;
            color: #333;
            margin: 0;
        }
        h1 {
            font-size: 32px;
            color: #fff;
            text-align: center;
            margin-bottom: 30px;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.3);
        }
        .logement {
            background: white;
            border: 1px solid #ddd;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            animation: fadeIn 0.5s ease-out;
        }
        .logement img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            transition: transform 0.3s ease;
        }
        .logement img:hover {
            transform: scale(1.05);
        }
        .logement h3 {
            font-size: 22px;
            color: #333;
            margin: 10px 0;
        }
        .logement p {
            font-size: 16px;
            color: #555;
            margin: 5px 0;
        }
        .search-form {
            margin-bottom: 30px;
            display: flex;
            justify-content: center;
        }
        .search-form input {
            padding: 12px;
            margin-right: 10px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            width: 250px;
        }
        .search-form button {
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            background: #39a8e0;
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .search-form button:hover {
            background-color: #2c8bb0;
        }
        .logement form {
            margin-top: 10px;
            display: flex;
            justify-content: flex-start;
        }
        button {
            border-radius: 8px;
            background: #ddd;
            padding: 8px 15px;
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #d9534f;
        }
        a {
            font-size: 18px;
            color: white;
            text-decoration: none;
            margin-bottom: 30px;
            display: block;
            text-align: left;
        }
        @keyframes fadeIn {
            0% { opacity: 0; }
            100% { opacity: 1; }
        }
    </style>
</head>
<body>

    <a href="dashboard.php">🔙 Retour</a>

    <h1>🏡 Mes Logements</h1>

    <!-- 🔍 Formulaire de recherche -->
    <form class="search-form" method="GET" action="">
        <input type="text" name="lieu" placeholder="Rechercher par lieu" value="<?php echo htmlspecialchars($lieuRecherche); ?>">
        <input type="number" name="prix" placeholder="Prix max (€)" value="<?php echo htmlspecialchars($prixRecherche); ?>">
        <button type="submit">Rechercher</button>
    </form>

    <?php if (empty($logements)): ?>
        <p style="text-align: center; font-size: 18px; color: #fff;">Aucun logement trouvé.</p>
    <?php endif; ?>

    <?php foreach ($logements as $logement): ?>
    <div class="logement">
        <img src="<?php echo htmlspecialchars($logement->getImagePath()); ?>" alt="Image logement">
        <h3><?php echo htmlspecialchars($logement->getlieu()); ?></h3>
        <p>Prix : <?php echo htmlspecialchars($logement->getprix()); ?> Fcfa</p>
        <p>Surface : <?php echo htmlspecialchars($logement->getsurface()); ?> m²</p>

        <!-- 🗑️ Bouton supprimer par ID -->
        <form method="POST" action="" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce logement ?');">
            <input type="hidden" name="supprimer_id" value="<?php echo $logement->getId(); ?>">
            <input type="hidden" name="image_path" value="<?php echo htmlspecialchars($logement->getImagePath()); ?>">
            <button type="submit">Supprimer 🗑️</button>
        </form>
    </div>
    <?php endforeach; ?>

</body>
</html>
