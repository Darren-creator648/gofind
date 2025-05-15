<?php
session_start();
require_once 'controlleur.php';

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Connexion à la base
$pdo = new PDO('mysql:host=localhost;dbname=gofind', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Initialise le contrôleur
$controller = new ObjetController($pdo);

// ✅ Si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numeroSerie = $_POST['numeroSerie'];
    $type = $_POST['type'];
    $marque = $_POST['marque'];
    $userId = $_SESSION['user_id'];

    // ✅ Gestion de l'image uploadée
    $imagePath = null;
    if (isset($_FILES['imageObjet']) && $_FILES['imageObjet']['error'] === UPLOAD_ERR_OK) {
        $imageName = uniqid() . '_' . $_FILES['imageObjet']['name'];
        $imagePath = 'uploads/' . $imageName;
        move_uploaded_file($_FILES['imageObjet']['tmp_name'], $imagePath);
    }

    // ✅ Déclare l'objet via le contrôleur
    $controller->declarerObjet($numeroSerie, $type, $marque, $imagePath, $userId);

    // ✅ Redirige avec message succès
    header('Location: declarerobjet.php?success=1');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>objet</title>
    <link rel="stylesheet" href="rechercherobjet.css">
    <style>.formObjet {
    display: flex;
    flex-direction: column;
    gap: 20px;
  }   </style>
</head>
<body>
  <!-- Header -->
  <header class="header">
    <span class="header-text">Objet</span>
    <img src="gof.jpg" alt="GoFind" class="logo">
    <span class="header-text">GoFind</span>
  </header>

  <nav class="nav">
    <a href="#" class="active">déclarer objet</a>
    <a href="rechercherobjet.php">Rechercher objet</a> 
  </nav>
<form class="formObjet" action="" method="POST" enctype="multipart/form-data">
  <input type="text" name="numeroSerie" id="numeroSerie" placeholder="Numéro de série" required>
  <input type="text" name="type" id="type" placeholder="Type" required>
  <input type="text" name="marque" id="marque" placeholder="Marque" required>
  <div class="file">
    <input type="file" name="imageObjet" id="imageObjet" accept="image/*" required onchange="previewImage(event)">
  </div>
  <img id="apercuImage" alt="Aperçu de l'objet" style="max-width: 200px; margin-top: 10px; display: none;">
  <button type="submit" class="btn">Déclarer</button>
</form>
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
    ✅ Objet déclaré avec succès !
  </div>
<?php endif; ?>
<nav class="bottom-nav">
    <a href="dashboard.php">🏠 Home</a>
    <a href="#" class="active">📦 Objet</a>
    <a href="router.php?action=golocation">🏡 Location</a>
    <a href="router.php?action=gocovoiturage">🚗 Covoiturage</a>
  </nav>

  <script src="objet.js"></script>
</body>
</html>