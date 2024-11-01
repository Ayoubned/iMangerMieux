<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<form id="signupForm">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required>

    <label for="age">Age:</label>
    <input type="number" id="age" name="age" required>

    <label for="sex">Sex:</label>
    <select id="sex" name="sex" required>
        <option value="1">Male</option>
        <option value="2">Female</option>
        <option value="3">Other</option>
    </select>

    <label for="activity">Activity Level:</label>
    <select id="activity" name="activity" required>
        <option value="1">Beginner</option>
        <option value="2">Intermediate</option>
        <option value="3">Advanced</option>
        <option value="3">Expert</option>
        <option value="3">Elite</option>
    </select>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>

    <button type="submit">Sign Up</button>
</form>

<script src="js/signup.js"></script>

</body>
</html>
