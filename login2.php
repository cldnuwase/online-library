<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Our Site</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        /* Basic Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Body styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            line-height: 1.6;
            margin: 0;
        }

        /* Header Styling */
        header {
            background: #333;
            color: #fff;
            text-align: center;
            padding: 20px 0;
        }

        header h1 {
            font-size: 2.5em;
        }

        header p {
            font-size: 1.2em;
            margin-top: 10px;
        }

        /* Main Section Styling */
        .main-content {
            display: flex;
            justify-content: space-around;
            align-items: center;
            margin: 50px;
        }

        .main-content .left {
            width: 45%;
        }

        .main-content .right {
            width: 45%;
            text-align: center;
        }

        .main-content img {
            width: 80%;
            border-radius: 8px;
        }

        /* Paragraph Text Styling */
        .info-text {
            font-size: 1.2em;
            margin-bottom: 20px;
            line-height: 1.6;
        }

        /* Button Styling */
        .login-btn {
            background-color: #333;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 1em;
            margin-top: 20px;
            display: inline-block;
        }

        .login-btn:hover {
            background-color: #555;
        }

        /* Footer Styling */
        footer {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 10px;
            position: fixed;
            width: 100%;
            bottom: 0;
        }
    </style>
</head>
<body>

<!-- Header -->
<header>
    <h1>Welcome to knowledge enrichment</h1>

</header>

<!-- Main Content Section -->
<div class="main-content">
    <!-- Left Section with Paragraph Text -->
    <div class="left">
        <h2>About Us</h2>
        <p class="info-text">We are a team of passionate individuals committed to making knowledge accessible to everyone. Our goal is to provide a comprehensive and user-friendly library system that allows users to explore, borrow, and manage books with ease.</p>
        <p class="info-text">Whether you're searching for academic resources, literary classics, or the latest bestsellers, our platform is designed to enhance your reading experience. Join us today and dive into a world of knowledge, learning, and discovery!</p>
        <a href="login2.php" class="login-btn">Login</a>  |  
        <a href="register_user2.php" class="login-btn">Register</a> |  
        <a href="index.php" class="login-btn">Home</a>
    </div>

    <!-- Right Section with Image -->
    <div class="right">
        <?php include 'login.php';?>
    </div>
</div>

<!-- Footer -->
<footer>
    <p>&copy; 2025 Library Management. All Rights Reserved.</p>
</footer>

</body>
</html>
