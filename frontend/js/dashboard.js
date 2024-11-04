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