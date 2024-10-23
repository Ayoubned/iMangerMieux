<?php
require_once("init_pdo.php");

function get_aliment($db)
{
    $sql = "SELECT * FROM aliment";
    $exe = $db->query($sql);
    $res = $exe->fetchAll(PDO::FETCH_OBJ);
    return $res;
}

function create_aliment($db, $data)
{
    $sql = "INSERT INTO aliment (NOM) VALUES (?)";
    $stmt = $db->prepare($sql);
    $stmt->execute([$data['NOM']]);
    return $db->lastInsertid();
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


function setHeaders()
{
    header("Access-Control-Allow-Origin: *");
    header('Content-type: application/json; charset=utf-8');
}

setHeaders();

// =================
// Handle API Requests
// =================
switch ($_SERVER["REQUEST_METHOD"]) {
    case 'GET':
        if (isset($_GET['ID_ALIMENT'])) {
            $stmt = $pdo->prepare("SELECT * FROM aliment WHERE ID_ALIMENT = ?");
            $stmt->execute([$_GET['ID_ALIMENT']]);
            $aliment = $stmt->fetch(PDO::FETCH_OBJ);
            if (json_encode($aliment) == 'false') {
                http_response_code(404);
                echo json_encode(["error" => "aliment not found"]);
                break;
            }
            echo json_encode($aliment);
        } else {
            $result = get_aliment($pdo);
            echo json_encode($result);
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data['NOM'])) {
            $alimentID_ALIMENT = create_aliment($pdo, $data);
            http_response_code(201);
            echo json_encode(["ID_ALIMENT" => $alimentID_ALIMENT, "NOM" => $data['NOM']]);
        } else {
            http_response_code(400);
            echo json_encode(["error" => "InvalID_ALIMENT input data"]);
        }
        break;

    case 'PUT':
        if (isset($_GET['ID_ALIMENT'])) {
            $stmt = $pdo->prepare("SELECT * FROM aliment WHERE ID_ALIMENT = ?");
            $stmt->execute([$_GET['ID_ALIMENT']]);
            $aliment = $stmt->fetch(PDO::FETCH_OBJ);

            if (!$aliment) {
                http_response_code(404);
                echo json_encode(["error" => "aliment not found"]);
                break;
            }
            $ID_ALIMENT = $_GET['ID_ALIMENT'];
            $data = json_decode(file_get_contents('php://input'), true);
            if (isset($data['NOM'])) {
                if (update_aliment($pdo, $ID_ALIMENT, $data)) {
                    http_response_code(200);
                    echo json_encode(["ID_ALIMENT" => $ID_ALIMENT, "NOM" => $data['NOM']]);
                } else {
                    http_response_code(500);
                    echo json_encode(["error" => "Failed to update aliment"]);
                }
            } else {
                http_response_code(400);
                echo json_encode(["error" => "InvalID_ALIMENT input data"]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["error" => "aliment ID_ALIMENT is required"]);
        }
        break;

    case 'DELETE':
        if (isset($_GET['ID_ALIMENT'])) {
            $stmt = $pdo->prepare("SELECT * FROM aliment WHERE ID_ALIMENT = ?");
            $stmt->execute([$_GET['ID_ALIMENT']]);
            $aliment = $stmt->fetch(PDO::FETCH_OBJ);

            if (!$aliment) {
                http_response_code(404);
                echo json_encode(["error" => "aliment not found"]);
            } else {
                if (delete_aliment($pdo, $_GET['ID_ALIMENT'])) {
                    http_response_code(200);
                    echo json_encode(["message" => "aliment deleted"]);
                } else {
                    http_response_code(500);
                    echo json_encode(["error" => "Failed to delete aliment"]);
                }
            }
        } else {
            http_response_code(400);
            echo json_encode(["error" => "aliment ID_ALIMENT is required"]);
        }
        break;


    default:
        http_response_code(405);
        echo json_encode(["error" => "Method not allowed"]);

}
?>