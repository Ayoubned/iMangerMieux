<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (isset($_SESSION['user_id'])) {
    // Redirect to dashboard if already logged in
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>iMangerMieux - Home</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/homecss.css">
</head>
<body>
    <!-- Hero Section -->
    <header class="jumbotron text-center my-4">
        <h1 class="display-4">Welcome to iMangerMieux</h1>
        <p class="lead">Track your food intake, monitor your nutrition, and stay healthy with ease.</p>
        <a href="login.php" class="btn btn-primary btn-lg">Get Started</a>
    </header>

    <!-- Features Section -->
    <section class="container text-center">
        <div class="row">
            <div class="col-md-4">
                <img src="https://images.pexels.com/photos/1640777/pexels-photo-1640777.jpeg" alt="Track Nutrition" class="img-fluid mb-3" style="width:100px; height:auto; border-radius:50%;">
                <h3>Track Your Nutrition</h3>
                <p>Log your meals, keep an eye on calories, carbs, proteins, and more. iMangerMieux helps you stay on top of your health goals.</p>
            </div>
            <div class="col-md-4">
                <img src="https://images.pexels.com/photos/2188447/pexels-photo-2188447.jpeg" alt="Set Your Profile" class="img-fluid mb-3" style="width:100px; height:auto; border-radius:50%;">
                <h3>Set Your Profile</h3>
                <p>Customize your experience based on your age, gender, and activity level. Receive personalized recommendations tailored just for you.</p>
            </div>
            <div class="col-md-4">
                <img src="https://images.pexels.com/photos/669617/pexels-photo-669617.jpeg" alt="Monitor Progress" class="img-fluid mb-3" style="width:100px; height:auto; border-radius:50%;">
                <h3>Monitor Progress</h3>
                <p>Get insights and track your progress over time with detailed reports and visualizations of your nutritional intake.</p>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="container my-5">
        <div class="row">
            <div class="col-lg-6">
                <h2>About iMangerMieux</h2>
                <p>iMangerMieux is designed to help you achieve a balanced and healthy diet. With features like meal tracking, personalized recommendations, and detailed reporting, it's easier than ever to manage your nutrition.</p>
                <p>Sign up today to start your journey towards a healthier lifestyle!</p>
            </div>
            <div class="col-lg-6">
                <img src="https://www.morelandobgyn.com/hs-fs/hubfs/Imported_Blog_Media/GettyImages-854725402-1.jpg?width=600&name=GettyImages-854725402-1.jpg" class="img-fluid" alt="Healthy Food" style="border-radius: 0.5rem; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);">
            </div>
        </div>
    </section>


    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
