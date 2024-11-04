<?php
session_start();

// Redirect to login if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container">
    <?php include 'navbar.php'; ?>

    <h2 class="mt-4">User Profile</h2>
    <p class="lead">Please enter your profile information to personalize your food tracking experience.</p>

    <form id="profileForm" class="mt-4">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" class="form-control" id="username" readonly>
        </div>
        <div class="form-group">
            <label for="age">Age Group</label>
            <select class="form-control" id="age">
                <option value="1">Under 40</option>
                <option value="2">40-59</option>
                <option value="3">60 and above</option>
            </select>
        </div>
        <div class="form-group">
            <label for="gender">Gender</label>
            <select class="form-control" id="gender">
                <option value="1">Male</option>
                <option value="2">Female</option>
                <option value="3">Other</option>
            </select>
        </div>
        <div class="form-group">
            <label for="activity">Activity Level</label>
            <select class="form-control" id="activity">
                <option value="1">Beginner</option>
                <option value="2">Intermediate</option>
                <option value="3">Advanced</option>
                <option value="4">Expert</option>
                <option value="5">Elite</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Save Profile</button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="js/profil.js"></script>

</body>
</html>
