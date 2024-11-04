document.getElementById('loginForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;

    const response = await fetch(`../backend/users.php?action=login`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ username, password })
    });

    const result = await response.json();
    if (response.ok) {
        // Store user information in session storage if needed
        sessionStorage.setItem('user_id', result.user.ID_UTILISATEUR);
        sessionStorage.setItem('username', result.user.USERNAME);
        sessionStorage.setItem('Age Category', result.user.ID_AGE);
        sessionStorage.setItem('Gender', result.user.ID_SEXE);
        sessionStorage.setItem('Activity level', result.user.ID_NS);

        
        // Redirect to the homepage
        window.location.href = 'index.php';

    } else {
        alert(result.error);
    }
});
