<?php
require_once 'logementmanager.php';

class LogementController
{
    private $manager;

    public function __construct($pdo)
    {
        $this->manager = new LogementManager($pdo);
    }

    // Publier logement
    public function publierLogement($lieu, $prix, $surface, $image_path, $user_id)
    {
        $logement = new Logement($lieu, $prix, $surface, $image_path, $user_id);
        $this->manager->ajouterLogement($logement);
        return $logement;
    }

    // Rechercher
    public function rechercherLogement($lieu, $prixMax, $surfaceMin)
    {
        return $this->manager->rechercherLogement1($lieu, $prixMax, $surfaceMin);
    }
  //  public function rechercherLogement1($lieu, $prixMax, $surfaceMin)
   // {
//return $this->manager->rechercherLogement1($lieu, $prixMax, $surfaceMin);
   // }


    // Supprimer
    public function supprimerLogement($logementId, $userId)
    {
        $this->manager->supprimerLogement($logementId, $userId);
    }

    // Lister par utilisateur
    public function listerLogements($userId)
    {
        return $this->manager->getLogementsByUser($userId);
    }
    public function getAllLogements()
    {
        return $this->manager->getAllLogements();
    }

}
?>
