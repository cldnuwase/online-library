<?php
require 'connection.php';
include 'user_header.php';
include 'security.php';

// Make sure the user is logged in
if (!isset($_SESSION['user_id'])) {
    die("Please login first.");
}

$search_query = "";
$books = [];

if (isset($_GET['search'])) {
    $search_query = trim($_GET['search']);

    // Fetch books that match the search query
    $stmt = $pdo->prepare("SELECT * FROM books WHERE title LIKE ? OR author LIKE ?");
    $stmt->execute(["%$search_query%", "%$search_query%"]);
    $books = $stmt->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Books</title>
    <link rel="stylesheet" type="text/css" href="css/my_bstyle.css">
</head>
<body><a href="library.php" style='float:left'>← Back</a>
<a href="library.php">view all</a>
<div class="container">
    <h1>Search Books</h1>
    <form method="GET" action="search_books.php">
        <input type="text" name="search" value="<?= htmlspecialchars($search_query) ?>" placeholder="Enter book title or author">
        <button type="submit">Search</button>
    </form>

    <br>

    <?php if ($search_query && count($books) > 0): ?>
        <h2>Search Results for "<?= htmlspecialchars($search_query) ?>"</h2>
        <ul>
            <?php foreach ($books as $book): ?>
                <li>
                    <?= htmlspecialchars($book['title']) ?> by <?= htmlspecialchars($book['author']) ?>
                    | <a href="borrowbook.php?id=<?= $book['id'] ?>">Borrow</a> 
                    | <a href="read_online.php?id=<?= $book['id'] ?>">Read Online</a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php elseif ($search_query): ?>
        <p>No books found for "<?= htmlspecialchars($search_query) ?>".</p>
    <?php endif; ?>
</div>

<footer style='background-color: #333;
              color: #fff;
              text-align: center;
              padding: 10px;
              position: fixed;
              width: 120%;
              bottom: 0;'>
    <p style='color: #fff;'> &copy; 2025 Library Management. All Rights Reserved.</p>
</footer>


</body>
</html>
