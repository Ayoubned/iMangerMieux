<?php
session_start();
require_once("init_pdo.php");

function setHeaders()
{
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=utf-8");
}

setHeaders();

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["error" => "User not authenticated"]);
    exit;
}

function getDashboardData($db)
{
    $user_id = $_SESSION['user_id'];
    $caloriesRatioID = 7;
    $proteinRatioID = 12;
    $calciumRatioID = 15;
    $potassiumRatioID = 15;
    $magnesiumRatioID = 19;

    // Helper function to execute a query and fetch the result
    function fetchData($db, $query, $params) {
        $stmt = $db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Define query templates
    $queryTemplate = "
        SELECT SUM(c.VALEUR_RATIO * r.QUANTITE / 100) AS total, 
               AVG(c.VALEUR_RATIO * r.QUANTITE / 100) AS average
        FROM journal j
        JOIN reference r ON j.ID_JOURNAL = r.ID_JOURNAL
        JOIN contient c ON r.ID_ALIMENT = c.ID_ALIMENT
        WHERE j.ID_UTILISATEUR = :user_id 
          AND c.ID_TR = :ratio_id";

    $queryLast7Days = $queryTemplate . " AND j.DATE >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";

    // Fetch all-time stats
    $allTimeStats = [
        'calories' => fetchData($db, $queryTemplate, ['user_id' => $user_id, 'ratio_id' => $caloriesRatioID]),
        'protein' => fetchData($db, $queryTemplate, ['user_id' => $user_id, 'ratio_id' => $proteinRatioID]),
        'calcium' => fetchData($db, $queryTemplate, ['user_id' => $user_id, 'ratio_id' => $calciumRatioID]),
        'potassium' => fetchData($db, $queryTemplate, ['user_id' => $user_id, 'ratio_id' => $potassiumRatioID]),
        'magnesium' => fetchData($db, $queryTemplate, ['user_id' => $user_id, 'ratio_id' => $magnesiumRatioID])
    ];

    // Fetch 7-day stats
    $sevenDayStats = [
        'calories' => fetchData($db, $queryLast7Days, ['user_id' => $user_id, 'ratio_id' => $caloriesRatioID]),
        'protein' => fetchData($db, $queryLast7Days, ['user_id' => $user_id, 'ratio_id' => $proteinRatioID]),
        'calcium' => fetchData($db, $queryLast7Days, ['user_id' => $user_id, 'ratio_id' => $calciumRatioID]),
        'potassium' => fetchData($db, $queryLast7Days, ['user_id' => $user_id, 'ratio_id' => $potassiumRatioID]),
        'magnesium' => fetchData($db, $queryLast7Days, ['user_id' => $user_id, 'ratio_id' => $magnesiumRatioID])
    ];

    // Fruit and vegetable servings count
    $fruitVegQueryAllTime = "
        SELECT COUNT(*) AS fruit_veg_servings
        FROM journal j
        JOIN reference r ON j.ID_JOURNAL = r.ID_JOURNAL
        JOIN type_aliment ta ON r.ID_ALIMENT = ta.ID_ALIMENT
        WHERE j.ID_UTILISATEUR = :user_id
          AND (ta.LAB = 'fruit' OR ta.LAB = 'vegetable')
    ";

    $fruitVegQueryLast7Days = $fruitVegQueryAllTime . " AND j.DATE >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";

    $fruitVegDataAllTime = fetchData($db, $fruitVegQueryAllTime, ['user_id' => $user_id]);
    $fruitVegDataLast7Days = fetchData($db, $fruitVegQueryLast7Days, ['user_id' => $user_id]);

    return [
        'allTime' => [
            "totalCalories" => round($allTimeStats['calories']['total'] ?? 0, 2),
            "averageCalories" => round($allTimeStats['calories']['average'] ?? 0, 2),
            "totalProtein" => round($allTimeStats['protein']['total'] ?? 0, 2),
            "averageProtein" => round($allTimeStats['protein']['average'] ?? 0, 2),
            "totalCalcium" => round($allTimeStats['calcium']['total'] ?? 0, 2),
            "averageCalcium" => round($allTimeStats['calcium']['average'] ?? 0, 2),
            "totalPotassium" => round($allTimeStats['potassium']['total'] ?? 0, 2),
            "averagePotassium" => round($allTimeStats['potassium']['average'] ?? 0, 2),
            "totalMagnesium" => round($allTimeStats['magnesium']['total'] ?? 0, 2),
            "averageMagnesium" => round($allTimeStats['magnesium']['average'] ?? 0, 2),
            "fruitVegServings" => $fruitVegDataAllTime['fruit_veg_servings'] ?? 0
        ],
        'last7Days' => [
            "totalCalories" => round($sevenDayStats['calories']['total'] ?? 0, 2),
            "averageCalories" => round($sevenDayStats['calories']['average'] ?? 0, 2),
            "totalProtein" => round($sevenDayStats['protein']['total'] ?? 0, 2),
            "averageProtein" => round($sevenDayStats['protein']['average'] ?? 0, 2),
            "totalCalcium" => round($sevenDayStats['calcium']['total'] ?? 0, 2),
            "averageCalcium" => round($sevenDayStats['calcium']['average'] ?? 0, 2),
            "totalPotassium" => round($sevenDayStats['potassium']['total'] ?? 0, 2),
            "averagePotassium" => round($sevenDayStats['potassium']['average'] ?? 0, 2),
            "totalMagnesium" => round($sevenDayStats['magnesium']['total'] ?? 0, 2),
            "averageMagnesium" => round($sevenDayStats['magnesium']['average'] ?? 0, 2),
            "fruitVegServings" => $fruitVegDataLast7Days['fruit_veg_servings'] ?? 0
        ]
    ];
}

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $dashboardData = getDashboardData($pdo);
    echo json_encode($dashboardData);
    exit;
}
?>
