<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    // Redirect to home if not logged in
    header("Location: home.php");
    exit();
}
include 'config.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Tracker Dashboard</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="js/dashboard.js" defer></script>
    <script>
        const API_BASE_URL = "<?php echo API_BASE_URL; ?>";
    </script>
</head>

<body>

    <div class="container-fluid">
        <div class="row mt-4">
            <!-- Calories Card -->
            <div class="col-md-4">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-header">Calories (Last 7 Days)</div>
                    <div class="card-body">
                        <h5 class="card-title">Total: <span id="total-calories">Loading...</span> kcal</h5>
                        <p class="card-text">Average daily intake: <span id="average-calories">Loading...</span> kcal
                        </p>
                    </div>
                </div>
            </div>

            <!-- Protein Intake Card -->
            <div class="col-md-4">
                <div class="card text-white bg-success mb-3">
                    <div class="card-header">Protein Intake (Last 7 Days)</div>
                    <div class="card-body">
                        <h5 class="card-title">Total: <span id="total-protein">Loading...</span> g</h5>
                        <p class="card-text">Average daily intake: <span id="average-protein">Loading...</span> g</p>
                    </div>
                </div>
            </div>

            <!-- Fruit & Vegetable Intake Card -->
            <div class="col-md-4">
                <div class="card text-white bg-warning mb-3">
                    <div class="card-header">Fruit & Vegetable Intake</div>
                    <div class="card-body">
                        <h5 class="card-title">Servings: <span id="fruit-veg-servings">Loading...</span></h5>
                        <p class="card-text">Recommended daily intake is 5 servings.</p>
                    </div>
                </div>
            </div>
        <!-- Average Nutritional Values Chart -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm p-3">
                <h3 class="text-center">Average Nutritional Values</h3>
                <canvas id="averageValuesChart" class="mx-auto" style="max-width: 100%; height: auto;"></canvas>
            </div>
        </div>

        <!-- Nutrient Intake Percentage Chart -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm p-3">
                <h3 class="text-center">Nutrient Intake Percentage</h3>
                <canvas id="nutrientPercentageChart" class="mx-auto" style="max-width: 100%; height: auto;"></canvas>
            </div>
        </div>
        </div>
    </div>

</body>

</html>