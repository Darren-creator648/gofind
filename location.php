<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🏡 Publier un logement</title>
    <link rel="stylesheet" href="rechercherobjet.css">
</head>
<body>
  <!-- Header -->
  <header class="header">
    <span class="header-text">Location</span>
    <img src="gof.jpg" alt="GoFind" class="logo">
    <span class="header-text">GoFind</span>
  </header>

  <nav class="nav">
    <a href="#" class="active">🏡 Publier logement</a>
    <a href="rechercherlogement.php">🔎 Rechercher logement</a> 
  </nav>

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
      animation: fadeIn 1s ease-out;
  ">
    ✅ Logement publié avec succès !
  </div>
  <?php endif; ?>

    <form class="formObjet" action="submit_logement.php" method="POST" enctype="multipart/form-data">
        <input type="text" name="lieu" id="lieu" placeholder="Lieu (ex: yaounde)" required>
        <input type="number" name="prix" id="prix" placeholder="Prix (Fcfa)" required>
        <input type="number" name="surface" id="surface" placeholder="Surface (m²)" required>
        
        <div class="file">
          <input type="file" name="imagelogement" id="imagelogement" accept="image/*" required onchange="previewImage(event)">
        </div>

        <!-- Aperçu image -->
        <img id="apercuImage" alt="Aperçu du logement" style="max-width: 200px; margin-top: 10px; display: none; border-radius: 8px;">
        
        <button type="submit" class="btn">📤 Publier</button>
    </form>
  </div>

  <nav class="bottom-nav">
    <a href="dashboard.php">🏠 Home</a>
    <a href="router.php?action=goobjet">📦 Objet</a>
    <a href="#" class="active">🏡 Location</a>
    <a href="router.php?action=gocovoiturage">🚗 Covoiturage</a>
    </nav>

  <script>
    // Aperçu image sélectionnée
    function previewImage(event) {
      const apercu = document.getElementById('apercuImage');
      apercu.src = URL.createObjectURL(event.target.files[0]);
      apercu.style.display = 'block';
    }

    // Animation disparition message succès
    setTimeout(() => {
      const msg = document.getElementById('successMessage');
      if (msg) {
        msg.style.transition = "opacity 1s ease-out";
        msg.style.opacity = 0;
        setTimeout(() => msg.remove(), 1000);
      }
    }, 3000);
  </script>

  <!-- Animation CSS -->
  <style>
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(-10px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
</body>
</html>
