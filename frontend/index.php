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
        <?php include 'navbar.php'; ?>
        <div class="row mt-4">
            <!-- Dashboard Cards for Indicators -->
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

        <!-- Chart Section -->
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

    <!-- jQuery, Popper.js, and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Chart.js for displaying the nutrition chart -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        var ctx = document.getElementById('nutritionChart').getContext('2d');
        var nutritionChart = new Chart(ctx, {
            type: 'line', // or 'bar', 'pie', etc.
            data: {
                labels: ['Day 1', 'Day 2', 'Day 3', 'Day 4', 'Day 5', 'Day 6', 'Day 7'],
                datasets: [{
                    label: 'Calories',
                    data: [2000, 2100, 1900, 2200, 2300, 1800, 2500],
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{ ticks: { beginAtZero: true } }]
                }
            }
        });
    </script>

</body>

</html>