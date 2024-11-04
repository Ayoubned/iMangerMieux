<?php
// logout.php
session_start();

// Destroy all session data
session_unset();
session_destroy();

// Redirect to the login page (or homepage)
header("Location: login.php");
exit;
?>
