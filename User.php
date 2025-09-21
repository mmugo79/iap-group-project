<?php
class User {
    private $id;
    private $name;
    private $email;
    private $password;
    private $phone;
    private $role;

    public function __construct($name, $email, $password, $phone, $role = "customer") {
        $this->name = $name;
        $this->email = $email;
        $this->password = password_hash($password, PASSWORD_DEFAULT);
        $this->phone = $phone;
        $this->role = $role;
    }

    public function getId() { return $this->id; }
    public function getName() { return $this->name; }
    public function getEmail() { return $this->email; }
    public function getPhone() { return $this->phone; }
    public function getRole() { return $this->role; }

    public function setPassword($password) {
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }
    public function verifyPassword($password) {
        return password_verify($password, $this->password);
    }

    public function save($pdo) {
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, phone, role) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$this->name, $this->email, $this->password, $this->phone, $this->role]);
    }

    public static function findByEmail($pdo, $email) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($data) {
            $user = new User($data['name'], $data['email'], $data['password'], $data['phone'], $data['role']);
            $user->id = $data['id'];
            $user->password = $data['password'];
            return $user;
        }
        return null;
    }
}
?>
