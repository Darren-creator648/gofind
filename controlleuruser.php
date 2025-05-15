<?php
require_once 'Utilisateur.php';
require_once 'usermanager.php';
class UserControlleur
{
    private $manager;

    public function __construct($pdo)
    {
        $this->manager = new UserManager($pdo);
    }

    // ✅ Enregistrer un nouvel utilisateur (avec hash)
    public function enregistrerUtilisateur(Utilisateur $utilisateur)
    {
        $this->manager->enregistrerUtilisateur($utilisateur );
    }

    // ✅ Supprimer un utilisateur par son ID
    public function supprimerUtilisateur($userId)
    {
        $this->manager->supprimerUtilisateur($userId);
        
    }

    // ✅ Récupérer un utilisateur par email
    public function getUserByEmail($email)
    {
        return $this->manager->getUserByEmail($email);
    }

    // ✅ Login sécurisé
    public function login($email, $password)
    {
       return $this->manager->login($email, $password);
    }

    // ✅ Récupérer un utilisateur par ID (utile pour dashboard etc.)
    public function getUserById($id)
    {
        return $this->manager->getUserById($id);
    }
}
?>
