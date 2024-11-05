<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="index.php?page=dashboard">iMangerMieux</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="index.php?page=dashboard">Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="index.php?page=journal">Journal</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="index.php?page=aliments">Aliments</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="index.php?page=profil">Profile</a>
            </li>
            <li class="nav-item" id="auth-link">
                <a class="nav-link" href="signup.php" id="signup-link">Signup</a>
                <!-- Placeholder for signout link if logged in -->
            </li>
        </ul>
        <span class="navbar-text ml-auto" id="welcome-message"></span>
    </div>
</nav>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const username = sessionStorage.getItem('username');
    const authLink = document.getElementById('auth-link');
    const welcomeMessage = document.getElementById('welcome-message');

    if (username) {
        // Display welcome message and sign-out link
        welcomeMessage.textContent = `Welcome, ${username}!`;
        authLink.innerHTML = `<a class="nav-link" href="javascript:void(0)" id="signout-link">Sign Out</a>`;
        
        // Add event listener for sign-out
        document.getElementById('signout-link').addEventListener('click', () => {
            // Clear sessionStorage
            sessionStorage.clear();
            // Redirect to logout.php to clear PHP session
            window.location.href = 'logout.php';
        });
    }
});
</script>
