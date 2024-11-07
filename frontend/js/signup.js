document.getElementById('signupForm').addEventListener('submit', function (e) {
    e.preventDefault();
    // Redirect form submission to server-side PHP handling in signup.php
    document.getElementById('signupForm').submit();
});
