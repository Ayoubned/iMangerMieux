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
        </div>

        <!-- "More Details" Button -->
        <div class="text-center my-4">
            <button id="show-more-btn" class="btn btn-secondary">More Details</button>
        </div>

        <!-- Hidden Detailed Stats and Charts -->
        <div id="detailed-stats" class="container-fluid" style="display: none;">
            <div class="row">
                <div class="col-md-6">
                    <h3 class="text-center">All-Time Stats</h3>
                    <div class="card shadow-sm p-3 mb-4">
                        <h5>Total Nutritional Intake</h5>
                        <p>Total Calories: <span id="total-calories-alltime">N/A</span></p>
                        <p>Average Calories: <span id="average-calories-alltime">N/A</span></p>
                        <p>Total Protein: <span id="total-protein-alltime">N/A</span></p>
                        <p>Average Protein: <span id="average-protein-alltime">N/A</span></p>
                        <p>Fruit & Veg Servings: <span id="fruit-veg-servings-alltime">N/A</span></p>
                    </div>

                    <!-- All-Time Average Values Bar Chart -->
                    <div class="card shadow-sm p-3 mb-4">
                        <h5 class="text-center">Average Nutritional Values (All Time)</h5>
                        <canvas id="averageValuesChartAllTime"></canvas>
                    </div>

                    <!-- All-Time Nutrient Percentage Doughnut Chart -->
                    <div class="card shadow-sm p-3">
                        <h5 class="text-center">Nutrient Intake Percentage (All Time)</h5>
                        <canvas id="nutrientPercentageChartAllTime"></canvas>
                    </div>
                </div>

                <div class="col-md-6">
                    <h3 class="text-center">Last 7 Days Stats</h3>
                    <div class="card shadow-sm p-3 mb-4">
                        <h5>Total Nutritional Intake</h5>
                        <p>Total Calories: <span id="total-calories-7days">N/A</span></p>
                        <p>Average Calories: <span id="average-calories-7days">N/A</span></p>
                        <p>Total Protein: <span id="total-protein-7days">N/A</span></p>
                        <p>Average Protein: <span id="average-protein-7days">N/A</span></p>
                        <p>Fruit & Veg Servings: <span id="fruit-veg-servings-7days">N/A</span></p>
                    </div>

                    <!-- Last 7 Days Average Values Bar Chart -->
                    <div class="card shadow-sm p-3 mb-4">
                        <h5 class="text-center">Average Nutritional Values (Last 7 Days)</h5>
                        <canvas id="averageValuesChart7Days"></canvas>
                    </div>

                    <!-- Last 7 Days Nutrient Percentage Doughnut Chart -->
                    <div class="card shadow-sm p-3">
                        <h5 class="text-center">Nutrient Intake Percentage (Last 7 Days)</h5>
                        <canvas id="nutrientPercentageChart7Days"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>