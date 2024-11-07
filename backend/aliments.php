<?php
session_start();
require_once("init_pdo.php");

function search_aliments($db, $name)
{
    $stmt = $db->prepare("SELECT NOM FROM aliment WHERE NOM LIKE ? LIMIT 10");
    $stmt->execute(["%$name%"]);
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}

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
// Handle search requests for aliment suggestions
if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["aliment_name"])) {
    $aliment_name = $_GET["aliment_name"];
    $suggestions = search_aliments($pdo, $aliment_name);
    echo json_encode($suggestions);
    exit;
}
function get_aliment($db)
{
    $sql = "SELECT 
                aliment.ID_ALIMENT, 
                aliment.NOM, 
                JSON_OBJECTAGG(type_ratio.LAB, contient.VALEUR_RATIO) AS RATIOS 
            FROM aliment
            LEFT JOIN contient ON aliment.ID_ALIMENT = contient.ID_ALIMENT
            LEFT JOIN type_ratio ON contient.ID_TR = type_ratio.ID_TR
            GROUP BY aliment.ID_ALIMENT";
    $exe = $db->query($sql);
    $res = $exe->fetchAll(PDO::FETCH_OBJ);
    return $res;
}

function create_aliment($db, $data)
{
    $sql = "INSERT INTO aliment (NOM) VALUES (?)";
    $stmt = $db->prepare($sql);
    $stmt->execute([$data['NOM']]);
    return $db->lastInsertId();
}

function update_aliment($db, $ID_ALIMENT, $data)
{
    $sql = "UPDATE aliment SET NOM = ? WHERE ID_ALIMENT = ?";
    $stmt = $db->prepare($sql);
    return $stmt->execute([$data['NOM'], $ID_ALIMENT]);
}

function delete_aliment($db, $ID_ALIMENT)
{
    $sql = "DELETE FROM aliment WHERE ID_ALIMENT = ?";
    $stmt = $db->prepare($sql);
    return $stmt->execute([$ID_ALIMENT]);
}

// Handle API Requests
switch ($_SERVER["REQUEST_METHOD"]) {
    case 'GET':
        // Search by ID_ALIMENT if provided
        if (isset($_GET['ID_ALIMENT'])) {
            $stmt = $pdo->prepare("SELECT 
                                       aliment.ID_ALIMENT, 
                                       aliment.NOM, 
                                       JSON_OBJECTAGG(type_ratio.LAB, contient.VALEUR_RATIO) AS RATIOS
                                   FROM aliment
                                   LEFT JOIN contient ON aliment.ID_ALIMENT = contient.ID_ALIMENT
                                   LEFT JOIN type_ratio ON contient.ID_TR = type_ratio.ID_TR
                                   WHERE aliment.ID_ALIMENT = ?
                                   GROUP BY aliment.ID_ALIMENT");
            $stmt->execute([$_GET['ID_ALIMENT']]);
            $aliment = $stmt->fetch(PDO::FETCH_OBJ);
            if (!$aliment) {
                http_response_code(404);
                echo json_encode(["error" => "Aliment not found"]);
            } else {
                echo json_encode($aliment);
            }
        }
        // Search by NOM if provided
        elseif (isset($_GET['NOM'])) {
            $stmt = $pdo->prepare("SELECT 
                                       aliment.ID_ALIMENT, 
                                       aliment.NOM
                                   FROM aliment
                                   WHERE aliment.NOM LIKE ?");
            $stmt->execute(['%' . $_GET['NOM'] . '%']);
            $aliments = $stmt->fetchAll(PDO::FETCH_OBJ);
            if (!$aliments) {
                http_response_code(404);
                echo json_encode(["error" => "No matching aliments found"]);
            } else {
                echo json_encode($aliments);
            }
        }
        // If neither ID_ALIMENT nor NOM is provided, return all aliments
        else {
            $result = get_aliment($pdo);
            echo json_encode($result);
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data['NOM'])) {
            $alimentID = create_aliment($pdo, $data);
            http_response_code(201);
            echo json_encode(["ID_ALIMENT" => $alimentID, "NOM" => $data['NOM']]);
        } else {
            http_response_code(400);
            echo json_encode(["error" => "Invalid input data"]);
        }
        break;

    case 'PUT':
        if (isset($_GET['ID_ALIMENT'])) {
            $stmt = $pdo->prepare("SELECT * FROM aliment WHERE ID_ALIMENT = ?");
            $stmt->execute([$_GET['ID_ALIMENT']]);
            $aliment = $stmt->fetch(PDO::FETCH_OBJ);

            if (!$aliment) {
                http_response_code(404);
                echo json_encode(["error" => "Aliment not found"]);
            } else {
                $data = json_decode(file_get_contents('php://input'), true);
                if (isset($data['NOM']) && update_aliment($pdo, $_GET['ID_ALIMENT'], $data)) {
                    echo json_encode(["ID_ALIMENT" => $_GET['ID_ALIMENT'], "NOM" => $data['NOM']]);
                } else {
                    http_response_code(400);
                    echo json_encode(["error" => "Failed to update aliment"]);
                }
            }
        } else {
            http_response_code(400);
            echo json_encode(["error" => "Aliment ID is required"]);
        }
        break;

    case 'DELETE':
        if (isset($_GET['ID_ALIMENT'])) {
            $stmt = $pdo->prepare("SELECT * FROM aliment WHERE ID_ALIMENT = ?");
            $stmt->execute([$_GET['ID_ALIMENT']]);
            $aliment = $stmt->fetch(PDO::FETCH_OBJ);

            if (!$aliment) {
                http_response_code(404);
                echo json_encode(["error" => "Aliment not found"]);
            } else {
                if (delete_aliment($pdo, $_GET['ID_ALIMENT'])) {
                    echo json_encode(["message" => "Aliment deleted successfully"]);
                } else {
                    http_response_code(500);
                    echo json_encode(["error" => "Failed to delete aliment"]);
                }
            }
        } else {
            http_response_code(400);
            echo json_encode(["error" => "Aliment ID is required"]);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(["error" => "Method not allowed"]);
}

?>
