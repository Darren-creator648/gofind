<?php
require_once 'trajet.php';

class TrajetManager
{
    private $pdo;


    public function __construct(PDO $pdo)  
    {
        $this->pdo = $pdo;
    }

    // Publier un trajet
    public function ajouterTrajet(Trajet $trajet): void
    {
        $stmt = $this->pdo->prepare('INSERT INTO trajet (depart, destination, heure_depart, jour, nbre_place, prix, user_id) VALUES (?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute([
            $trajet->getDepart(),
            $trajet->getDestination(),
            $trajet->getHeureDepart(),
            $trajet->getJour(),
            $trajet->getNbrePlace(),
            $trajet->getPrix(),
            $trajet->getUserId()
        ]);

        $trajet->setId((int)$this->pdo->lastInsertId());
    }

    // Rechercher des trajets
    public function rechercherTrajet(string $depart, string $destination, string $jour, float $prixMax): array
    {
        $sql = "SELECT * FROM trajet WHERE depart LIKE ? AND destination LIKE ? AND jour LIKE ? AND prix <= ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(["%$depart%", "%$destination%", "%$jour%", $prixMax]);

        $trajets = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $trajet = new Trajet($row['depart'], $row['destination'], $row['heure_depart'], $row['jour'], (int)$row['nbre_place'], (float)$row['prix'], (int)$row['user_id']);
            $trajet->setId((int)$row['id']);
            $trajets[] = $trajet;
        }

        return $trajets;
    }

    // Supprimer un trajet
    public function supprimerTrajet(int $trajetId, int $userId): void
    {
        $stmt = $this->pdo->prepare('DELETE FROM trajet WHERE id = ? AND user_id = ?');
        $stmt->execute([$trajetId, $userId]);
    }
     // reserver un trajet
     public function reservertrajet(int $trajetId, int $nbre_place, int $userId, $username): void
     {
         // 1. Vérifier s'il reste assez de places
    $stmt = $this->pdo->prepare('SELECT nbre_place FROM trajet WHERE id = ?');
    $stmt->execute([$trajetId]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$result || $result['nbre_place'] < 1) {
        // Pas assez de places ou trajet inexistant
        echo "Pas assez de places ou trajet inexistant";
        return;
    }
         $stmt = $this->pdo->prepare('INSERT INTO  trajet_user (trajet_id,user_id,username) VALUES (?,?,?)');
         $stmt->execute([$trajetId, $userId,$username]);
        // 2. Mettre à jour le nombre de places restantes (décrémenter)
         $stmt = $this->pdo->prepare('UPDATE trajet SET nbre_place = nbre_place - 1 WHERE id = ?;');
         $stmt->execute([ $trajetId]);
     }

    // Récupérer les trajets d’un utilisateur
    public function getTrajetsByUser(int $userId): array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM trajet WHERE user_id = ?');
        $stmt->execute([$userId]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $trajets = [];
        foreach ($result as $row) {
            $trajet = new Trajet($row['depart'], $row['destination'], $row['heure_depart'], $row['jour'], (int)$row['nbre_place'], (float)$row['prix'], (int)$row['user_id']);
            $trajet->setId((int)$row['id']);
            $trajets[] = $trajet;
        }
        return $trajets;
    }

    // Récupérer un trajet par ID
    public function getTrajetById(int $trajetId): ?Trajet
    {
        $stmt = $this->pdo->prepare('SELECT * FROM trajet WHERE id = ?');
        $stmt->execute([$trajetId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $trajet = new Trajet($row['depart'], $row['destination'], $row['heure_depart'], $row['jour'], (int)$row['nbre_place'], (float)$row['prix'], (int)$row['user_id']);
            $trajet->setId((int)$row['id']);
            return $trajet;
        }
        return null;
    }

    // Lister tous les trajets avec l'email des utilisateurs
    public function getAllTrajets(): array
    {
        $stmt = $this->pdo->query('SELECT trajet.*, users.email FROM trajet JOIN users ON trajet.user_id = users.id');
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $trajets = [];
        foreach ($result as $row) {
            $trajet = new Trajet($row['depart'], $row['destination'], $row['heure_depart'], $row['jour'], (int)$row['nbre_place'], (float)$row['prix'], (int)$row['user_id']);
            $trajet->setId((int)$row['id']);
            $trajet->setUserEmail($row['email']); 
            $trajets[] = $trajet;
        }
        return $trajets;
    }

    // Rechercher des trajets avec email utilisateur
    public function rechercherTrajetAvecEmail(string $depart, string $destination, string $jour, float $prixMax): array
    {
        $sql = "SELECT trajet.*, users.email FROM trajet
                JOIN users ON trajet.user_id = users.id
                WHERE nbre_place > 0 AND depart LIKE ? AND destination LIKE ? AND jour LIKE ? AND prix <= ?";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(["%$depart%", "%$destination%", "%$jour%", $prixMax]);

        $trajets = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $trajet = new Trajet($row['depart'], $row['destination'], $row['heure_depart'], $row['jour'], (int)$row['nbre_place'], (float)$row['prix'], (int)$row['user_id']);
            $trajet->setId((int)$row['id']);
            $trajet->setUserEmail($row['email']);
            $trajets[] = $trajet;
        }

        return $trajets;
    }
}
?>
