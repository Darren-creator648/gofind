<?php
require_once 'trajet.php';
require_once 'trajetmanager.php';

class Trajetcontrolleur
{
    private $manager;

    public function __construct($pdo)
    {
        $this->manager = new TrajetManager($pdo);
    }

    // Publier un trajet
    public function ajouterTrajet(Trajet $trajet)
    {
       
        $this->manager->ajouterTrajet($trajet);
        
    }

    // Rechercher des trajets
    public function rechercherTrajet($depart, $destination, $jour, $prixMax)
    {
       return $this->manager->rechercherTrajet($depart, $destination, $jour, $prixMax);
    }

    // Supprimer un trajet
    public function supprimerTrajet($trajetId, $userId)
    {
        $this->manager->supprimerTrajet($trajetId, $userId);
    }
    //reserver un trajet
    public function reservertrajet($trajetId,$nbre_place, $userId,$username)
    {
        $this->manager->reservertrajet($trajetId, $nbre_place,$userId,$username);
    }

    // Récupérer les trajets d’un utilisateur
    public function getTrajetsByUser($userId)
    {
        return $this->manager->getTrajetsByUser( $userId);
    }

    // Récupérer un trajet par ID
    public function getTrajetById($trajetId)
    {
        return $this->manager->getTrajetById( $trajetId);
    }

    // Lister tous les trajets avec l'email des utilisateurs
    public function getAllTrajets()
    {
        return $this->manager->getAllTrajets( );
    }

    // Rechercher des trajets avec email utilisateur
    public function rechercherTrajetAvecEmail($depart, $destination, $jour, $prixMax)
    {
        return $this->manager->rechercherTrajetAvecEmail($depart, $destination, $jour, $prixMax );
    }
}
?>
