<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        /* Basic Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* General Body Styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            padding-top: 80px; /* To prevent content from hiding behind fixed fieldset */
        }

        /* Fixed Full-Width Fieldset */
        fieldset {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            padding: 20px 0;
            background-color: #333;
            border: none;
            z-index: 1000; /* Ensures it stays on top */
        }

        /* Header */
        h1 {
            color: white;
            text-align: center;
            font-size: 36px;
            margin-bottom: 10px;
        }

        /* Admin Links Container */
        .admin-links {
            text-align: center;
            font-size: 18px;
        }

        /* Admin Links Style */
        .admin-links a {
            padding: 12px 25px;
            margin: 0 10px;
            background-color: #444;
            color: #fff;
            border-radius: 50px;
            font-size: 16px;
            text-decoration: none;
            transition: background-color 0.3s ease-in-out, transform 0.3s ease-in-out;
        }

        .admin-links .logout {
            background-color: #e74c3c;
            float: right;
            margin-right: 20px;
        }

        .admin-links .logout:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>

<fieldset style='margin-bottom: 10em'>
    <h1 style='color:white'>Library Management</h1><br><br>
    <div class="admin-links">
    | <a href="admin_dashbord.php">Home</a>
        <a href="books.php">Add New Book</a>  
        | <a href="view_borrowed_books.php">View Borrowed Books</a>
        | <a href="Online_readed_Book.php">Online Readed Book</a>
       
        | <a href="view_returned_books.php">View Returned Books</a> 
        <!--| <a href="report.php">Report</a> -->
        | <a href="logout.php" class="logout">Logout</a>
    </div>
</fieldset>
</body>
</html>
