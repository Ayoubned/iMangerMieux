<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/login.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand mx-auto" href="index.php?page=dashboard">iMangerMieux</a>
    </div>
</nav>

<div class="container form-container">
    <h2 class="text-center">Login to Your Account</h2>
    <form id="loginForm">
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-login btn-block">Login</button>
    </form>
    <div class="signup-link">
        <p>Don't have an account? <a href="signup.php">Signup here</a>.</p>
    </div>
</div>

<script src="js/login.js"></script>
</body>
</html>
