<?php
require 'connection.php';
session_start();

// Check if user is admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("Access denied.");
}

try {
    // Get the borrowed book ID from the query string
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $borrowed_book_id = $_GET['id'];

        // Begin transaction to ensure data consistency
        $pdo->beginTransaction();

        // Fetch the borrowed book details to get the book_id
        $stmt = $pdo->prepare("SELECT book_id, user_id FROM borrowed_books WHERE id = ?");
        $stmt->execute([$borrowed_book_id]);
        $borrowed_book = $stmt->fetch();

        // Check if the book record exists
        if ($borrowed_book) {
            // Get the book_id
            $book_id = $borrowed_book['book_id'];

            // Update the book's available copies by incrementing it by 1
            $stmt = $pdo->prepare("UPDATE books SET available_copies = available_copies + 1 WHERE id = ?");
            $stmt->execute([$book_id]);

            // Insert the return record into the returned_books table
            $stmt = $pdo->prepare("INSERT INTO returned_books (user_id, book_id, returned_at) VALUES (?, ?, NOW())");
            $stmt->execute([$borrowed_book['user_id'], $book_id]);

            // Update the status of the borrowed book to 'returned'
            $stmt = $pdo->prepare("UPDATE borrowed_books SET status = 'returned' WHERE id = ?");
            $stmt->execute([$borrowed_book_id]);

            // Commit the transaction
            $pdo->commit();

            header("Location: view_borrowed_books.php");

            echo "The book has been successfully returned.";
        } else {
            throw new Exception("The borrowed book record does not exist.");
        }
    } else {
        throw new InvalidArgumentException("Invalid request: Missing or incorrect book ID.");
    }
} catch (PDOException $e) {
    // Rollback in case of error
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    // Log the error for debugging
    error_log("Database error: " . $e->getMessage());

    // Display a user-friendly error message
    echo "<p style='color: red;'>Error: There was a problem processing your request. Please try again later.</p>";
} catch (Exception $e) {
    // Handle other general exceptions
    // Rollback in case of error
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    // Log the general error for debugging
    error_log("Error: " . $e->getMessage());

    // Display a user-friendly error message
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
} catch (InvalidArgumentException $e) {
    // Handle invalid argument exception
    // Rollback in case of error
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    // Log the error for debugging
    error_log("Invalid Argument error: " . $e->getMessage());

    // Display a user-friendly error message
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}
?>
