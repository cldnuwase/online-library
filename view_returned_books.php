<?php
require 'connection.php';
include 'header.php';
include 'security.php';

// Check if user is admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("Access denied.");
}

try {
    // Fetch the returned books data
    $stmt = $pdo->query("
        SELECT rb.id, u.name AS user_name, b.title AS book_title, rb.returned_at
        FROM returned_books rb
        JOIN users u ON rb.user_id = u.id
        JOIN books b ON rb.book_id = b.id
        ORDER BY rb.returned_at DESC
    ");

    $returnedBooks = $stmt->fetchAll();
} catch (PDOException $e) {
    // If an exception occurs, display an error message
    echo "Error: " . $e->getMessage();
    exit;
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Returned Books</title>
    <link rel="stylesheet" href="css\return_style.css"> 
</head>
<body>
<br><br>
<a href="admin_dashbord.php" style="float:left">← Back</a>
<br><br>
<br><br><br><br><br><br>
<h2>Returned Books</h2>

<?php if (count($returnedBooks) > 0): ?>
<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th style='background-color:#333'>#</th>
        <th style='background-color:#333'>User Name</th>
        <th style='background-color:#333'>Book Title</th>
        <th style='background-color:#333'>Returned At</th>
    </tr>
    <?php foreach ($returnedBooks as $index => $row): ?>
    <tr>
        <td><?= $index + 1 ?></td>
        <td><?= htmlspecialchars($row['user_name']) ?></td>
        <td><?= htmlspecialchars($row['book_title']) ?></td>
        <td><?= $row['returned_at'] ?></td>
    </tr>
    <?php endforeach; ?>
</table>
<?php else: ?>
<p>No returned books found.</p>
<?php endif; ?>



<footer style='background-color: #333;
              color: #fff;
              text-align: center;
              padding: 10px;
              position: fixed;
              width: 120%;
              bottom: 0;'>
    <p style='color:#fff'>&copy; 2025 Library Management. All Rights Reserved.</p>
</footer>

</body>
</html>
