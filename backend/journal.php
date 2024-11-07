<?php
session_start();
require_once("init_pdo.php");

function get_journals($db, $user_id) 
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
    contient.ID_TR = (SELECT ID_TR FROM type_ratio WHERE LAB = 'Energie (kj/100g)') AND journal.ID_UTILISATEUR = ? ;";
    $stmt = $db->prepare($sql);
    $stmt->execute([$user_id]);
    $res = $stmt->fetchAll(PDO::FETCH_OBJ);
    return $res;
}

function create_journal($db, $data)
{
    try {
        // Start a transaction
        $db->beginTransaction();

        // Look for ID_ALIMENT that matches $data['NOM']
        $sql = "SELECT ID_ALIMENT FROM aliment WHERE NOM = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$data['NOM']]);
        $alimentID = $stmt->fetch(PDO::FETCH_COLUMN);

        // Check if ID_ALIMENT was found
        if (!$alimentID) {
            throw new Exception("Aliment not found for the given NOM: " . $data['NOM']);
        }

        // First insert into the journal table
        $sql = "INSERT INTO journal (ID_UTILISATEUR, DATE) VALUES (?, ?)";
        $stmt = $db->prepare($sql);
        $stmt->execute([
            $data['ID_UTILISATEUR'],
            $data['DATE']
        ]);

        // Get the last inserted ID for journal
        $lastJournalId = $db->lastInsertId();

        // Second insert into the reference table using the last journal ID
        $sql = "INSERT INTO reference (ID_JOURNAL, ID_ALIMENT, QUANTITE) VALUES (?, ?, ?)";
        $stmt = $db->prepare($sql);
        $stmt->execute([
            $lastJournalId,
            $alimentID,
            $data['QUANTITE']
        ]);

        // Commit the transaction
        $db->commit();

        return $lastJournalId;
    } catch (Exception $e) {
        // Rollback the transaction in case of error
        $db->rollBack();
        throw new Exception("Failed to create journal entry: " . $e->getMessage());
    }
}


function update_journal($db, $ID_JOURNAL, $data)
{
    try {
        // Begin transaction
        $db->beginTransaction();

        // Check if the journal entry exists
        $stmt = $db->prepare("SELECT * FROM journal WHERE ID_JOURNAL = ?");
        $stmt->execute([$ID_JOURNAL]);
        $journal = $stmt->fetch(PDO::FETCH_OBJ);

        if (!$journal) {
            throw new Exception("Journal entry not found");
        }

        // Set updated values for journal
        $ID_UTILISATEUR = isset($data['ID_UTILISATEUR']) ? $data['ID_UTILISATEUR'] : $journal->ID_UTILISATEUR;
        $DATE = isset($data['DATE']) ? $data['DATE'] : $journal->DATE;

        // Update the journal table
        $sql = "UPDATE journal SET ID_UTILISATEUR = ?, DATE = ? WHERE ID_JOURNAL = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$ID_UTILISATEUR, $DATE, $ID_JOURNAL]);

        // Determine ID_ALIMENT based on NOM if provided
        if (isset($data['NOM'])) {
            // Look for ID_ALIMENT that matches the given NOM
            $sql = "SELECT ID_ALIMENT FROM aliment WHERE NOM = ?";
            $stmt = $db->prepare($sql);
            $stmt->execute([$data['NOM']]);
            $alimentID = $stmt->fetch(PDO::FETCH_COLUMN);

            if (!$alimentID) {
                throw new Exception("Aliment not found for the given NOM: " . $data['NOM']);
            }
        } else {
            // Use existing ID_ALIMENT from reference if NOM not provided
            $stmt = $db->prepare("SELECT ID_ALIMENT FROM reference WHERE ID_JOURNAL = ?");
            $stmt->execute([$ID_JOURNAL]);
            $alimentID = $stmt->fetch(PDO::FETCH_COLUMN);

            if (!$alimentID) {
                throw new Exception("Reference entry not found for the given journal ID: " . $ID_JOURNAL);
            }
        }

        // Set quantity for reference table
        $QUANTITE = isset($data['QUANTITE']) ? $data['QUANTITE'] : $journal->QUANTITE;

        // Update the reference table with new ID_ALIMENT and QUANTITE
        $sql = "UPDATE reference SET ID_ALIMENT = ?, QUANTITE = ? WHERE ID_JOURNAL = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$alimentID, $QUANTITE, $ID_JOURNAL]);

        // Commit the transaction
        $db->commit();

        return true;
    } catch (Exception $e) {
        // Rollback the transaction in case of error
        $db->rollBack();
        throw new Exception("Failed to update journal entry: " . $e->getMessage());
    }
}


function delete_journal($db, $ID_JOURNAL)
{
    $sql = "DELETE FROM reference WHERE ID_JOURNAL = ?";
    $stmt = $db->prepare($sql);
    $stmt->execute([$ID_JOURNAL]);
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
            $result = get_journals($pdo, $_SESSION['user_id']);
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
        if (isset($_GET['ID'])) {
            $stmt = $pdo->prepare("SELECT * FROM journal WHERE ID_JOURNAL = ?");
            $stmt->execute([$_GET['ID']]);
            $journal = $stmt->fetch(PDO::FETCH_OBJ);

            if (!$journal) {
                http_response_code(404);
                echo json_encode(["error" => "Journal not found"]);
            } else {
                $data = json_decode(file_get_contents('php://input'), true);
                if (update_journal($pdo, $_GET['ID'], $data)) {
                    echo json_encode([
                        "ID_JOURNAL" => $_GET['ID'],
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
        if (isset($_GET['ID'])) {
            $stmt = $pdo->prepare("SELECT * FROM journal WHERE ID_JOURNAL = ?");
            $stmt->execute([$_GET['ID']]);
            $journal = $stmt->fetch(PDO::FETCH_OBJ);

            if (!$journal) {
                http_response_code(404);
                echo json_encode(["error" => "Journal not found"]);
            } else {
                if (delete_journal($pdo, $_GET['ID'])) {
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
