<?php
require_once 'accueil_home.php';

if (isset($_GET['action'])) {
    $action = $_GET['action'];
    $accueil = new Accueil();

    switch ($action) {
        case 'goobjet':
            $accueil->afficherfiltre();
            break;
        case 'golocation':
            $accueil->afficherfiltre1();
            break;
        case 'gocovoiturage':
            $accueil->afficherfiltre2();
            break;
        default:
            echo "Page inconnue.";
    }
} else {
    echo "Aucune action spécifiée.";
}
?>
