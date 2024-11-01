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