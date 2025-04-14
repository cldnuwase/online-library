<?php
require 'dbconfig.php';
include 'header.php';
include 'security.php';


if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("Access denied.");
}

// Handle book deletion
if (isset($_GET['delete'])) {
    $bookId = $_GET['delete'];
    if ($book->deleteBook($bookId)) {
        echo "Book deleted successfully. <a href='books.php'>Back</a>";
    } else {
        echo "Failed to delete book.";
    }
    exit();
}


// Handle book update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $available_copies= $_POST['available_copies'];
    $author = $_POST['author'];
    

    if ($book->updateBook($id, $title, $author, $available_copies)) {
        header("Location: books.php");
        exit();
    } else {
        echo "Failed to update book.";
    }
}

// Fetch all books
$allBooks = $book->getAllBooks();

// Get book data for editing (if edit is requested)
$editBook = null;
if (isset($_GET['edit'])) {
    $editBook = $book->getBookById($_GET['edit']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Management</title>
    <link rel="stylesheet" href="css\book_styles.css">
</head>
<body>

<!-- Edit Book Form (Only if editing) -->
<?php if ($editBook) : ?>
    <h2>Edit Book</h2>
    <form method="POST">
        <input type="hidden" name="id" value="<?= htmlspecialchars($editBook['id']) ?>">

        <label>Title:</label>
        <input type="text" name="title" value="<?= htmlspecialchars($editBook['title']) ?>" required><br><br>

        <label>Available_copies:</label>
        <input type="text" name="available_copies" value="<?= htmlspecialchars($editBook['available_copies']) ?>" required><br><br>

        <label>Author:</label>
        <input type="text" name="author" value="<?= htmlspecialchars($editBook['author']) ?>" required><br><br>
        

        <input type="submit" name="update" value="Update Book">
    </form>
<?php endif; ?>

<!-- search book -->
 <?php
$search_query = "";
$books = [];

// Check if the search form has been submitted
if (isset($_GET['search'])) {
    $search_query = trim($_GET['search']);

    // Fetch books that match the search query
    $stmt = $pdo->prepare("SELECT * FROM books WHERE title LIKE ? OR author LIKE ?");
    $stmt->execute(["%$search_query%", "%$search_query%"]);
    $books = $stmt->fetchAll();
}
?>

<!-- Display search form and results -->
<div class="container">
    <form method="GET">
        <input type="text" name="search" value="<?= htmlspecialchars($search_query) ?>" placeholder="Enter book title or author">
        <button type="submit">Search</button>
    </form>

    <br>

    <?php if ($search_query && count($books) > 0): ?>
    <h2>Search Results for "<?= htmlspecialchars($search_query) ?>"</h2>
    <ul>
        <?php foreach ($books as $book): ?>
            <li>
                ID: <?= htmlspecialchars($book['id']) ?> - 
                <?= htmlspecialchars($book['title']) ?> by <?= htmlspecialchars($book['author']) ?>
                
            </li>
        <?php endforeach; ?>
    </ul>
<?php elseif ($search_query): ?>
    <p>No books found for "<?= htmlspecialchars($search_query) ?>".</p>
<?php endif; ?>

</div>

<!-- Display Books -->
<h2>Book List</h2>
<table border="1">
    <tr>
        <th style='background-color:#333'>ID</th>
        <th style='background-color:#333'>Title</th>
        <th style='background-color:#333'>Available Copies</th>
        <th style='background-color:#333'>Author</th>
        <th style='background-color:#333'>Actions</th>
    </tr>
    <?php foreach ($allBooks as $b) : ?>
        <tr>
            <td><?= htmlspecialchars($b['id']) ?></td>
            <td><?= htmlspecialchars($b['title']) ?></td>
            <td><?= htmlspecialchars($b['available_copies']) ?></td>
            <td><?= htmlspecialchars($b['author']) ?></td>
            
            <td>
                <a href="books.php?edit=<?= $b['id'] ?>">Edit</a> | 
                <a href="books.php?delete=<?= $b['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<footer style='background-color: #333;
              color: #fff;
              text-align: center;
              padding: 10px;
              position: fixed;
              width: 120%;
              bottom: 0;'>
    <p>&copy; 2025 Library Management. All Rights Reserved.</p>
</footer>

</body>
</html>
