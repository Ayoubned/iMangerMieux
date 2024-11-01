$(document).ready(function() {
  function fetchProfile() {
      $.ajax({
          url: API_BASE_URL + 'users.php?ID_UTILISATEUR=105',  // Replace 1 with dynamic user ID as needed
          method: 'GET',
          success: function(user) {
              $('#age').val(user.ID_AGE);
              $('#gender').val(user.ID_SEXE);
              $('#activity').val(user.ID_NS);
          },
          error: function() {
              alert("Failed to load profile");
          }
      });
  }

  fetchProfile();
});
