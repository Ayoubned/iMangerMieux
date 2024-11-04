<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Tracker</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    
    <?php
        $page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
        $scriptFile = 'js/' . $page . '.js';

        if (file_exists($scriptFile)) {
            echo "<script src='$scriptFile'></script>";
        }
    ?>
</head>
<body>

<div class="container-fluid">
    <?php include 'navbar.php'; ?>
    
    <div class="row mt-4">
        <?php
            $pageFile = $page . '.php';

            if (file_exists($pageFile)) {
                include $pageFile;
            } else {
                echo "<div class='col-12'><h1>Page Not Found</h1></div>";
            }
        ?>
    </div>
</div>
<?php include 'footer.php'; ?>
</body>
</html>
