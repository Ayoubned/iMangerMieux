document.getElementById('loginForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;

    const response = await fetch(`../backend/users.php?username=${encodeURIComponent(username)}&password=${encodeURIComponent(password)}`, {
        method: 'GET',
        headers: { 'Content-Type': 'application/json' }
    });

    const result = await response.json();
    if (response.ok) {
        window.location.href = 'index.php'; // Redirect to the homepage
    } else {
        alert(result.error);
    }
});