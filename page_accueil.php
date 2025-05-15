<?php
// Connexion à la base MySQL
$pdo = new PDO('mysql:host=localhost;dbname=gofind', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

session_start();

?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>GoFind - Accueil</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="container">
    <img src="gof.jpg" alt="Logo GoFind" class="logo">
    <h1 class="title">Go<span class="find">Find</span></h1>

    <div class="buttons">
    <a href="login.php" class="btn" method="get">Connexion</a>
    <a href="inscription2.php" class="btn" method="get">Créer un compte</a>
    </div>
  </div>
</body>
</html>