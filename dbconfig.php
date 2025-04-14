<?php
require 'connection.php'; // Use existing connection

// Book class to handle CRUD operations
class Book {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    // Create a new book
    public function addBook($title, $author, $filePath, $availableCopies) {
        $stmt = $this->db->prepare("INSERT INTO books (title, author, file_path, available_copies) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$title, $author, $filePath, $availableCopies]);
    }

    // Retrieve all books
    public function getAllBooks() {
        return $this->db->query("SELECT * FROM books")->fetchAll(PDO::FETCH_ASSOC);
    }

    // Retrieve a book by ID
    public function getBookById($id) {
        $stmt = $this->db->prepare("SELECT * FROM books WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Update book details
public function updateBook($id, $title, $author, $available_copies) {
    $stmt = $this->db->prepare("UPDATE books SET title = ?, author = ?, available_copies = ? WHERE id = ?");
    return $stmt->execute([$title, $author, $available_copies, $id]);
}

    // Delete a book
    public function deleteBook($id) {
        $stmt = $this->db->prepare("DELETE FROM books WHERE id = ?");
        return $stmt->execute([$id]);
    }
}

// Initialize Book class using the existing $pdo connection
$book = new Book($pdo);
?>
