<?php
require 'connection.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    die("Please <a href='login.php'>login</a> first.");
}

class OnlineReader {
    private $pdo;
    private $userId;

    public function __construct(PDO $pdo, $userId) {
        $this->pdo = $pdo;
        $this->userId = $userId;
    }

    public function readBook($bookId) {
        $book = $this->getBook($bookId);

        if ($book) {
            $this->logReading($bookId);
            $this->redirectToPDF($book['file_path']);
        } else {
            echo "Book not found.";
        }
    }

    private function getBook($bookId) {
        $stmt = $this->pdo->prepare("SELECT file_path FROM books WHERE id = ?");
        $stmt->execute([$bookId]);
        $books = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $books[0] ?? null; // Return first book or null
    }

    private function logReading($bookId) {
        $stmt = $this->pdo->prepare("INSERT INTO book_read_online (user_id, book_id) VALUES (?, ?)");
        $stmt->execute([$this->userId, $bookId]);
    }

    private function redirectToPDF($pdfLink) {
        header("Location: $pdfLink");
        exit;
    }
}

// Process the request if book ID is provided
if (isset($_GET['id'])) {
    $bookId = $_GET['id'];
    $userId = $_SESSION['user_id'];

    $reader = new OnlineReader($pdo, $userId);
    $reader->readBook($bookId);
} else {
    echo "Invalid book request.";
}
?>