$(document).ready(function() {
    const userProfile = {
      age: "<40",
      gender: "Female",
      activity: "Moderate"
    };
  
    $('#age').val(userProfile.age);
    $('#gender').val(userProfile.gender);
    $('#activity').val(userProfile.activity);
  
    // Handle form submission
    $('#profileForm').on('submit', function(e) {
      e.preventDefault();
      
      // Collect form data
      const profileData = {
        age: $('#age').val(),
        gender: $('#gender').val(),
        activity: $('#activity').val()
      };
  
      // Display alert (replace with AJAX call to save data)
      alert(`Profile saved! Age: ${profileData.age}, Gender: ${profileData.gender}, Activity: ${profileData.activity}`);
    });
  });