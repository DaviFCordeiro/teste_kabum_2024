<?php
//Interface para operações de autenticação
class AuthenticationService {
    private $dbConnection;

    public function __construct(DatabaseConnection $dbConnection) {
        $this->dbConnection = $dbConnection;
    }

    public function authenticate($username, $password) {
        try {
            $stmt = $this->dbConnection->getConnection()->prepare("SELECT * FROM auth WHERE username=? AND password=?");
            $stmt->execute([$username, $password]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $stmt->closeCursor();
            return $result ? true : false;
        } catch (PDOException $e) {
            die("Erro na autenticação: " . $e->getMessage());
        }
    }

    public function logout() {
        session_start();
        // Destroy session
        session_destroy();
        // Redirect to login page
        header("Location: index.html");
        exit();
    }
}
?>