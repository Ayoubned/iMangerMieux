<?php
require_once("init_pdo.php");

function get_utilisateur($db)
{
    $sql = "SELECT * FROM utilisateur";
    $exe = $db->query($sql);
    $res = $exe->fetchAll(PDO::FETCH_OBJ);
    return $res;
}

function create_utilisateur($db, $data)
{
    $sql = "INSERT INTO utilisateur (ID_AGE, ID_SEXE, ID_NS, USERNAME, PASSWORD) VALUES (?, ?, ?, ?, ?)";
    $stmt = $db->prepare($sql);
    $stmt->execute([
        $data['ID_AGE'],
        $data['ID_SEXE'],
        $data['ID_NS'],
        $data['USERNAME'],
        password_hash($data['PASSWORD'], PASSWORD_DEFAULT)
    ]);
    return $db->lastInsertid();
}

function update_utilisateur($db, $ID_UTILISATEUR, $data)
{
    $sql = "UPDATE utilisateur SET ID_AGE = ?, ID_SEXE = ?, ID_NS = ?, USERNAME = ?, PASSWORD = ? WHERE ID_UTILISATEUR = ?";
    $stmt = $db->prepare($sql);
    return $stmt->execute([
        $data['ID_AGE'],
        $data['ID_SEXE'],
        $data['ID_NS'],
        $data['USERNAME'],
        password_hash($data['PASSWORD'], PASSWORD_DEFAULT),
        $ID_UTILISATEUR
    ]);
}

function delete_utilisateur($db, $ID_UTILISATEUR)
{
    $sql = "DELETE FROM utilisateur WHERE ID_UTILISATEUR = ?";
    $stmt = $db->prepare($sql);
    return $stmt->execute([$ID_UTILISATEUR]);
}

function setHeaders()
{
    header("Access-Control-Allow-Origin: *");
    header('Content-type: application/json; charset=utf-8');
}

setHeaders();
// Check for login request
if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET['username'], $_GET['password'])) {
    $username = $_GET['username'];
    $password = $_GET['password'];

    // Verify username and password
    $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE USERNAME = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_OBJ);

    if ($user && password_verify($password, $user->PASSWORD)) {
        session_start();
        $_SESSION['user_id'] = $user->ID_UTILISATEUR;
        $_SESSION['username'] = $user->USERNAME;

        echo json_encode(["message" => "Login successful"]);
    } else {
        http_response_code(401);
        echo json_encode(["error" => "Invalid credentials"]);
    }
    exit;
}

// =================
// Handle API Requests
// =================
switch ($_SERVER["REQUEST_METHOD"]) {
    case 'GET':
        if (isset($_GET['ID_UTILISATEUR'])) {
            $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE ID_UTILISATEUR = ?");
            $stmt->execute([$_GET['ID_UTILISATEUR']]);
            $utilisateur = $stmt->fetch(PDO::FETCH_OBJ);
            if (json_encode($utilisateur) == 'false') {
                http_response_code(404);
                echo json_encode(["error" => "utilisateur not found"]);
                break;
            }
            echo json_encode($utilisateur);
        } else {
            $result = get_utilisateur($pdo);
            echo json_encode($result);
        }
        break;

        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);
            if (isset($data['ID_AGE'], $data['ID_SEXE'], $data['ID_NS'], $data['USERNAME'], $data['PASSWORD'])) {
                try {
                    // Attempt to create the new user
                    $utilisateurID_UTILISATEUR = create_utilisateur($pdo, $data);
                    http_response_code(201);
                    echo json_encode([
                        "ID_UTILISATEUR" => $utilisateurID_UTILISATEUR,
                        "ID_AGE" => $data['ID_AGE'],
                        "ID_SEXE" => $data['ID_SEXE'],
                        "ID_NS" => $data['ID_NS'],
                        "USERNAME" => $data['USERNAME']
                    ]);
                } catch (PDOException $e) {
                    // Check if the error is due to duplicate username
                    if ($e->getCode() == 23000) { // 23000 is the SQLSTATE code for integrity constraint violation
                        http_response_code(409);
                        echo json_encode(["error" => "Username already exists"]);
                    } else {
                        // Handle other potential database errors
                        http_response_code(500);
                        echo json_encode(["error" => "An unexpected error occurred"]);
                    }
                }
            } else {
                http_response_code(400);
                echo json_encode(["error" => "Invalid input data"]);
            }
            break;

        case 'PUT':
            if (isset($_GET['ID_UTILISATEUR'])) {
                // Fetch the current utilisateur data
                $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE ID_UTILISATEUR = ?");
                $stmt->execute([$_GET['ID_UTILISATEUR']]);
                $utilisateur = $stmt->fetch(PDO::FETCH_OBJ);
        
                if (!$utilisateur) {
                    http_response_code(404);
                    echo json_encode(["error" => "utilisateur not found"]);
                    break;
                }
        
                // Get the ID of the utilisateur to update
                $ID_UTILISATEUR = $_GET['ID_UTILISATEUR'];
        
                // Decode the input data
                $data = json_decode(file_get_contents('php://input'), true);
        
                // Update only the fields that are provided, keeping the current values for the rest
                $ID_AGE = isset($data['ID_AGE']) ? $data['ID_AGE'] : $utilisateur->ID_AGE;
                $ID_SEXE = isset($data['ID_SEXE']) ? $data['ID_SEXE'] : $utilisateur->ID_SEXE;
                $ID_NS = isset($data['ID_NS']) ? $data['ID_NS'] : $utilisateur->ID_NS;
                $USERNAME = isset($data['USERNAME']) ? $data['USERNAME'] : $utilisateur->USERNAME;
        
                // Handle password separately, since we don't want to hash the existing password again
                if (isset($data['PASSWORD'])) {
                    $PASSWORD = password_hash($data['PASSWORD'], PASSWORD_DEFAULT);
                } else {
                    $PASSWORD = $utilisateur->PASSWORD; // Keep the existing hashed password
                }
        
                // Prepare the SQL query to update the utilisateur
                $sql = "UPDATE utilisateur SET ID_AGE = ?, ID_SEXE = ?, ID_NS = ?, USERNAME = ?, PASSWORD = ? WHERE ID_UTILISATEUR = ?";
                $stmt = $pdo->prepare($sql);
                $result = $stmt->execute([$ID_AGE, $ID_SEXE, $ID_NS, $USERNAME, $PASSWORD, $ID_UTILISATEUR]);
        
                if ($result) {
                    http_response_code(200);
                    echo json_encode([
                        "ID_UTILISATEUR" => $ID_UTILISATEUR,
                        "ID_AGE" => $ID_AGE,
                        "ID_SEXE" => $ID_SEXE,
                        "ID_NS" => $ID_NS,
                        "USERNAME" => $USERNAME
                    ]);
                } else {
                    http_response_code(500);
                    echo json_encode(["error" => "Failed to update utilisateur"]);
                }
            } else {
                http_response_code(400);
                echo json_encode(["error" => "utilisateur ID is required"]);
            }
            break;
        

    case 'DELETE':
        if (isset($_GET['ID_UTILISATEUR'])) {
            $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE ID_UTILISATEUR = ?");
            $stmt->execute([$_GET['ID_UTILISATEUR']]);
            $utilisateur = $stmt->fetch(PDO::FETCH_OBJ);

            if (!$utilisateur) {
                http_response_code(404);
                echo json_encode(["error" => "utilisateur not found"]);
            } else {
                if (delete_utilisateur($pdo, $_GET['ID_UTILISATEUR'])) {
                    http_response_code(200);
                    echo json_encode(["message" => "utilisateur deleted"]);
                } else {
                    http_response_code(500);
                    echo json_encode(["error" => "Failed to delete utilisateur"]);
                }
            }
        } else {
            http_response_code(400);
            echo json_encode(["error" => "utilisateur ID is required"]);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(["error" => "Method not allowed"]);
}
?>