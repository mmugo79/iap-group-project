<?php
require_once "User.php";

class UserService {

    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Register a new user
     */
    public function register($name, $email, $password, $phone, $role = "customer") {
        // Check if email already exists
        if (User::findByEmail($this->pdo, $email)) {
            return ["success" => false, "message" => "Email already registered"];
        }

        $user = new User($name, $email, $password, $phone, $role);
        if ($user->save($this->pdo)) {
            return ["success" => true, "message" => "User registered successfully"];
        }
        return ["success" => false, "message" => "Registration failed"];
    }

    /**
     * Login user with email + password
     */
    public function login($email, $password) {
        $user = User::findByEmail($this->pdo, $email);
        if ($user && $user->verifyPassword($password)) {
            return ["success" => true, "user" => $user];
        }
        return ["success" => false, "message" => "Invalid email or password"];
    }

    /**
     * Get all users
     */
    public function getAllUsers() {
        $stmt = $this->pdo->query("SELECT id, name, email, phone, role, created_at FROM users");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Delete user by ID
     */
    public function deleteUser($id) {
        $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>
