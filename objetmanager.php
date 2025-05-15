<?php
require_once 'objet.php';

class ObjetManager
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    //  Ajouter un objet
    public function ajouterObjet(Objet $objet)
    {
        $stmt = $this->pdo->prepare('INSERT INTO objets (numero_serie, type, marque, image_path, user_id) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute([
            $objet->getNumeroSerie(),
            $objet->getType(),
            $objet->getMarque(),
            $objet->getImagePath(),
            $objet->getUserId()
        ]);

        $objet->setId($this->pdo->lastInsertId());
    }

    //  Supprimer un objet
    public function supprimerObjet($objetId)
    {
        $stmt = $this->pdo->prepare('DELETE FROM objets WHERE id = ?');
        $stmt->execute([$objetId]);
    }

    //  Rechercher par numéro de série
    // Recherche plus souple (si un champ est vide, il est ignoré)
public function rechercherObjet($numeroSerie, $type, $marque) {
    $sql = "SELECT * FROM objets WHERE 1=1";
    $params = [];

    if (!empty($numeroSerie)) {
        $sql .= " AND numero_serie = ?";
        $params[] = $numeroSerie;
    }

    if (!empty($type)) {
        $sql .= " AND type = ?";
        $params[] = $type;
    }

    if (!empty($marque)) {
        $sql .= " AND marque = ?";
        $params[] = $marque;
    }

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute($params);
    $objets = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $objet = new Objet($row['numero_Serie'], $row['type'], $row['marque'], $row['image_path'], $row['user_id']);
        $objet->setId($row['id']);
        $objets[] = $objet;
    }

    return $objets;
}

    


    // ✅ Récupérer tous les objets d’un user
    //public function getObjetsByUser($user_id)
    //{
      //  $stmt = $this->pdo->prepare('SELECT * FROM objets WHERE user_id = ?');
       // $stmt->execute([$user_id]);
        //return $stmt->fetchAll();
    //}
    public function getObjetsByUser($userId) {
        $stmt = $this->pdo->prepare('SELECT * FROM objets WHERE user_id = ?');
        $stmt->execute([$userId]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        $objets = [];
        foreach ($result as $row) {
            $objet = new Objet($row['numero_Serie'], $row['type'], $row['marque'], $row['image_path'], $row['user_id']);
            $objet->setId($row['id']);
            $objets[] = $objet;
        }
        return $objets;
    }
    
    
    public function supprimerObjet1($objetId, $userId) {
        $stmt = $this->pdo->prepare('DELETE FROM objets WHERE id = ? AND user_id = ?');
        $stmt->execute([$objetId, $userId]);
    }
    
}
?>
