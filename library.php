<a href="logout.php" style='float: right; font-size: 18px;
            color: #2c3e50;
            text-decoration: none;
            padding: 12px 24px;
            border: 2px solid #2c3e50;
            border-radius: 5px;
            margin: 10px;
            display: inline-block;
            transition: all 0.3s ease;'>Logout</a>

<?php 
require 'connection.php';
include 'user_header.php';
include 'security.php';

if (!isset($_SESSION['user_id'])) {
    die("Please <a href='login.php'>login</a> first.");
}

// Fetch user details from the database
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if ($user) {
    $user_name = htmlspecialchars($user['name']); // Assuming 'name' is the column for the user's name
} else {
    die("User not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        .container {
            width: 100%;
            max-width: 900px;
            margin: 20px auto;
            padding: 40px;
            background-color: #ffffff;
            box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h1 {
            font-size: 32px;
            color: #444;
            text-align: center;
            margin-bottom: 20px;
        }

        h2 {
            font-size: 24px;
            color: #333;
        }

        .links a {
            font-size: 18px;
            color: #2c3e50;
            text-decoration: none;
            padding: 12px 24px;
            border: 2px solid #2c3e50;
            border-radius: 5px;
            margin: 10px;
            display: inline-block;
            transition: all 0.3s ease;
        }

        .links a:hover {
            background-color: #2c3e50;
            color: #fff;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        ul li {
            padding: 10px 0;
            font-size: 18px;
        }

        ul li a {
            color: #3498db;
            text-decoration: none;
        }

        ul li a:hover {
            text-decoration: underline;
        }

    </style>
</head>
<body>
<a href="library.php" style='float:left'>← Back</a>
<div class="container">
<!-- <h1>Welcome back, <?= $user_name ?>!</h1> -->

    <h2>Available Books</h2>
    <ul>
    <?php
    // Fetch available books from the database
    $books = $pdo->query("SELECT * FROM books WHERE available_copies > 1")->fetchAll();
    foreach ($books as $book) {
        echo "<li>{$book['title']} by {$book['author']} 
              | <a href='borrowbook.php?id={$book['id']}'>Borrow</a> 
              | <a href='read_online.php?id={$book['id']}'> Read Online</a></li>";
    }
    ?>
    </ul>

    <div class="links">
        
    </div>
</div>


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
