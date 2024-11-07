$(document).ready(function () {
    // Fetch dashboard data using AJAX
    fetchDashboardData();
});

function fetchDashboardData() {
    $.ajax({
        url: API_BASE_URL + 'dashboard.php',
        method: 'GET',
        dataType: 'json',
        success: function (data) {
            // Update dashboard elements with the data received
            $('#total-calories').text(data.totalCalories || 'N/A');
            $('#average-calories').text(data.averageCalories || 'N/A');
            $('#total-protein').text(data.totalProtein || 'N/A');
            $('#average-protein').text(data.averageProtein || 'N/A');
            $('#fruit-veg-servings').text(data.fruitVegServings || 'N/A');

            // Render average values bar chart
            renderAverageValuesChart(data.averageCalories, data.averageProtein, data.averageCalcium, data.averagePotassium, data.averageMagnesium);

            // Render percentage doughnut chart
            renderNutrientPercentageChart(data.totalCalories, data.totalProtein, data.totalCalcium, data.totalPotassium, data.totalMagnesium);
        },
        error: function (xhr, status, error) {
            console.error('Error fetching dashboard data:', error);
            $('#total-calories').text('Error');
            $('#average-calories').text('Error');
            $('#total-protein').text('Error');
            $('#average-protein').text('Error');
            $('#fruit-veg-servings').text('Error');
        }
    });
}

// Function to render average values bar chart
function renderAverageValuesChart(avgCalories, avgProtein, avgCalcium, avgPotassium, avgMagnesium) {
    var ctx = document.getElementById('averageValuesChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Calories', 'Protein', 'Calcium', 'Potassium', 'Magnesium'],
            datasets: [{
                label: 'Average Intake',
                data: [avgCalories, avgProtein, avgCalcium, avgPotassium, avgMagnesium],
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
}

// Function to render nutrient percentage doughnut chart
// Function to render nutrient percentage doughnut chart with calories in the center
function renderNutrientPercentageChart(totalCalories, totalProtein, totalCalcium, totalPotassium, totalMagnesium) {
    var ctx = document.getElementById('nutrientPercentageChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Protein', 'Calcium', 'Potassium', 'Magnesium'],
            datasets: [{
                label: 'Nutrient Intake Percentage',
                data: [totalProtein, totalCalcium, totalPotassium, totalMagnesium],
                backgroundColor: [
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)'
                ],
                borderColor: [
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'top' },
                tooltip: { enabled: true },
                // Custom plugin to show total calories in the center
                beforeDraw: function (chart) {
                    const ctx = chart.ctx;
                    ctx.restore();
                    const fontSize = 18;
                    ctx.font = fontSize + "px Arial";
                    ctx.textBaseline = "middle";

                    // Calculate position for total calories text
                    const centerX = (chart.chartArea.left + chart.chartArea.right) / 2;
                    const centerY = (chart.chartArea.top + chart.chartArea.bottom) / 2;

                    // Set text color and align
                    ctx.fillStyle = 'black';
                    ctx.textAlign = 'center';

                    // Draw the text
                    ctx.fillText(`Total Calories`, centerX, centerY - 10);
                    ctx.fillText(`${totalCalories}`, centerX, centerY + 10);
                    ctx.save();
                }
            }
        }
    });
}


