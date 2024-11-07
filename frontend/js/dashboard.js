$(document).ready(function () {
    // Fetch dashboard data using AJAX
    fetchDashboardData();
});
document.getElementById('show-more-btn').addEventListener('click', function () {
    const detailedStats = document.getElementById('detailed-stats');
    const isHidden = detailedStats.style.display === 'none';
    
    // Toggle visibility
    detailedStats.style.display = isHidden ? 'block' : 'none';

    // Update button text based on visibility
    this.textContent = isHidden ? 'Hide Details' : 'More Details';
});

function fetchDashboardData() {
    $.ajax({
        url: API_BASE_URL + 'dashboard.php',
        method: 'GET',
        dataType: 'json',
        success: function (data) {
            // Update dashboard elements with the data received for all-time stats
            $('#total-calories-alltime').text(data.allTime.totalCalories || 'N/A');
            $('#average-calories-alltime').text(data.allTime.averageCalories || 'N/A');
            $('#total-protein-alltime').text(data.allTime.totalProtein || 'N/A');
            $('#average-protein-alltime').text(data.allTime.averageProtein || 'N/A');
            $('#fruit-veg-servings-alltime').text(data.allTime.fruitVegServings || 'N/A');

            // Update dashboard elements with the data received for last 7 days stats
            $('#total-calories-7days').text(data.last7Days.totalCalories || 'N/A');
            $('#average-calories-7days').text(data.last7Days.averageCalories || 'N/A');
            $('#total-protein-7days').text(data.last7Days.totalProtein || 'N/A');
            $('#average-protein-7days').text(data.last7Days.averageProtein || 'N/A');
            $('#fruit-veg-servings-7days').text(data.last7Days.fruitVegServings || 'N/A');
            $('#total-calories').text(data.last7Days.totalCalories || 'N/A');
            $('#average-calories').text(data.last7Days.averageCalories || 'N/A');
            $('#total-protein').text(data.last7Days.totalProtein || 'N/A');
            $('#average-protein').text(data.last7Days.averageProtein || 'N/A');
            $('#fruit-veg-servings').text(data.last7Days.fruitVegServings || 'N/A');
            

            // Render all-time average values bar chart
            renderAverageValuesChart(
                data.allTime.averageCalories,
                data.allTime.averageProtein,
                data.allTime.averageCalcium,
                data.allTime.averagePotassium,
                data.allTime.averageMagnesium,
                'averageValuesChartAllTime'
            );

            // Render 7-day average values bar chart
            renderAverageValuesChart(
                data.last7Days.averageCalories,
                data.last7Days.averageProtein,
                data.last7Days.averageCalcium,
                data.last7Days.averagePotassium,
                data.last7Days.averageMagnesium,
                'averageValuesChart7Days'
            );

            // Render all-time nutrient percentage doughnut chart
            renderNutrientPercentageChart(
                data.allTime.totalCalories,
                data.allTime.totalProtein,
                data.allTime.totalCalcium,
                data.allTime.totalPotassium,
                data.allTime.totalMagnesium,
                'nutrientPercentageChartAllTime'
            );

            // Render 7-day nutrient percentage doughnut chart
            renderNutrientPercentageChart(
                data.last7Days.totalCalories,
                data.last7Days.totalProtein,
                data.last7Days.totalCalcium,
                data.last7Days.totalPotassium,
                data.last7Days.totalMagnesium,
                'nutrientPercentageChart7Days'
            );
        },
        error: function (xhr, status, error) {
            console.error('Error fetching dashboard data:', error);
            $('#total-calories-alltime, #average-calories-alltime, #total-protein-alltime, #average-protein-alltime, #fruit-veg-servings-alltime').text('Error');
            $('#total-calories-7days, #average-calories-7days, #total-protein-7days, #average-protein-7days, #fruit-veg-servings-7days').text('Error');
        }
    });
}

// Function to render average values bar chart
function renderAverageValuesChart(avgCalories, avgProtein, avgCalcium, avgPotassium, avgMagnesium, chartId) {
    var ctx = document.getElementById(chartId).getContext('2d');
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

// Function to render nutrient percentage doughnut chart with calories in the center
function renderNutrientPercentageChart(totalCalories, totalProtein, totalCalcium, totalPotassium, totalMagnesium, chartId) {
    var ctx = document.getElementById(chartId).getContext('2d');
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
                beforeDraw: function (chart) {
                    const ctx = chart.ctx;
                    ctx.restore();
                    const fontSize = 18;
                    ctx.font = fontSize + "px Arial";
                    ctx.textBaseline = "middle";

                    const centerX = (chart.chartArea.left + chart.chartArea.right) / 2;
                    const centerY = (chart.chartArea.top + chart.chartArea.bottom) / 2;

                    ctx.fillStyle = 'black';
                    ctx.textAlign = 'center';

                    ctx.fillText(`Total Calories`, centerX, centerY - 10);
                    ctx.fillText(`${totalCalories}`, centerX, centerY + 10);
                    ctx.save();
                }
            }
        }
    });
}
