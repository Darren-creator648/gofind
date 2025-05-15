<?php
class Trajet
{
    protected $id;
    protected $depart;
    protected $destination;
    protected $heureDepart;
    protected $jour;
    protected $nbrePlace;
    protected $prix;
    protected $userId;
    protected $userEmail;

    public function __construct($depart, $destination, $heureDepart, $jour, $nbrePlace, $prix, $userId)
    {
        $this->depart = $depart;
        $this->destination = $destination;
        $this->heureDepart = $heureDepart;
        $this->jour = $jour;
        $this->nbrePlace = $nbrePlace;
        $this->prix = $prix;
        $this->userId = $userId;
    }

    // Getters
    public function getId()
    {
        return $this->id;
    }

    public function getDepart()
    {
        return $this->depart;
    }

    public function getDestination()
    {
        return $this->destination;
    }

    public function getJour()
    {
        return $this->jour;
    }

    public function getHeureDepart()
    {
        return $this->heureDepart;
    }

    public function getNbrePlace()
    {
        return $this->nbrePlace;
    }

    public function getPrix()
    {
        return $this->prix;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    // Setters
    public function setId($id)
    {
        $this->id = $id;
    }

    public function setDepart($depart)
    {
        $this->depart = $depart;
    }

    public function setDestination($destination)
    {
        $this->destination= $destination;
    }

    public function setJour($jour)
    {
        $this->jour = $jour;
    }

    public function setHeureDepart($heureDepart)
    {
        $this->heureDepart = $heureDepart;
    }

    public function setNbrePlace($nbrePlace)
    {
        $this->nbrePlace = $nbrePlace;
    }

    public function setPrix($prix)
    {
        $this->prix = $prix;
    }

    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    public function getUserEmail()
    {
        return $this->userEmail;
    }

    public function setUserEmail($email)
    {
        $this->userEmail = $email;
    }
}
?>
