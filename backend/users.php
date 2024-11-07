<?php
session_start();
require_once("init_pdo.php");

function get_utilisateur($db) {
    $sql = "SELECT * FROM utilisateur";
    $exe = $db->query($sql);
    $res = $exe->fetchAll(PDO::FETCH_OBJ);
    return $res;
}

function create_utilisateur($db, $data) {
    $sql = "INSERT INTO utilisateur (ID_AGE, ID_SEXE, ID_NS, USERNAME, PASSWORD) VALUES (?, ?, ?, ?, ?)";
    $stmt = $db->prepare($sql);
    $stmt->execute([
        $data['ID_AGE'],
        $data['ID_SEXE'],
        $data['ID_NS'],
        $data['USERNAME'],
        password_hash($data['PASSWORD'], PASSWORD_DEFAULT)
    ]);
    return $db->lastInsertId();
}

function update_utilisateur($db, $ID_UTILISATEUR, $data) {
    $sql = "UPDATE utilisateur SET ID_AGE = ?, ID_SEXE = ?, ID_NS = ? WHERE ID_UTILISATEUR = ?";
    $stmt = $db->prepare($sql);
    return $stmt->execute([
        $data['ID_AGE'],
        $data['ID_SEXE'],
        $data['ID_NS'],
        $ID_UTILISATEUR
    ]);
}

function delete_utilisateur($db, $ID_UTILISATEUR) {
    $sql = "DELETE FROM utilisateur WHERE ID_UTILISATEUR = ?";
    $stmt = $db->prepare($sql);
    return $stmt->execute([$ID_UTILISATEUR]);
}

function setHeaders() {
    header("Access-Control-Allow-Origin: *");
    header('Content-type: application/json; charset=utf-8');
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
}

setHeaders();

// Login request processing
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_GET['action']) && $_GET['action'] === 'login') {
    $data = json_decode(file_get_contents('php://input'), true);
    $username = $data['username'];
    $password = $data['password'];

    // Verify username and password
    $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE USERNAME = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_OBJ);

    if ($user && password_verify($password, $user->PASSWORD)) {
        $_SESSION['user_id'] = $user->ID_UTILISATEUR;
        $_SESSION['username'] = $user->USERNAME;
        $_SESSION['age_group'] = $user->ID_AGE;
        $_SESSION['gender'] = $user->ID_SEXE;
        $_SESSION['activity_level'] = $user->ID_NS;

        echo json_encode(["message" => "Login successful"]);
    } else {
        http_response_code(401);
        echo json_encode(["error" => "Invalid credentials"]);
    }
    exit;
}

// Ensure user is authenticated
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["error" => "User not authenticated"]);
    exit;
}

// Main API Request Handling
switch ($_SERVER["REQUEST_METHOD"]) {
    case 'GET':
        if (isset($_GET['ID_UTILISATEUR'])) {
            $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE ID_UTILISATEUR = ?");
            $stmt->execute([$_GET['ID_UTILISATEUR']]);
            $user = $stmt->fetch(PDO::FETCH_OBJ);
            if (!$user) {
                http_response_code(404);
                echo json_encode(["error" => "User not found"]);
            } else {
                echo json_encode($user);
            }
        } else {
            echo json_encode(get_utilisateur($pdo));
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        
        // Handle profile update if `update` is set to true
        if (isset($data['update']) && $data['update'] === true) {
            $userId = $_SESSION['user_id'];
            $ageGroup = $data['ID_AGE'];
            $gender = $data['ID_SEXE'];
            $activityLevel = $data['ID_NS'];

            // Update the user profile
            if (update_utilisateur($pdo, $userId, ['ID_AGE' => $ageGroup, 'ID_SEXE' => $gender, 'ID_NS' => $activityLevel])) {
                // Refresh session variables with updated values
                $_SESSION['age_group'] = $ageGroup;
                $_SESSION['gender'] = $gender;
                $_SESSION['activity_level'] = $activityLevel;

                echo json_encode(["message" => "Profile updated successfully"]);
            } else {
                http_response_code(500);
                echo json_encode(["error" => "Failed to update profile"]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["error" => "Invalid request"]);
        }
        break;

    case 'PUT':
        if (isset($_GET['ID_UTILISATEUR'])) {
            $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE ID_UTILISATEUR = ?");
            $stmt->execute([$_GET['ID_UTILISATEUR']]);
            $user = $stmt->fetch(PDO::FETCH_OBJ);

            if (!$user) {
                http_response_code(404);
                echo json_encode(["error" => "User not found"]);
            } else {
                $data = json_decode(file_get_contents('php://input'), true);
                $result = update_utilisateur($pdo, $_GET['ID_UTILISATEUR'], $data);
                if ($result) {
                    echo json_encode(["message" => "User updated successfully"]);
                } else {
                    http_response_code(500);
                    echo json_encode(["error" => "Failed to update user"]);
                }
            }
        } else {
            http_response_code(400);
            echo json_encode(["error" => "User ID is required"]);
        }
        break;

    case 'DELETE':
        if (isset($_GET['ID_UTILISATEUR'])) {
            $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE ID_UTILISATEUR = ?");
            $stmt->execute([$_GET['ID_UTILISATEUR']]);
            $user = $stmt->fetch(PDO::FETCH_OBJ);

            if (!$user) {
                http_response_code(404);
                echo json_encode(["error" => "User not found"]);
            } else {
                if (delete_utilisateur($pdo, $_GET['ID_UTILISATEUR'])) {
                    echo json_encode(["message" => "User deleted successfully"]);
                } else {
                    http_response_code(500);
                    echo json_encode(["error" => "Failed to delete user"]);
                }
            }
        } else {
            http_response_code(400);
            echo json_encode(["error" => "User ID is required"]);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(["error" => "Method not allowed"]);
}
?>
