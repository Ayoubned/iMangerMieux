<?php
// terms.php - Terms of Service Page for iMangerMieux
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terms of Service - iMangerMieux</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/policy.css">
</head>
<body>
    <!-- Terms of Service Content -->
    <section class="container my-5">
        <h1>Terms of Service</h1>
        <p>Welcome to iMangerMieux. By using our services, you agree to comply with the following terms and conditions. Please read them carefully.</p>

        <h2>Account Responsibilities</h2>
        <p>You are responsible for maintaining the confidentiality of your account credentials and agree not to share them with others. You also agree to provide accurate and truthful information when registering and using our platform.</p>

        <h2>Acceptable Use</h2>
        <p>iMangerMieux is intended for personal use only. You agree not to misuse our services, including but not limited to distributing spam, uploading malicious content, or attempting to disrupt our services.</p>

        <h2>Limitation of Liability</h2>
        <p>iMangerMieux is a tool to support your dietary tracking and health goals. However, we do not guarantee any specific health outcomes, and you should consult a healthcare professional for personalized advice. We are not liable for any damages arising from the use of our platform.</p>

        <h2>Intellectual Property</h2>
        <p>All content and features on iMangerMieux are protected by copyright and other intellectual property laws. You agree not to copy, reproduce, or distribute any materials from our platform without permission.</p>

        <h2>Changes to Terms</h2>
        <p>We reserve the right to modify these Terms of Service at any time. Updated terms will be posted on this page, and continued use of iMangerMieux after changes implies acceptance of the revised terms.</p>

        <p> <a href="index.php?page=dashboard">Back to Dashboard</a></p>
    </section>

   

    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
