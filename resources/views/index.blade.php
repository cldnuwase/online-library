<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Online Library System</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f4f4f4;
      color: #333;
    }

    header {
      background-color: white;
      padding: 15px 30px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-bottom: 1px solid #ddd;
    }

    header h1 {
      font-size: 1.5rem;
      color: #222;
    }

    .dropdown {
      position: relative;
      display: inline-block;
    }

    .dropdown-btn {
      text-decoration: none;
      color: #2C5CD9;
      font-weight: bold;
      cursor: pointer;
      padding: 10px;
      display: flex;
      align-items: center;
      gap: 5px;
    }

    .dropdown-btn:after {
      content: '▼';
      font-size: 8px;
      margin-top: 2px;
      transition: transform 0.2s;
    }

    .dropdown-content {
      display: none;
      position: absolute;
      right: 0;
      background-color: #fff;
      min-width: 200px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      z-index: 1;
      border: 1px solid #e5e5e5;
      border-radius: 0;
      margin-top: 10px;
    }

    .dropdown-content:before {
      content: '';
      position: absolute;
      top: -8px;
      right: 20px;
      border-left: 8px solid transparent;
      border-right: 8px solid transparent;
      border-bottom: 8px solid #fff;
      z-index: 2;
    }

    .dropdown-content:after {
      content: '';
      position: absolute;
      top: -9px;
      right: 20px;
      border-left: 8px solid transparent;
      border-right: 8px solid transparent;
      border-bottom: 8px solid #e5e5e5;
      z-index: 1;
    }

    .dropdown-content a {
      color: #2C5CD9;
      padding: 12px 20px;
      text-decoration: none;
      display: block;
      transition: background-color 0.2s;
      font-size: 14px;
      border-bottom: 1px solid #e5e5e5;
    }

    .dropdown-content a:last-child {
      border-bottom: none;
    }

    .dropdown-content a:hover {
      background-color: #f8f8f8;
    }

    .dropdown.active .dropdown-content {
      display: block;
    }

    .dropdown.active .dropdown-btn:after {
      transform: rotate(180deg);
    }

    .hero {
      background-image: url('https://images.unsplash.com/photo-1507842217343-583bb7270b66?ixlib=rb-1.2.1&auto=format&fit=crop&w=1500&q=80');
      background-size: cover;
      background-position: center;
      color: white;
      padding: 100px 30px;
      text-align: center;
    }

    .hero h2 {
      font-size: 2.8rem;
      margin-bottom: 10px;
    }

    .hero p {
      font-size: 1.2rem;
      margin-bottom: 20px;
    }

    .search-box {
      max-width: 600px;
      margin: 0 auto;
      display: flex;
      background: white;
      border-radius: 5px;
      overflow: hidden;
    }

    .search-box input {
      flex: 1;
      padding: 12px;
      border: none;
      font-size: 1rem;
    }

    .search-box button {
      padding: 12px 20px;
      background-color: #003366;
      color: white;
      border: none;
      cursor: pointer;
    }

    .about {
      max-width: 900px;
      margin: 40px auto;
      padding: 0 20px;
      text-align: center;
    }

    .about h2 {
      margin-bottom: 20px;
      font-size: 2rem;
    }

    .about p {
      font-size: 1.1rem;
      line-height: 1.6;
      margin-bottom: 20px;
    }

    footer {
      background-color: #333;
      color: white;
      text-align: center;
      padding: 15px 0;
      margin-top: 40px;
    }

    .alert {
      padding: 15px;
      margin: 20px auto;
      max-width: 600px;
      border-radius: 5px;
      text-align: center;
    }

    .alert-success {
      background-color: #d4edda;
      color: #155724;
      border: 1px solid #c3e6cb;
    }

    .alert-info {
      background-color: #d1ecf1;
      color: #0c5460;
      border: 1px solid #bee5eb;
    }

    .alert-danger {
      background-color: #f8d7da;
      color: #721c24;
      border: 1px solid #f5c6cb;
    }
  </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dropdown = document.querySelector('.dropdown');
            const dropdownBtn = document.querySelector('.dropdown-btn');

            dropdownBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                dropdown.classList.toggle('active');
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!dropdown.contains(e.target)) {
                    dropdown.classList.remove('active');
                }
            });

            // Prevent dropdown from closing when clicking inside
            document.querySelector('.dropdown-content').addEventListener('click', function(e) {
                e.stopPropagation();
            });
        });
    </script>
</head>
<body>

  <!-- Header -->
  <header>
    <h1>Online Library</h1>
    <div class="dropdown">
      <span class="dropdown-btn">Login / Register </span>
      <div class="dropdown-content">
        <a href="{{ route('login') }}">Login as user</a>
        <a href="{{ route('register') }}">Register</a>
        <a href="{{ route('admin.login') }}">Login as administrator</a>
      </div>
    </div>
  </header>

  <!-- Hero Section -->
  <section class="hero">
    <h2>Today's Research, Tomorrow's Innovation</h2>
    <p>Accelerating knowledge discovery through powerful, easy-to-use library access</p>
    
    @if(session('error'))
      <div class="alert alert-danger">
        {{ session('error') }}
      </div>
    @endif

    @if(session('info'))
      <div class="alert alert-info">
        {{ session('info') }}
      </div>
    @endif

    @if(session('book'))
      <div class="alert alert-success">
        <p>We found "{{ session('book')->title }}" in our library!</p>
        <a href="{{ route('borrows.create', ['book' => session('book')->id]) }}" class="btn btn-primary">Borrow this book</a>
      </div>
    @endif

    <form action="{{ route('search') }}" method="GET" class="search-box">
      @csrf
      <input type="text" name="search" placeholder="Search book" required />
      <button type="submit">Search</button>
    </form>
  </section>

  <!-- About Us -->
  <section class="about">
    <h2>About Us</h2>
    <p>Welcome to our Online Library System — a dedicated platform built by a passionate team with a shared mission: to make knowledge easily accessible to all.</p>
    <p>From academic materials and timeless literary classics to contemporary bestsellers, our system is designed to support your reading journey. Explore, borrow, and manage your reading list with ease, and join our community of lifelong learners today.</p>
  </section>

  <!-- Footer -->
  <footer>
    <p>&copy; {{ date('Y') }} Online Library System. All rights reserved.</p>
  </footer>

</body>
</html>
