document.getElementById('loginForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;
    const url = API_BASE_URL;

    const response = await fetch(url + `users.php?action=login`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ username, password })
    });

    const result = await response.json();
    if (response.ok) {
        // Redirect to the homepage upon successful login
        window.location.href = 'index.php';
    } else {
        alert(result.error);
    }
});
