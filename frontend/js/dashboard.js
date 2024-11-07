$(document).ready(function() {
    // Fetch dashboard data using AJAX
    fetchDashboardData();
});

function fetchDashboardData() {
    $.ajax({
        url: API_BASE_URL + 'journal.php', 
        method: 'GET',
        dataType: 'json',
        success: function(data) {
            // Update dashboard elements with the data received
            $('#total-calories').text(data.totalCalories || 'N/A');
            $('#average-calories').text(data.averageCalories || 'N/A');
            $('#total-protein').text(data.totalProtein || 'N/A');
            $('#average-protein').text(data.averageProtein || 'N/A');
            $('#fruit-veg-servings').text(data.fruitVegServings || 'N/A');
        },
        error: function(xhr, status, error) {
            console.error('Error fetching dashboard data:', error);
            $('#total-calories').text('Error');
            $('#average-calories').text('Error');
            $('#total-protein').text('Error');
            $('#average-protein').text('Error');
            $('#fruit-veg-servings').text('Error');
        }
    });
}
