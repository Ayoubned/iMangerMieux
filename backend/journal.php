<?php
session_start();
require_once("init_pdo.php");

function get_journals($db, $user_id) 
{
    try {
        $sql = "SELECT 
            journal.ID_JOURNAL,
            journal.DATE,
            aliment.NOM,
            reference.QUANTITE,
            contient.VALEUR_RATIO,
            type_aliment.LAB,
            journal.CUSTOM_CALORIES
        FROM 
            journal
        LEFT JOIN reference ON journal.ID_JOURNAL = reference.ID_JOURNAL
        LEFT JOIN aliment ON reference.ID_ALIMENT = aliment.ID_ALIMENT
        LEFT JOIN contient ON aliment.ID_ALIMENT = contient.ID_ALIMENT
        LEFT JOIN type_aliment ON aliment.ID_ALIMENT = type_aliment.ID_ALIMENT
        WHERE 
            (contient.ID_TR = (SELECT ID_TR FROM type_ratio WHERE LAB = 'Energie (kj/100g)') OR journal.CUSTOM_CALORIES IS NOT NULL)
            AND journal.ID_UTILISATEUR = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    } catch (Exception $e) {
        error_log("Error fetching journals: " . $e->getMessage());
        throw new Exception("Failed to fetch journals");
    }
}

function create_journal($db, $data)
{
    try {
        $db->beginTransaction();

        if (isset($data['CUSTOM_CALORIES'])) {
            $sql = "INSERT INTO journal (ID_UTILISATEUR, DATE, CUSTOM_CALORIES) VALUES (?, ?, ?)";
            $stmt = $db->prepare($sql);
            $stmt->execute([
                $data['ID_UTILISATEUR'],
                $data['DATE'],
                $data['CUSTOM_CALORIES']
            ]);
        } else {
            $sql = "SELECT ID_ALIMENT FROM aliment WHERE NOM = ?";
            $stmt = $db->prepare($sql);
            $stmt->execute([$data['NOM']]);
            $alimentID = $stmt->fetch(PDO::FETCH_COLUMN);

            if (!$alimentID) {
                throw new Exception("Aliment not found for the given NOM: " . $data['NOM']);
            }

            $sql = "INSERT INTO journal (ID_UTILISATEUR, DATE) VALUES (?, ?)";
            $stmt = $db->prepare($sql);
            $stmt->execute([
                $data['ID_UTILISATEUR'],
                $data['DATE']
            ]);

            $lastJournalId = $db->lastInsertId();

            $sql = "INSERT INTO reference (ID_JOURNAL, ID_ALIMENT, QUANTITE) VALUES (?, ?, ?)";
            $stmt = $db->prepare($sql);
            $stmt->execute([
                $lastJournalId,
                $alimentID,
                $data['QUANTITE']
            ]);
        }

        $db->commit();
        return $db->lastInsertId();
    } catch (Exception $e) {
        $db->rollBack();
        error_log("Error creating journal: " . $e->getMessage());
        throw new Exception("Failed to create journal entry: " . $e->getMessage());
    }
}

function delete_journal($db, $ID_JOURNAL)
{
    try {
        $db->beginTransaction();

        $sql = "DELETE FROM reference WHERE ID_JOURNAL = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$ID_JOURNAL]);

        $sql = "DELETE FROM journal WHERE ID_JOURNAL = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$ID_JOURNAL]);

        $db->commit();
        return true;
    } catch (Exception $e) {
        $db->rollBack();
        error_log("Error deleting journal: " . $e->getMessage());
        throw new Exception("Failed to delete journal entry: " . $e->getMessage());
    }
}

function setHeaders()
{
    header("Access-Control-Allow-Origin: *");
    header('Content-type: application/json; charset=utf-8');
}

setHeaders();

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["error" => "User not authenticated"]);
    exit;
}

try {
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
                $result = get_journals($pdo, $_SESSION['user_id']);
                echo json_encode($result);
            }
            break;

        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);
            if (isset($data['ID_UTILISATEUR'], $data['DATE'])) {
                try {
                    $journalID = create_journal($pdo, $data);
                    http_response_code(201);
                    echo json_encode([
                        "ID_JOURNAL" => $journalID,
                        "ID_UTILISATEUR" => $data['ID_UTILISATEUR'],
                        "DATE" => $data['DATE']
                    ]);
                } catch (Exception $e) {
                    http_response_code(500);
                    echo json_encode(["error" => $e->getMessage()]);
                }
            } else {
                http_response_code(400);
                echo json_encode(["error" => "Invalid input data"]);
            }
            break;

        case 'DELETE':
            if (isset($_GET['ID'])) {
                try {
                    if (delete_journal($pdo, $_GET['ID'])) {
                        echo json_encode(["message" => "Journal deleted"]);
                    } else {
                        http_response_code(500);
                        echo json_encode(["error" => "Failed to delete journal"]);
                    }
                } catch (Exception $e) {
                    http_response_code(500);
                    echo json_encode(["error" => $e->getMessage()]);
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
} catch (Exception $e) {
    error_log("Unhandled error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(["error" => "Internal server error"]);
}
