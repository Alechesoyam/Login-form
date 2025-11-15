<?php
class User {
    private PDO $pdo;

    public function __construct(PDO $pdo){
        $this->pdo = $pdo;
    }

    public function findByUsername(string $username): ?array {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

     public function findByRememberToken($token) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE remember_token = ?");
        $stmt->execute([$token]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateRememberToken($userId, $token) {
        $stmt = $this->pdo->prepare("UPDATE users SET remember_token = ? WHERE id = ?");
        $stmt->execute([$token, $userId]);
    }

    public function create($username, $password) {
        $hash = password_hash($password, PASSWORD_DEFAULT); // <--- PASSWORD HASHED
        $stmt = $this->pdo->prepare("INSERT INTO users (username,  password) VALUES (?, ?)");
        
        return $stmt->execute([$username, $hash]);
    }
}
