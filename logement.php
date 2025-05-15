<?php
class Logement
{
    protected $id;
    protected $lieu;
    protected $prix;
    protected $surface;
    protected $image_path;
    protected $user_id;

    public function __construct($lieu, $prix, $surface, $image_path, $user_id)
    {
        $this->lieu = $lieu;
        $this->prix = $prix;
        $this->surface = $surface;
        $this->image_path = $image_path;
        $this->user_id = $user_id;
    }

    // Getters
    public function getId() {
        return $this->id;
    }

    public function getlieu() {
        return $this->lieu;
    }

    public function getsurface() {
        return $this->surface;
    }

    public function getprix() {
        return $this->prix;
    }

    public function getImagePath() {
        return $this->image_path;
    }

    public function getUserId() {
        return $this->user_id;
    }

    // Setters
    public function setId($id) {
        $this->id = $id;
    }

    public function setNumeroSerie($numero_serie) {
        $this->numero_serie = $numero_serie;
    }

    public function setType($type) {
        $this->type = $type;
    }

    public function setMarque($marque) {
        $this->marque = $marque;
    }

    public function setImagePath($image_path) {
        $this->image_path = $image_path;
    }

    public function setUserId($user_id) {
        $this->user_id = $user_id;
    }private $user_email;

    public function getUserEmail()
    {
        return $this->user_email;
    }
    
    public function setUserEmail($email)
    {
        $this->user_email = $email;
    }
    
}
?>
