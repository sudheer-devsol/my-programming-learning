<?php
/*
    classes/User.php
    ---------------------------------------------
    Topic 17: OOP - handles everything about a user account:
    registering, logging in, and the "remember me" cookie.
*/

class User {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function findByEmail($email) {
        return $this->db->selectOne("SELECT * FROM users WHERE email = ?", "s", [$email]);
    }

    public function findById($id) {
        return $this->db->selectOne("SELECT * FROM users WHERE id = ?", "i", [$id]);
    }

    public function findByToken($token) {
        return $this->db->selectOne("SELECT * FROM users WHERE remember_token = ?", "s", [$token]);
    }

    public function register($fullName, $email, $password, $phone, $address, $city) {
        // password_hash() is PHP's built-in, beginner-safe way to store passwords
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        return $this->db->run(
            "INSERT INTO users (full_name, email, password, phone, address, city) VALUES (?, ?, ?, ?, ?, ?)",
            "ssssss",
            [$fullName, $email, $hashedPassword, $phone, $address, $city]
        );
    }

    public function verifyPassword($plainPassword, $hashedPassword) {
        return password_verify($plainPassword, $hashedPassword);
    }

    public function saveRememberToken($userId, $token) {
        $this->db->run("UPDATE users SET remember_token = ? WHERE id = ?", "si", [$token, $userId]);
    }

    public function clearRememberToken($userId) {
        $this->db->run("UPDATE users SET remember_token = NULL WHERE id = ?", "i", [$userId]);
    }

    public function updateProfile($userId, $fullName, $phone, $address, $city) {
        return $this->db->run(
            "UPDATE users SET full_name = ?, phone = ?, address = ?, city = ? WHERE id = ?",
            "ssssi",
            [$fullName, $phone, $address, $city, $userId]
        );
    }

    // Logs the user into the session - called after a successful login/register
    public function login($userRow) {
        $_SESSION['user_id']   = $userRow['id'];
        $_SESSION['user_name'] = $userRow['full_name'];
        $_SESSION['role']      = $userRow['role'];
    }
}
?>
