<?php
require_once 'BaseModel.php';

class UserModel extends BaseModel {
    protected $table = 'users';

    public function __construct() {
        parent::__construct();
    }

    public function findByEmail($email) {
        $query = "SELECT * FROM " . $this->table . " WHERE email = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createUser($data) {
        // Mã hóa mật khẩu
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        return $this->create($data);
    }

    public function verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }

    public function updatePassword($userId, $newPassword) {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $query = "UPDATE " . $this->table . " SET password = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$hashedPassword, $userId]);
    }

    public function getUsersByRole($role) {
        $query = "SELECT * FROM " . $this->table . " WHERE role = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$role]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function saveResetToken($userId, $token, $expires) {
        $query = "UPDATE " . $this->table . " 
                  SET reset_token = ?, reset_token_expires = ? 
                  WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$token, $expires, $userId]);
    }

    public function validateResetToken($token) {
        $query = "SELECT id FROM " . $this->table . " 
                  WHERE reset_token = ? 
                  AND reset_token_expires > NOW()";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$token]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result) {
            // Xóa token sau khi sử dụng
            $this->clearResetToken($result['id']);
            return $result['id'];
        }
        
        return false;
    }

    private function clearResetToken($userId) {
        $query = "UPDATE " . $this->table . " 
                  SET reset_token = NULL, reset_token_expires = NULL 
                  WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$userId]);
    }

    public function countTotalUsers() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }
} 