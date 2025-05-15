<?php
session_start();
require_once 'logement.php';
require_once 'logementmanager.php';

// ✅ Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    die("Erreur : Vous devez être connecté pour publier un logement.");
}

$user_id = $_SESSION['user_id'];

// ✅ Connexion PDO
try {
    $pdo = new PDO('mysql:host=localhost;dbname=gofind', 'root', '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    die("Erreur connexion DB : " . $e->getMessage());
}

// ✅ Si formulaire soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Champs texte
    $lieu = $_POST['lieu'];
    $prix = $_POST['prix'];
    $surface = $_POST['surface'];

    // Vérifier dossier upload
    if (!is_dir('uploads')) {
        mkdir('uploads', 0777, true);
    }

    // ✅ Gestion image
    if (isset($_FILES['imagelogement']) && $_FILES['imagelogement']['error'] === 0) {
        $imageTmpName = $_FILES['imagelogement']['tmp_name'];
        $imageName = $_FILES['imagelogement']['name'];
        $imageSize = $_FILES['imagelogement']['size'];

        if ($imageSize <= 5 * 1024 * 1024) { // max 5Mo
            $ext = pathinfo($imageName, PATHINFO_EXTENSION);
            $uniqueName = uniqid('logement_', true) . '.' . strtolower($ext);
            $imageDestination = 'uploads/' . $uniqueName;

            if (move_uploaded_file($imageTmpName, $imageDestination)) {
                // ✅ Création objet Logement
                $logement = new Logement($lieu, $prix, $surface, $imageDestination, $user_id);

                // ✅ Utilisation du Manager
                $manager = new LogementManager($pdo);
                $manager->ajouterLogement($logement);

                // ✅ Redirection avec message succès
                header('Location: location.php?success=1');
                exit;
            } else {
                die("Erreur : Echec upload de l'image.");
            }
        } else {
            die("Erreur : Image trop grande (max 5Mo).");
        }
    } else {
        die("Erreur : Aucune image sélectionnée ou problème lors de l'upload.");
    }
} else {
    die("Erreur : Méthode non autorisée.");
}
?>
