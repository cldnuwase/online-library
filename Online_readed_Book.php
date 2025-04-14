<?php
require 'connection.php';
include 'header.php';
include 'security.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    die("Please <a href='login.php'>login</a> first.");
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'] ?? 'user'; // default to 'user' if not set

try {
    // Admins see all; users see only their records
    if ($role === 'admin') {
        $stmt = $pdo->query("
            SELECT bro.id, u.name AS reader_name, b.title, b.author, bro.read_at
            FROM book_read_online bro
            JOIN books b ON bro.book_id = b.id
            JOIN users u ON bro.user_id = u.id
            ORDER BY bro.read_at DESC
        ");
    } else {
        $stmt = $pdo->prepare("
            SELECT bro.id, u.name AS reader_name, b.title, b.author, bro.read_at
            FROM book_read_online bro
            JOIN books b ON bro.book_id = b.id
            JOIN users u ON bro.user_id = u.id
            WHERE bro.user_id = ?
            ORDER BY bro.read_at DESC
        ");
        $stmt->execute([$user_id]);
    }

    $readBooks = $stmt->fetchAll();
} catch (PDOException $e) {
    // If an exception occurs, display an error message
    echo "Error: " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Books Read Online</title>
</head>
<body>
<?php  ?><br><br>
<link rel="stylesheet" href="css\rdd_book_style.css"> 
<br><br><br><br><br><br><br><br>
<h2><?= ($role === 'admin') ? 'All Online Read Book Records' : 'Books You Read Online' ?></h2>
<a href="<?= ($role === 'admin') ? 'admin_dashbord.php' : 'library.php' ?>" style='float: left'>← Back</a>
<br><br>

<?php if (count($readBooks) > 0): ?>
    <table border="1" cellpadding="8" cellspacing="0">
        <tr>
            <th>#</th>
            <th>Reader</th>
            <th>Book Title</th>
            <th>Author</th>
            <th>Read At</th>
        </tr>
        <?php foreach ($readBooks as $index => $book): ?>
            <tr>
                <td><?= $index + 1 ?></td>
                <td><?= htmlspecialchars($book['reader_name']) ?></td>
                <td><?= htmlspecialchars($book['title']) ?></td>
                <td><?= htmlspecialchars($book['author']) ?></td>
                <td><?= $book['read_at'] ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <p>No online reading records found.</p>
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
