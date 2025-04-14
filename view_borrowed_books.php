<?php
require 'connection.php';
include 'header.php';
include 'security.php';

// Check if user is admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("Access denied.");
}

try {
    // Fetch borrowed books data for pending or approved statuses only
    $stmt = $pdo->query("
        SELECT bb.id, u.name AS user_name, b.title AS book_title, bb.borrowed_at, bb.status
        FROM borrowed_books bb
        JOIN users u ON bb.user_id = u.id
        JOIN books b ON bb.book_id = b.id
        WHERE bb.status IN ('pending', 'approved')
        ORDER BY bb.borrowed_at DESC
    ");

    // Fetch online reading requests data
    $readRequestsStmt = $pdo->query("
        SELECT rr.id, u.name AS user_name, b.title AS book_title, rr.request_date, rr.status
        FROM read_requests rr
        JOIN users u ON rr.user_id = u.id
        JOIN books b ON rr.book_id = b.id
        ORDER BY rr.request_date DESC
    ");

    $borrowedBooks = $stmt->fetchAll();
    $readRequests = $readRequestsStmt->fetchAll();
} catch (PDOException $e) {
    // If an exception occurs, display an error message
    echo "Error: " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Borrowed Books</title>
    <link rel="stylesheet" href="css\rdd_book_style.css"> 

</head>
<body>


<br><br>
<?php  ?>
<br><br>
<span align="center">
<br><br><br><br><br><br><br><br>
<h2>Borrowed Books</h2>

<?php if (count($borrowedBooks) > 0): ?>
    <a href="admin_dashbord.php" style='float:left'>← Back</a>
<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th>#</th>
        <th>User Name</th>
        <th>Book Title</th>
        <th>Borrowed At</th>
        <th>Status</th>
        <th>Action</th>
    </tr>
    <?php foreach ($borrowedBooks as $index => $row): ?>
    <tr>
        <td><?= $index + 1 ?></td>
        <td><?= htmlspecialchars($row['user_name']) ?></td>
        <td><?= htmlspecialchars($row['book_title']) ?></td>
        <td><?= $row['borrowed_at'] ?></td>
        <td><?= ucfirst($row['status']) ?></td>
        <td>
            <?php if ($row['status'] === 'pending'): ?>
                <a href="approve_borrow.php?id=<?= $row['id'] ?>">Approve</a> |
                <a href="reject_borrow.php?id=<?= $row['id'] ?>">Reject</a>
            <?php elseif ($row['status'] === 'approved'): ?>
                <a href="return_book.php?id=<?= $row['id'] ?>">Return</a>
            <?php else: ?>
                <?= ucfirst($row['status']) ?>
            <?php endif; ?>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
<?php else: ?>
<p>No borrowed books found.</p>
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

</span>
</body>
</html>
