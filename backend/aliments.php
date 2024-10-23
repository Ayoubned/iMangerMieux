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

   

    default:
        http_response_code(405);
        echo json_encode(["error" => "Method not allowed"]);

}
?>