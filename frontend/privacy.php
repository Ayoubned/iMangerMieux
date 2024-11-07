<?php
// privacy.php - Privacy Policy Page for iMangerMieux
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy Policy - iMangerMieux</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/policy.css">
</head>
<body>


    <!-- Privacy Policy Content -->
    <section class="container my-5">
        <h1>Privacy Policy</h1>
        <p>At iMangerMieux, we prioritize your privacy and are committed to protecting your personal data. This Privacy Policy outlines the types of data we collect, how we use it, and the steps we take to ensure its security.</p>

        <h2>Information We Collect</h2>
        <p>We collect personal information necessary to provide our services, including but not limited to your username, age, gender, and activity level. We may also collect data related to your dietary preferences and health goals to offer a more personalized experience.</p>

        <h2>How We Use Your Data</h2>
        <p>Your data is used exclusively for tracking and analyzing your dietary habits to provide you with personalized recommendations. We do not share or sell your information to third parties, and it is only accessible by authorized personnel within iMangerMieux.</p>

        <h2>Data Security</h2>
        <p>We implement robust security measures to protect your information from unauthorized access, alteration, or disclosure. However, please note that no data transmission over the internet is entirely secure, and we cannot guarantee absolute security.</p>

        <h2>Your Rights</h2>
        <p>You have the right to access, update, or delete your personal information at any time. If you have questions or wish to exercise these rights, please contact our support team.</p>

        <h2>Policy Updates</h2>
        <p>We may update this Privacy Policy from time to time. Any changes will be posted on this page, and we encourage you to review it periodically to stay informed.</p>

        <p> <a href="index.php?page=dashboard">Back to Dashboard</a></p>
    </section>


    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
