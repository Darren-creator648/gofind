<?php
require_once 'logement.php';

class LogementManager
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Publier un logement
    public function ajouterLogement(Logement $logement)
    {
        $stmt = $this->pdo->prepare('INSERT INTO logement (lieu, prix, surface, image_path, user_id) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute([
            $logement->getlieu(),
            (float)$logement->getprix(),
            (float)$logement->getsurface(),
            $logement->getImagePath(),
            (int)$logement->getUserId()
        ]);

        $logement->setId($this->pdo->lastInsertId());
    }

    // Rechercher des logements
    public function rechercherLogement($lieu, $prixMax, $surfaceMin)
    {
        $sql = "SELECT * FROM logement WHERE lieu LIKE ? AND prix <= ? AND surface >= ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(["%$lieu%", (float)$prixMax, (float)$surfaceMin]);

        $logements = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $logement = new Logement($row['lieu'], $row['prix'], $row['surface'], $row['image_path'], $row['user_id']);
            $logement->setId($row['id']);
            $logements[] = $logement;
        }

        return $logements;
    }

    // Supprimer un logement (par son propriétaire)
    public function supprimerLogement($logementId, $userId)
    {
        $stmt = $this->pdo->prepare('DELETE FROM logement WHERE id = ? AND user_id = ?');
        $stmt->execute([(int)$logementId, (int)$userId]);
    }

    // Récupérer tous les logements publiés par un utilisateur
    public function getLogementsByUser($userId)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM logement WHERE user_id = ?');
        $stmt->execute([(int)$userId]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $logements = [];
        foreach ($result as $row) {
            $logement = new Logement($row['lieu'], $row['prix'], $row['surface'], $row['image_path'], $row['user_id']);
            $logement->setId($row['id']);
            $logements[] = $logement;
        }
        return $logements;
    }

    // ✅ Optionnel : récupérer un logement précis par son ID
    public function getLogementById($logementId)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM logement WHERE id = ?');
        $stmt->execute([(int)$logementId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $logement = new Logement($row['lieu'], $row['prix'], $row['surface'], $row['image_path'], $row['user_id']);
            $logement->setId($row['id']);
            return $logement;
        }
        return null;
    }
   
    public function getAllLogements()
    {
        $stmt = $this->pdo->query('SELECT logement.*, users.email FROM logement JOIN users ON logement.user_id = users.id');
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        $logements = [];
        foreach ($result as $row) {
            $logement = new Logement($row['lieu'], $row['prix'], $row['surface'], $row['image_path'], $row['user_id']);
            $logement->setId($row['id']);
            // On ajoute une méthode pour stocker l'email (voir étape 2)
            $logement->setUserEmail($row['email']);
            $logements[] = $logement;
        }
        return $logements;
    }public function rechercherLogement1($lieu, $prixMax, $surfaceMin)
    {
        $sql = "SELECT logement.*, users.email FROM logement 
                JOIN users ON logement.user_id = users.id
                WHERE 1=1";
        
        $params = [];
    
        if (!empty($lieu)) {
            $sql .= " AND lieu LIKE ?";
            $params[] = "%$lieu%";
        }
        if (!empty($prixMax)) {
            $sql .= " AND prix <= ?";
            $params[] = (float)$prixMax;
        }
        if (!empty($surfaceMin)) {
            $sql .= " AND surface >= ?";
            $params[] = (float)$surfaceMin;
        }
    
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
    
        $logements = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $logement = new Logement($row['lieu'], $row['prix'], $row['surface'], $row['image_path'], $row['user_id']);
            $logement->setId($row['id']);
            $logement->setUserEmail($row['email']);
            $logements[] = $logement;
        }
    
        return $logements;
    }
    
    

}
?>
