<?php
require 'connection.php';
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("Access denied.");
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Step 1: Get the book_id from the borrow request
    $stmt = $pdo->prepare("SELECT book_id FROM borrowed_books WHERE id = ?");
    $stmt->execute([$id]);
    $borrow = $stmt->fetch();

    if ($borrow) {
        $bookId = $borrow['book_id'];

        // Step 2: Reject the borrow request
        $updateStatus = $pdo->prepare("UPDATE borrowed_books SET status = 'rejected' WHERE id = ?");
        $updateStatus->execute([$id]);

        // Step 3: Increase the available copies of the book
        $updateBook = $pdo->prepare("UPDATE books SET available_copies = available_copies + 1 WHERE id = ?");
        $updateBook->execute([$bookId]);
    }
}

header("Location: view_borrowed_books.php");
exit();
?>
