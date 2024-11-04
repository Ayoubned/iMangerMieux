document.addEventListener("DOMContentLoaded", function() {
    // Load data from sessionStorage to pre-fill the form fields
    document.getElementById("username").value = sessionStorage.getItem("username") || "";

    const ageGroup = sessionStorage.getItem("Age Category");
    if (ageGroup) {
        document.getElementById("age").value = ageGroup;
    }

    const gender = sessionStorage.getItem("Gender");
    if (gender) {
        document.getElementById("gender").value = gender;
    }

    const activityLevel = sessionStorage.getItem("Activity level");
    if (activityLevel) {
        document.getElementById("activity").value = activityLevel;
    }
});

document.getElementById('profileForm').addEventListener('submit', async function(e) {
    e.preventDefault(); // Prevent default form submission

    const ageGroup = document.getElementById('age').value;
    const gender = document.getElementById('gender').value;
    const activityLevel = document.getElementById('activity').value;
    const userId = sessionStorage.getItem('user_id');

    // Send PUT request to update the profile
    const response = await fetch(`../../backend/users.php?ID_UTILISATEUR=${userId}`, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            ID_AGE: ageGroup,
            ID_SEXE: gender,
            ID_NS: activityLevel
        })
    });
    const textResponse = await response.text();
    console.log('Server response:', textResponse); // Log the actual response
    
    try {
        const result = JSON.parse(textResponse);
        if (response.ok) {
            alert('Profile updated successfully');
            sessionStorage.setItem("Age Category", ageGroup);
            sessionStorage.setItem("Gender", gender);
            sessionStorage.setItem("Activity level", activityLevel);
        } else {
            alert(result.error || 'Failed to update profile');
        }
    } catch (error) {
        console.error('Error parsing JSON:', error);
        alert('Unexpected error occurred.');
    }
    
});
