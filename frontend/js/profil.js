document.addEventListener("DOMContentLoaded", function() {
    const profileForm = document.getElementById('profileForm');
    if (profileForm) {
        profileForm.addEventListener('submit', async function(e) {
            e.preventDefault(); // Prevent default form submission

            const ageGroup = document.getElementById('age').value;
            const gender = document.getElementById('gender').value;
            const activityLevel = document.getElementById('activity').value;
            const url = API_BASE_URL;

            // Send POST request to update the profile
            try {
                const response = await fetch(`${url}users.php`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        update: true,  // Ensures backend understands this is an update
                        ID_AGE: ageGroup,
                        ID_SEXE: gender,
                        ID_NS: activityLevel
                    })
                });

                if (response.ok) {
                    alert('Profile updated successfully');
                } else {
                    const error = await response.json();
                    alert(error.message || 'Failed to update profile');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An unexpected error occurred.');
            }
        });
    } else {
        console.error("Profile form not found. Check if the form's ID is 'profileForm'.");
    }
});
