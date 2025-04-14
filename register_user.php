<?php
// Include the existing connection file
require 'connection.php';

// Database class to handle the database connection
class Database {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getConnection() {
        return $this->pdo;
    }
}

// User class to handle user-related operations
class User {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Method to check if an email already exists
    private function emailExists($email) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetchColumn() > 0;
    }

    // Method to register a new user
    public function register($name, $email, $password) {
        try {
            if ($this->emailExists($email)) {
                return "Email already exists! Please use a different email.";
            }

            $stmt = $this->pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            $stmt->execute([$name, $email, $password]);
            return "Registration successful! <a href='login.php'>Login</a>";
        } catch (PDOException $e) {
            // Log the error message for debugging
            error_log($e->getMessage());
            return "Registration failed. Please try again later.";
        }
    }
}

// Initialize the Database and User classes
$db = new Database($pdo); // Using the existing connection
$user = new User($pdo);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Register the user
    $message = $user->register($name, $email, $password);
    echo $message;
}
?>

<!-- Registration Form -->

<h2>Register</h2>
<form method="post">
    Name: <input type="text" name="name" required><br>
    Email: <input type="email" name="email" required><br>
    Password: <input type="password" name="password" required><br>
    <button type="submit">Register</button>
</form>
