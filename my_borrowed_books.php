<a href="logout.php" style='float: right; font-size: 18px;
            color: #333;
            text-decoration: none;
            padding: 12px 24px;
            border: 2px solid #2c3e50;
            border-radius: 5px;
            margin: 10px;
            display: inline-block;
            transition: all 0.3s ease;
            background-color:white'>Logout</a>
<?php
require 'connection.php';
include 'user_header.php';
include 'security.php';

// Make sure the user is logged in
if (!isset($_SESSION['user_id'])) {
    die("Please login first.");
}

$user_id = $_SESSION['user_id'];

// Fetch user details (name)
$stmt = $pdo->prepare("SELECT name FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Fetch all borrowed books for this user
$stmt = $pdo->prepare("
    SELECT b.title, bb.borrowed_at, bb.status, u.name AS borrower_name
    FROM borrowed_books bb
    JOIN books b ON bb.book_id = b.id
    JOIN users u ON bb.user_id = u.id
    WHERE bb.user_id = ?
    ORDER BY bb.borrowed_at DESC
");
$stmt->execute([$user_id]);
$borrowedBooks = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Borrowed Books</title>
    <link rel="stylesheet" type="text/css" href="css\my_bstyle.css">

</head>
<body>

<!--<h1>Welcome, <?= htmlspecialchars($user['name']) ?>!</h1>-->
<h2>My request Books</h2>

<br><br>

<?php if (count($borrowedBooks) > 0): ?><a href="library.php" style='float:left'>← Back</a>
<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th style='background-color:#333'>#</th>
        <th style='background-color:#333'>Book Title</th>
        <th style='background-color:#333'>User name</th>
        <th style='background-color:#333'>Status</th>
        <th style='background-color:#333'>Borrowed At</th>
    </tr>
    <?php foreach ($borrowedBooks as $index => $row): ?>
    <tr>
        <td><?= $index + 1 ?></td>
        <td><?= htmlspecialchars($row['title']) ?></td>
        <td><?= htmlspecialchars($row['borrower_name']) ?></td>
        <td><?= ucfirst($row['status']) ?></td>
        <td><?= $row['borrowed_at'] ?></td>
    </tr>
    <?php endforeach; ?>
</table>
<?php else: ?>
<p>You haven't borrowed any books yet.</p>
<?php endif; ?>

</body>
</html>
