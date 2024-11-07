<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    // Redirect to home if not logged in
    header("Location: home.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Tracker Dashboard</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <div class="container-fluid">
        <div class="row mt-4">
            <div class="col-md-4">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-header">Calories (Last 7 Days)</div>
                    <div class="card-body">
                        <h5 class="card-title">Total: 2100 kcal</h5>
                        <p class="card-text">Average daily intake over the past week.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-success mb-3">
                    <div class="card-header">Salt Consumption</div>
                    <div class="card-body">
                        <h5 class="card-title">3.5 g/day</h5>
                        <p class="card-text">Average daily salt intake vs WHO recommendation.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-warning mb-3">
                    <div class="card-header">Fruit & Vegetable Intake</div>
                    <div class="card-body">
                        <h5 class="card-title">4 servings/day</h5>
                        <p class="card-text">Daily average intake for the last 7 days.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Nutritional Intake Chart</div>
                    <div class="card-body">
                        <canvas id="nutritionChart" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="js/dashboard.js">
    </script>

</body>

</html>