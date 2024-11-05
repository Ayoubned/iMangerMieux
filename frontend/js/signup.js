document.getElementById('signupForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirmPassword').value;
    const age = parseInt(document.getElementById('age').value, 10);
    const gender = document.getElementById('gender').value;
    const activityLevel = document.getElementById('activityLevel').value;

    // Password strength validation
    const passwordRequirements = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
    if (!passwordRequirements.test(password)) {
        alert("Password must be at least 8 characters long, include at least one uppercase letter, one lowercase letter, one number, and one special character.");
        return;
    }

    // Validate password confirmation
    if (password !== confirmPassword) {
        alert("Passwords do not match.");
        return;
    }

    // Determine age category based on age
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

    // Prepare data to send
    const signupData = {
        ID_AGE,
        ID_SEXE: gender,
        ID_NS: activityLevel,
        USERNAME: username,
        PASSWORD: password
    };

    // Send data to the server
    const response = await fetch('../backend/users.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(signupData)
    });

    const result = await response.json();
    if (response.ok) {
        // Redirect to login page on successful signup
        window.location.href = 'login.php';
    } else {
        alert(result.error);
    }
});
