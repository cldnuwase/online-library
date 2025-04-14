<?php
require 'connection.php';
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("Access denied.");
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Get the book ID
    $stmt = $pdo->prepare("SELECT book_id FROM borrowed_books WHERE id = ?");
    $stmt->execute([$id]);
    $borrow = $stmt->fetch();

    if ($borrow) {
        $bookId = $borrow['book_id'];

        // Approve the borrow request
        $updateStatus = $pdo->prepare("UPDATE borrowed_books SET status = 'approved' WHERE id = ?");
        $updateStatus->execute([$id]);

        // Decrease book availability
        
    }
}

header("Location: view_borrowed_books.php");
exit();
?>
