<?php
session_start();
require_once("init_pdo.php");

// Set response headers for JSON and allow cross-origin access
function setHeaders()
{
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=utf-8");
}

setHeaders();

// Check if the user is authenticated
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["error" => "User not authenticated"]);
    exit;
}

// Function to retrieve dashboard data
function getDashboardData($db)
{
    // Define ratio type IDs for calories and proteins
    $caloriesRatioID = 7; 
    $proteinRatioID = 12;

    // Query to get total and average calories for the past 7 days
    $caloriesQuery = "
        SELECT SUM(c.VALEUR_RATIO * r.QUANTITE / 100) AS total_calories, 
               AVG(c.VALEUR_RATIO * r.QUANTITE / 100) AS average_calories
        FROM journal j
        JOIN reference r ON j.ID_JOURNAL = r.ID_JOURNAL
        JOIN contient c ON r.ID_ALIMENT = c.ID_ALIMENT
        WHERE j.ID_UTILISATEUR = :user_id 
          AND j.DATE >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
          AND c.ID_TR = :calories_id
    ";
    $stmtCalories = $db->prepare($caloriesQuery);
    $stmtCalories->execute(['user_id' => $_SESSION['user_id'], 'calories_id' => $caloriesRatioID]);
    $caloriesData = $stmtCalories->fetch(PDO::FETCH_ASSOC);

    // Query to get total and average protein for the past 7 days
    $proteinQuery = "
        SELECT SUM(c.VALEUR_RATIO * r.QUANTITE / 100) AS total_protein, 
               AVG(c.VALEUR_RATIO * r.QUANTITE / 100) AS average_protein
        FROM journal j
        JOIN reference r ON j.ID_JOURNAL = r.ID_JOURNAL
        JOIN contient c ON r.ID_ALIMENT = c.ID_ALIMENT
        WHERE j.ID_UTILISATEUR = :user_id 
          AND j.DATE >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
          AND c.ID_TR = :protein_id
    ";
    $stmtProtein = $db->prepare($proteinQuery);
    $stmtProtein->execute(['user_id' => $_SESSION['user_id'], 'protein_id' => $proteinRatioID]);
    $proteinData = $stmtProtein->fetch(PDO::FETCH_ASSOC);

    // Query to get fruit and vegetable servings count for the past 7 days
    $fruitVegQuery = "
        SELECT COUNT(*) AS fruit_veg_servings
        FROM journal j
        JOIN reference r ON j.ID_JOURNAL = r.ID_JOURNAL
        JOIN type_aliment ta ON r.ID_ALIMENT = ta.ID_ALIMENT
        WHERE j.ID_UTILISATEUR = :user_id
          AND j.DATE >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
          AND ta.LAB = 'fruit' OR ta.LAB = 'vegetable'
    ";
    $stmtFruitVeg = $db->prepare($fruitVegQuery);
    $stmtFruitVeg->execute(['user_id' => $_SESSION['user_id']]);
    $fruitVegData = $stmtFruitVeg->fetch(PDO::FETCH_ASSOC);

    // Combine all data into a single response
    return [
        "totalCalories" => $caloriesData['total_calories'] ?? 0,
        "averageCalories" => $caloriesData['average_calories'] ?? 0,
        "totalProtein" => $proteinData['total_protein'] ?? 0,
        "averageProtein" => $proteinData['average_protein'] ?? 0,
        "fruitVegServings" => $fruitVegData['fruit_veg_servings'] ?? 0
    ];
}

// Check if the request method is GET to provide data for the dashboard
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $dashboardData = getDashboardData($pdo);
    echo json_encode($dashboardData);
    exit;
}
?>
