<?php
class Objet
{
    protected $id;
    protected $numero_serie;
    protected $type;
    protected $marque;
    protected $image_path;
    protected $user_id;

    public function __construct($numero_serie, $type, $marque, $image_path, $user_id)
    {
        $this->numero_serie = $numero_serie;
        $this->type = $type;
        $this->marque = $marque;
        $this->image_path = $image_path;
        $this->user_id = $user_id;
    }

    // Getters
    public function getId() {
        return $this->id;
    }

    public function getNumeroSerie() {
        return $this->numero_serie;
    }

    public function getType() {
        return $this->type;
    }

    public function getMarque() {
        return $this->marque;
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
    }
}
?>
