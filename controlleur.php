<?php
require_once 'objet.php';
require_once 'objetManager.php';
//require_once 'logementManager.php';
//require_once 'logement.php';

class ObjetController
{
    private $objetManager;

    public function __construct($pdo)
    {
        $this->objetManager = new ObjetManager($pdo);
    }

    //  Déclarer un nouvel objet
    public function declarerObjet($numero_serie, $type, $marque, $image_path, $user_id)
    {
        $objet = new Objet($numero_serie, $type, $marque, $image_path, $user_id);
        $this->objetManager->ajouterObjet($objet);
    }

    //  Rechercher un objet par numéro de série
    // Rechercher un objet par numero_serie + type + marque
    public function rechercherObjet($numero_serie, $type, $marque)
    {
        return $this->objetManager->rechercherObjet($numero_serie, $type, $marque);
    }


    //  Supprimer un objet déclaré par son ID
    public function supprimerObjet($objetId)
    {
        $this->objetManager->supprimerObjet($objetId);
    }

    //  (Optionnel) Récupérer tous les objets d’un utilisateur
    public function getObjetsUtilisateur($user_id)
    {
        return $this->objetManager->getObjetsByUser($user_id);
    }
    public function listerObjets($user_id) {
        return $this->objetManager->getObjetsByUser($user_id);
    }
    
    public function supprimerObjet1($objetId, $userId, $imagePath) {
        // Supprime l'objet de la base
        $this->objetManager->supprimerObjet($objetId, $userId);
    
        // Supprime aussi l’image si elle existe
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }
    }
    
    
}

?>
