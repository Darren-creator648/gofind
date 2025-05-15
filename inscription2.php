<?php
session_start();

require_once 'Utilisateur.php';
require_once 'UserManager.php';
require_once 'controlleuruser.php';

// Connexion à la base de données (ce sera mieux plus tard dans une classe DB séparée)
$pdo = new PDO('mysql:host=localhost;dbname=gofind', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Initialise le contrôleur
$controller = new Usercontrolleur($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // On crée un objet Utilisateur avec les données du POST
        $utilisateur = new Utilisateur(
            $_POST['username'],
            $_POST['email'],
            $_POST['password'],
            isset($_POST['newsletter']) ? 1 : 0
        );

        // Enregistre dans la BD via UserManager
        $controller->enregistrerUtilisateur($utilisateur);

        // Stocke l'id dans la session
        $_SESSION['user_id'] = $utilisateur->getId();

        // Redirection vers le dashboard
        header('Location: dashboard.php');
        exit();

    } catch (Exception $e) {
        // Affiche l'erreur (ex : email déjà utilisé)
        echo "<script>alert('" . $e->getMessage() . "'); window.history.back();</script>";
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inscription - GoFind</title>
  <link rel="stylesheet" href="inscription.css">
</head>
<body>

  <div class="container">

    <h1 class="title">Go<span class="find">Find</span></h1>

    <div class="top-bar">
      <a href="page_accueil.php" class="back">&#8592;</a>
      <span class="top-text">S'inscrire</span>
    </div>

    <form class="login-form" method="POST" action="inscription2.php">
      <input type="text" name="username" id="username" placeholder="Nom utilisateur" required>
      <input type="email" name="email" id="email" placeholder="Addresse email" required>
      <div class="password-container">
       <input type="password" name="password" id="password" placeholder="Password" required>
      <span class="toggle" onclick="togglePassword()"></span>
      </div>

<div class="checkbox-container">
  <input type="checkbox" name="newsletter" id="newsletter">
  <label for="newsletter">je souhaite recevoir les emails des offres et des dernières mise à jour</label>
</div>

<button type="submit" class="btn">S'inscrire</button>

    </form>

  </div>

  <script src="inscription.js"></script>
</body>
</html>

