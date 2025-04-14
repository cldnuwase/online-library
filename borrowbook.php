<?php
require 'connection.php';
session_start();

try {
    // Check if the user is logged in
    if (!isset($_SESSION['user_id'])) {
        throw new Exception("<script>alert('Please login first.'); window.location.href = 'login.php';</script>");
    }

    // Ensure a valid book_id is provided
    if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
        throw new Exception("<script>alert('Invalid book ID.'); window.location.href = 'library.php';</script>");
    }

    $book_id = $_GET['id'];
    $user_id = $_SESSION['user_id'];

    // Check if the user has already borrowed this book but has not returned it
    $stmt = $pdo->prepare("SELECT status FROM borrowed_books WHERE user_id = ? AND book_id = ? AND status IN ('pending', 'approved')");
    $stmt->execute([$user_id, $book_id]);
    $borrowedBook = $stmt->fetch();

    if ($borrowedBook) {
        throw new Exception("<script>alert('You have already borrowed this book. Please return it before borrowing again.'); window.location.href = 'library.php';</script>");
    }

    // Fetch book details, now only checking available_copies
    $stmt = $pdo->prepare("SELECT available_copies FROM books WHERE id = ?");
    $stmt->execute([$book_id]);
    $book = $stmt->fetch();

    if ($book && $book['available_copies'] > 1) {
        // Begin transaction
        $pdo->beginTransaction();

        // Insert borrowing record with 'pending' status
        $stmt = $pdo->prepare("INSERT INTO borrowed_books (user_id, book_id, status) VALUES (?, ?, 'pending')");
        if (!$stmt->execute([$user_id, $book_id])) {
            throw new Exception("<script>alert('Failed to record the borrowing.'); window.location.href = 'library.php';</script>");
        }

        // Decrease the available_copies count
        $stmt = $pdo->prepare("UPDATE books SET available_copies = available_copies - 1 WHERE id = ?");
        if (!$stmt->execute([$book_id])) {
            throw new Exception("<script>alert('Error updating the book availability.'); window.location.href = 'library.php';</script>");
        }

        // Commit transaction
        $pdo->commit();

        echo "<script>alert('Your borrow request has been submitted and is pending approval.'); window.location.href = 'library.php';</script>";
        exit();
    } else {
        throw new Exception("<script>alert('Sorry, this book is not available for borrowing.'); window.location.href = 'library.php';</script>");
    }
} catch (Exception $e) {
    // Rollback in case of an error
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    // Log error for debugging
    error_log("Error borrowing book: " . $e->getMessage());

    // Display error message using JavaScript alert
    echo $e->getMessage();
}
?>
