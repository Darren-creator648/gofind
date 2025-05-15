<?php
session_start();

require_once 'Utilisateur.php';
require_once 'UserManager.php';
require_once 'controlleuruser.php';

// Connexion à la base de données
$pdo = new PDO('mysql:host=localhost;dbname=gofind', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Initialise le contrôleur
$controller = new Usercontrolleur($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Utilise la méthode login du UserManager
    $user = $controller->login($email, $password);

    if ($user) {
        // Connexion réussie : démarrer une session
        $_SESSION['user_id'] = $user->getId();
        $_SESSION['username'] = $user->getUsername();

        // Rediriger vers le dashboard
        header('Location: dashboard.php');
        exit();
    } else {
        // Erreur : mauvais identifiants
        echo "<script>alert('Email ou mot de passe incorrect'); window.history.back();</script>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Connexion - GoFind</title>
  <link rel="stylesheet" href="stylelogin.css">
</head>
<body>

  <div class="container">

    <h1 class="title">Go<span class="find">Find</span></h1>

    <div class="top-bar">
      <a href="page_accueil.php" class="back">&#8592;</a>
      <span class="top-text">se connecter</span>
    </div>

    <!-- ✅ Ici le form POSTE sur le même fichier -->
    <form class="login-form" method="POST" action="">
      <input type="email" name="email" placeholder="adresse email" required>
      <div class="password-container">
        <input type="password" name="password" id="password" placeholder="mot de passe" required>
        <span class="toggle" onclick="togglePassword()"></span>
      </div>
      <button type="submit" class="btn">Connexion</button>
    </form>

  </div>

  <script src="scriptlogin.js"></script>
</body>
</html>
