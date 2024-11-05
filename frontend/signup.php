<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/signup.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand mx-auto" href="index.php?page=dashboard">iMangerMieux</a>
    </div>
</nav>

<div class="container form-container">
    <h2 class="text-center">Create Your Account</h2>
    <form id="signupForm">
        <div class="form-group">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" class="form-control" required>
</div>
<div class="form-group">
    <label for="password">Password:</label>
    <input type="password" id="password" name="password" class="form-control" required>
</div>
<div class="form-group">
    <label for="confirmPassword">Confirm Password:</label>
    <input type="password" id="confirmPassword" name="confirmPassword" class="form-control" required>
</div>
<div class="form-group">
    <label for="age">Age:</label>
    <input type="number" id="age" name="age" class="form-control" required>
</div>
<div class="form-group">
    <label for="gender">Gender:</label>
    <select id="gender" name="gender" class="form-control" required>
        <option value="">Select</option>
        <option value="1">Male</option>
        <option value="2">Female</option>
        <option value="3">Other</option>
    </select>
</div>
<div class="form-group">
    <label for="activityLevel">Activity Level:</label>
    <select id="activityLevel" name="activityLevel" class="form-control" required>
        <option value="">Select</option>
        <option value="1">Beginner</option>
        <option value="2">Intermediate</option>
        <option value="3">Advanced</option>
        <option value="4">Expert</option>
        <option value="5">Elite</option>
    </select>
</div>
        <button type="submit" class="btn btn-signup btn-block">Sign Up</button>
    </form>
    <div class="login-link">
        <p>Already have an account? <a href="login.php">Log in here</a>.</p>
    </div>
</div>
<script src="js/signup.js"></script>
</body>
</html>
