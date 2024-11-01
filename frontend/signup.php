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

<script>
    document.getElementById('signupForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const age = document.getElementById('age').value;
        const sex = document.getElementById('sex').value;
        const activity = document.getElementById('activity').value;
        const username = document.getElementById('username').value;
        const password = document.getElementById('password').value;

        // Determine age group based on age
        let ID_AGE;
        if (age < 13) {
            alert("Users must be at least 13 years old.");
            return;
        } else if (age <= 39) {
            ID_AGE = 1;
        } else if (age <= 59) {
            ID_AGE = 2;
        } else {
            ID_AGE = 3;
        }

        const response = await fetch('../backend/users.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                ID_AGE,
                ID_SEXE: sex,
                ID_NS: activity,
                USERNAME: username,
                PASSWORD: password
            })
        });

        const result = await response.json();
        if (response.ok) {
            window.location.href = 'login.php'; // Redirect to login page
        } else {
            alert(result.error);
        }
    });
</script>

</body>
</html>
