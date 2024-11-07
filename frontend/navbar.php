<?php
session_start();
?>

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
                <?php if (isset($_SESSION['username'])): ?>
                    <a class="nav-link" href="logout.php">Sign Out</a>
                <?php else: ?>
                    <a class="nav-link" href="signup.php">Signup</a>
                <?php endif; ?>
            </li>
        </ul>
        <span class="navbar-text ml-auto" id="welcome-message">
            <?php if (isset($_SESSION['username'])): ?>
                Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!
            <?php endif; ?>
        </span>
    </div>
</nav>
