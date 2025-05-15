<?php
require_once 'Utilisateur.php';

class UserManager
{
    private $pdo;

    // Constructeur
    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // ✅ Enregistrer un nouvel utilisateur (avec hash)
    public function enregistrerUtilisateur(Utilisateur $utilisateur)
    {
        // Vérifie si l'email est déjà utilisé
        if ($this->getUserByEmail($utilisateur->getEmail())) {
            throw new Exception('Cet email est déjà utilisé.');
        }

        // Hash du mot de passe
        $hashedPassword = password_hash($utilisateur->getPassword(), PASSWORD_BCRYPT);

        // Insertion en base
        $stmt = $this->pdo->prepare('INSERT INTO users (username, email, password, newsletter) VALUES (?, ?, ?, ?)');
        $stmt->execute([
            $utilisateur->getUsername(),
            $utilisateur->getEmail(),
            $hashedPassword,
            $utilisateur->getNewsletter()
        ]);

        // Récupère l'ID auto-incrémenté
        $utilisateur->setId($this->pdo->lastInsertId());
    }

    // ✅ Supprimer un utilisateur par son ID
    public function supprimerUtilisateur($userId)
    {
        $stmt = $this->pdo->prepare('DELETE FROM users WHERE id = ?');
        $stmt->execute([$userId]);
    }

    // ✅ Récupérer un utilisateur par email
    public function getUserByEmail($email)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE email = ?');
        $stmt->execute([$email]);
        $data = $stmt->fetch();

        if ($data) {
            // Création d'un objet Utilisateur avec les données de la BDD
            $user = new Utilisateur(
                $data['username'],
                $data['email'],
                $data['password'],
                $data['newsletter']
            );
            $user->setId($data['id']);
            return $user;
        }

        return null;
    }

    // ✅ Login sécurisé
    public function login($email, $password)
    {
        $user = $this->getUserByEmail($email);

        if ($user && password_verify($password, $user->getPassword())) {
            return $user; // Connexion réussie
        }

        return null; // Échec
    }

    // ✅ Récupérer un utilisateur par ID (utile pour dashboard etc.)
    public function getUserById($id)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE id = ?');
        $stmt->execute([$id]);
        $data = $stmt->fetch();

        if ($data) {
            $user = new Utilisateur(
                $data['username'],
                $data['email'],
                $data['password'],
                $data['newsletter']
            );
            $user->setId($data['id']);
            return $user;
        }

        return null;
    }
}
?>
