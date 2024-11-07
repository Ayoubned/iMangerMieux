<?php
session_start();
require_once("init_pdo.php");

function get_journals($db)
{
    $sql = "SELECT 
    journal.ID_JOURNAL,
    journal.DATE,
    aliment.NOM ,
    reference.QUANTITE ,
    contient.VALEUR_RATIO ,
    type_aliment.LAB 
FROM 
    journal
JOIN reference ON journal.ID_JOURNAL = reference.ID_JOURNAL
JOIN aliment ON reference.ID_ALIMENT = aliment.ID_ALIMENT
JOIN contient ON aliment.ID_ALIMENT = contient.ID_ALIMENT
JOIN type_aliment ON aliment.ID_ALIMENT = type_aliment.ID_ALIMENT
WHERE 
    contient.ID_TR = (SELECT ID_TR FROM type_ratio WHERE LAB = 'Energie (kj/100g)') ;";
    $exe = $db->query($sql);
    $res = $exe->fetchAll(PDO::FETCH_OBJ);
    return $res;
}

function create_journal($db, $data)
{
    $sql = "INSERT INTO journal (ID_UTILISATEUR, DATE) VALUES (?, ?);
INSERT INTO reference (ID_JOURNAL, ID_ALIMENT, QUANTITE) VALUES (LAST_INSERT_ID(), ?, ?);  
";
    $stmt = $db->prepare($sql);
    $stmt->execute([
        $data['ID_UTILISATEUR'],
        $data['DATE'],
        $data['ID_ALIMENT'],
        $data['QUANTITE']
    ]);
    return $db->lastInsertId();
}

function update_journal($db, $ID_JOURNAL, $data)
{
    $stmt = $db->prepare("SELECT * FROM journal WHERE ID_JOURNAL = ?");
    $stmt->execute([$ID_JOURNAL]);
    $journal = $stmt->fetch(PDO::FETCH_OBJ);

    if (!$journal) {
        return false;
    }

    $ID_UTILISATEUR = isset($data['ID_UTILISATEUR']) ? $data['ID_UTILISATEUR'] : $journal->ID_UTILISATEUR;
    $DATE = isset($data['DATE']) ? $data['DATE'] : $journal->DATE;

    $sql = "UPDATE journal SET ID_UTILISATEUR = ?, DATE = ? WHERE ID_JOURNAL = ?";
    $stmt = $db->prepare($sql);
    return $stmt->execute([$ID_UTILISATEUR, $DATE, $ID_JOURNAL]);
}

function delete_journal($db, $ID_JOURNAL)
{
    $sql = "DELETE FROM journal WHERE ID_JOURNAL = ?";
    $stmt = $db->prepare($sql);
    return $stmt->execute([$ID_JOURNAL]);
}

function setHeaders()
{
    header("Access-Control-Allow-Origin: *");
    header('Content-type: application/json; charset=utf-8');
}

setHeaders();

// Validate if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["error" => "User not authenticated"]);
    exit;
}

// Handle API Requests
switch ($_SERVER["REQUEST_METHOD"]) {
    case 'GET':
        if (isset($_GET['ID_JOURNAL'])) {
            $stmt = $pdo->prepare("SELECT * FROM journal WHERE ID_JOURNAL = ?");
            $stmt->execute([$_GET['ID_JOURNAL']]);
            $journal = $stmt->fetch(PDO::FETCH_OBJ);
            if (!$journal) {
                http_response_code(404);
                echo json_encode(["error" => "Journal not found"]);
            } else {
                echo json_encode($journal);
            }
        } else {
            $result = get_journals($pdo);
            echo json_encode($result);
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data['ID_UTILISATEUR'], $data['DATE'])) {
            $journalID = create_journal($pdo, $data);
            http_response_code(201);
            echo json_encode([
                "ID_JOURNAL" => $journalID,
                "ID_UTILISATEUR" => $data['ID_UTILISATEUR'],
                "DATE" => $data['DATE']
            ]);
        } else {
            http_response_code(400);
            echo json_encode(["error" => "Invalid input data"]);
        }
        break;

    case 'PUT':
        if (isset($_GET['ID_JOURNAL'])) {
            $stmt = $pdo->prepare("SELECT * FROM journal WHERE ID_JOURNAL = ?");
            $stmt->execute([$_GET['ID_JOURNAL']]);
            $journal = $stmt->fetch(PDO::FETCH_OBJ);

            if (!$journal) {
                http_response_code(404);
                echo json_encode(["error" => "Journal not found"]);
            } else {
                $data = json_decode(file_get_contents('php://input'), true);
                if (update_journal($pdo, $_GET['ID_JOURNAL'], $data)) {
                    echo json_encode([
                        "ID_JOURNAL" => $_GET['ID_JOURNAL'],
                        "ID_UTILISATEUR" => $data['ID_UTILISATEUR'] ?? $journal->ID_UTILISATEUR,
                        "DATE" => $data['DATE'] ?? $journal->DATE
                    ]);
                } else {
                    http_response_code(500);
                    echo json_encode(["error" => "Failed to update journal"]);
                }
            }
        } else {
            http_response_code(400);
            echo json_encode(["error" => "Journal ID is required"]);
        }
        break;

    case 'DELETE':
        if (isset($_GET['ID_JOURNAL'])) {
            $stmt = $pdo->prepare("SELECT * FROM journal WHERE ID_JOURNAL = ?");
            $stmt->execute([$_GET['ID_JOURNAL']]);
            $journal = $stmt->fetch(PDO::FETCH_OBJ);

            if (!$journal) {
                http_response_code(404);
                echo json_encode(["error" => "Journal not found"]);
            } else {
                if (delete_journal($pdo, $_GET['ID_JOURNAL'])) {
                    echo json_encode(["message" => "Journal deleted"]);
                } else {
                    http_response_code(500);
                    echo json_encode(["error" => "Failed to delete journal"]);
                }
            }
        } else {
            http_response_code(400);
            echo json_encode(["error" => "Journal ID is required"]);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(["error" => "Method not allowed"]);
}
?>
