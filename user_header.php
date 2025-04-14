<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Dashboard</title>
    <style>
        body {
            font-family: 'Verdana', sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
            max-width: 500px;
            margin: 50px auto;
            padding: 40px;
            background-color: #ffffff;
            box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            text-align: center;
        }

        h1 {
            font-size: 32px;
            color: #444;
            margin-bottom: 20px;
        }

        .links a {
            font-size: 18px;
            color: #2c3e50;
            background-color: white;
            text-decoration: none;
            padding: 12px 24px;
            border: 2px solid #2c3e50;
            border-radius: 5px;
            margin: 10px;
            display: inline-block;
            transition: all 0.3s ease;
        }

        .links a:hover {
            background-color:rgb(62, 90, 118);
            color: #fff;
        }

        .action-buttons {
            margin-top: 30px;
        }

        .action-buttons a {
            padding: 10px 20px;
            background-color: #3498db;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            margin: 10px;
            display: inline-block;
            transition: background-color 0.3s ease;
        }

        .action-buttons a:hover {
            background-color: #2980b9;
        }

    </style>
</head>
<body>

<div class="container">
    <h1>Welcome to Your Library Dashboard!</h1>

    <div class="links">
        <a href="my_borrowed_books.php">My Borrowed Books</a>
    </div>

    <div class="action-buttons">
        <a href="search_books.php">Search Books</a>
    </div>
</div>

</body>
</html>
