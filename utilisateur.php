
    <?php
    class Utilisateur
    {
        protected $id;
        protected $username;
        protected $email;
        protected $password;
        protected $newsletter;
    
        public function __construct($username, $email, $password, $newsletter = 0)
        {
            $this->username = $username;
            $this->email = $email;
            $this->password = $password;
            $this->newsletter = $newsletter;
        }
    
        // Getters
        public function getId() {
            return $this->id;
        }
    
        public function getUsername() {
            return $this->username;
        }
    
        public function getEmail() {
            return $this->email;
        }
    
        public function getPassword() {
            return $this->password;
        }
    
        public function getNewsletter() {
            return $this->newsletter;
        }
    
        // Setters
        public function setId($id) {
            $this->id = $id;
        }
    
        public function setUsername($username) {
            $this->username = $username;
        }
    
        public function setEmail($email) {
            $this->email = $email;
        }
    
        public function setPassword($password) {
            $this->password = $password;
        }
    
        public function setNewsletter($newsletter) {
            $this->newsletter = $newsletter;
        }
    }
    ?>
    