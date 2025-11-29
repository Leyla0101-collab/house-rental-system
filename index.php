<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Optima View - Find Your Perfect Home</title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('https://static.vecteezy.com/system/resources/thumbnails/022/755/745/small_2x/3d-rendering-of-modern-cozy-house-with-garage-for-sale-or-rent-with-beautiful-landscaping-on-background-real-estate-concept-ai-generated-artwork-photo.jpg'); /* Replace with your image URL */
            background-size: cover;
            background-position: center;
            color: rgb(0, 0, 0);
        }
        .navbar {
            background-color: rgba(0, 0, 0, 0.8);
        }
        .navbar-brand {
            color: rgb(255, 255, 255);
        }
        .navbar-brand:hover {
            color: rgb(200, 200, 200);
        }
        .form-container {
            background: rgba(250, 250, 250, 0.9);
            padding: 20px;
            border-radius: 10px;
            max-width: 600px;
            margin: 50px auto;
        }
        footer {
            background-color: rgba(0, 0, 0, 0.8);
            padding: 10px 0;
            color: #fff;
            text-align: center;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <a class="navbar-brand" href="#">Optima View</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        </ul>
        <ul class="navbar-nav">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="accountDropdown" role="button" data-toggle="dropdown">Account</a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="login.php">Login</a>
                    <a class="dropdown-item" href="register.php">Register</a>
                </div>
            </li>
        </ul>
    </div>
</nav>

<!-- Main Content -->
<div class="form-container">
    <h2 class="text-center text-dark">Find Your Perfect Home</h2>
    <form action="search_result.php" method="GET"> <!-- Action set to send data to search_result.php -->
        <div class="form-group">
            <label for="location">Enter Location</label>
            <input type="text" class="form-control" id="location" name="location" placeholder="Enter city name" required>
        </div>
        <div class="form-group">
            <label for="propertyType">Property Type</label>
            <select class="form-control" id="propertyType" name="propertyType" required>
                <option value="House">House</option>
            </select>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="minBudget">Minimum Budget</label>
                <input type="number" class="form-control" id="minBudget" name="minBudget" placeholder="e.g., RM 100" min="0" required>
            </div>
            <div class="form-group col-md-6">
                <label for="maxBudget">Maximum Budget</label>
                <input type="number" class="form-control" id="maxBudget" name="maxBudget" placeholder="e.g., RM 10,000" min="0" required>
            </div>
        </div>
        <button type="submit" class="btn btn-danger btn-block">Search Property</button>
    </form>
</div>

<!-- Footer -->
<footer>
    <p>&copy; 2025 Optima View | About Us | Contact Us</p>
</footer>

<!-- Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.4.4/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
