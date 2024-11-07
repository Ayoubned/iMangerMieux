<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirect to login if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
include 'config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script>
        const API_BASE_URL = "<?php echo API_BASE_URL; ?>";
    </script>
</head>
<body>

<div class="container">
    <h2 class="mt-4">User Profile</h2>
    <p class="lead">Please enter your profile information to personalize your food tracking experience.</p>

    <form id="profileForm" class="mt-4">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" class="form-control" id="username" value="<?php echo htmlspecialchars($_SESSION['username']); ?>" readonly>
        </div>
        <div class="form-group">
            <label for="age">Age Group</label>
            <select class="form-control" id="age">
                <option value="1" <?php echo $_SESSION['age_group'] == 1 ? 'selected' : ''; ?>>Under 40</option>
                <option value="2" <?php echo $_SESSION['age_group'] == 2 ? 'selected' : ''; ?>>40-59</option>
                <option value="3" <?php echo $_SESSION['age_group'] == 3 ? 'selected' : ''; ?>>60 and above</option>
            </select>
        </div>
        <div class="form-group">
            <label for="gender">Gender</label>
            <select class="form-control" id="gender">
                <option value="1" <?php echo $_SESSION['gender'] == 1 ? 'selected' : ''; ?>>Male</option>
                <option value="2" <?php echo $_SESSION['gender'] == 2 ? 'selected' : ''; ?>>Female</option>
                <option value="3" <?php echo $_SESSION['gender'] == 3 ? 'selected' : ''; ?>>Other</option>
            </select>
        </div>
        <div class="form-group">
            <label for="activity">Activity Level</label>
            <select class="form-control" id="activity">
                <option value="1" <?php echo $_SESSION['activity_level'] == 1 ? 'selected' : ''; ?>>Beginner</option>
                <option value="2" <?php echo $_SESSION['activity_level'] == 2 ? 'selected' : ''; ?>>Intermediate</option>
                <option value="3" <?php echo $_SESSION['activity_level'] == 3 ? 'selected' : ''; ?>>Advanced</option>
                <option value="4" <?php echo $_SESSION['activity_level'] == 4 ? 'selected' : ''; ?>>Expert</option>
                <option value="5" <?php echo $_SESSION['activity_level'] == 5 ? 'selected' : ''; ?>>Elite</option>
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
