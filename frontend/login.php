<?php
session_start();
include("config.php");
require_once(__DIR__ . "/../backend/init_pdo.php");
if (isset($_SESSION['user_id'])) {
    // Redirect to dashboard if already logged in
    header("Location: dashboard.php");
    exit();
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE USERNAME = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_OBJ);

    if ($user && password_verify($password, $user->PASSWORD)) {
        $_SESSION['user_id'] = $user->ID_UTILISATEUR;
        $_SESSION['username'] = $user->USERNAME;
        $_SESSION['age_group'] = $user->ID_AGE;
        $_SESSION['gender'] = $user->ID_SEXE;
        $_SESSION['activity_level'] = $user->ID_NS;

        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Invalid credentials";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/login.css">
    <script>
        const API_BASE_URL = "<?php echo API_BASE_URL; ?>";
    </script>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand mx-auto" href="index.php?page=dashboard">iMangerMieux</a>
    </div>
</nav>

<div class="container form-container">
    <h2 class="text-center">Login to Your Account</h2>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger" role="alert">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>
    <form method="POST" action="login.php" id="loginForm">
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
