<?php
require 'connection.php';
session_start();

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

// User class to handle user-related operations like login
class User {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Method to verify user credentials during login
    public function login($email, $password) {
        try {
            // Prepare and execute the query to fetch user by email
            $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();
            
            // Verify the password
            if ($user && password_verify($password, $user['password'])) {
                // Set session variables upon successful login
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];
                return $user;
            } else {
                throw new Exception("Invalid email or password.");
            }
        } catch (Exception $e) {
            // Log the exception message for debugging (avoid displaying it to the user)
            error_log($e->getMessage());
            return false;
        }
    }
}

// Initialize the Database and User classes
$db = new Database($pdo); // Using the existing connection
$user = new User($pdo);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Attempt to login the user
    $loggedInUser = $user->login($email, $password);

    if ($loggedInUser) {
        if ($loggedInUser['role'] == 'admin') {
            header("Location: admin_dashbord.php");
        } else {
            header("Location: library.php");
        }
        exit();
    } else {
        echo "An error occurred during login. Please try again later.";
    }
}
?>

<!-- Include external CSS -->


<!-- Login Form -->
<div class="login-container">
    <h2>Login</h2>
    <form method="post" class="login-form">
        <label>Email:</label>
        <input type="email" name="email" required><br>

        <label>Password:</label>
        <input type="password" name="password" required><br>

        <button type="submit" class="login-btn" style='background-color:#333'>Login</button>
    </form>
</div>