<?php
require_once "Logger.php"; // Include the Logger class

class Auth {
    private $conn;
    private $table = "users";

    public function __construct($db) {
        $this->conn = $db;
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // Register new user
    public function register($username, $email, $password, $firstName, $lastName) {
        try {
            $checkQuery = "SELECT UserID FROM {$this->table} WHERE Username = :username OR Email = :email";
            $checkStmt = $this->conn->prepare($checkQuery);
            $checkStmt->bindParam(':username', $username);
            $checkStmt->bindParam(':email', $email);
            $checkStmt->execute();

            if ($checkStmt->rowCount() > 0) {
                Logger::warn("Failed registration attempt", ['user' => $username, 'reason' => 'username/email exists']);
                return "Username or email already exists!";
            }

            if (strlen($password) < 8 || strlen($password) > 20) {
                Logger::warn("Failed registration attempt", ['user' => $username, 'reason' => 'invalid password length']);
                return "Password must be between 8 and 20 characters!";
            }

            if (preg_match('/(.)\1/', $password)) {
                Logger::warn("Failed registration attempt", ['user' => $username, 'reason' => 'consecutive duplicate chars']);
                return "Password cannot contain consecutive duplicate characters!";
            }

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $query = "INSERT INTO {$this->table} (Username, Email, Password, FirstName, LastName) 
                      VALUES (:username, :email, :password, :firstName, :lastName)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->bindParam(':firstName', $firstName);
            $stmt->bindParam(':lastName', $lastName);

            if ($stmt->execute()) {
                Logger::info("User registered successfully", ['user' => $username]);
                return true;
            } else {
                Logger::error("Registration failed for unknown reason", ['user' => $username]);
                return "Registration failed!";
            }

        } catch (PDOException $e) {
            Logger::error("Registration error: " . $e->getMessage(), ['user' => $username]);
            return "Error: " . $e->getMessage();
        }
    }

    // Login user
    public function login($username, $password) {
        try {
            $query = "SELECT UserID, Username, Password, FirstName, LastName FROM {$this->table} WHERE Username = :username OR Email = :username";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':username', $username);
            $stmt->execute();

            if ($stmt->rowCount() == 1) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if (password_verify($password, $user['Password'])) {
                    $_SESSION['user_id'] = $user['UserID'];
                    $_SESSION['username'] = $user['Username'];
                    $_SESSION['first_name'] = $user['FirstName'];
                    $_SESSION['last_name'] = $user['LastName'];

                    Logger::info("User logged in successfully", ['user' => $username]);
                    return true;
                } else {
                    Logger::warn("Failed login attempt", ['user' => $username, 'reason' => 'invalid password']);
                    return "Invalid password!";
                }
            } else {
                Logger::warn("Failed login attempt", ['user' => $username, 'reason' => 'user not found']);
                return "User not found!";
            }

        } catch (PDOException $e) {
            Logger::error("Login error: " . $e->getMessage(), ['user' => $username]);
            return "Error: " . $e->getMessage();
        }
    }

    // Logout user
    public function logout() {
        $username = $_SESSION['username'] ?? 'unknown';
        $_SESSION = [];
        session_unset();
        session_destroy();
        Logger::info("User logged out", ['user' => $username]);
        echo "✅ User logged out successfully.\n";
    }

    public function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    public function getUserData() {
        if (!$this->isLoggedIn()) return null;
        return [
            'id' => $_SESSION['user_id'],
            'username' => $_SESSION['username'],
            'first_name' => $_SESSION['first_name'],
            'last_name' => $_SESSION['last_name'] ?? ''
        ];
    }
}
?>
