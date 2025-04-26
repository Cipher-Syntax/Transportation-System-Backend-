<?php
    header("Content-Type: application/json");
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
    header("Access-Control-Allow-Headers: Content-Type");

    require_once("../database/Connection.php");
    require_once("../database/Database.php");
    require_once("../database/UserRegistration.php");

    $database = new Database();
    $conn = $database->getConnection();
    $user = new UserRegistration($conn);

    $method = $_SERVER['REQUEST_METHOD'];

    switch ($method) {
        // GET: Get all users
        case 'GET':

            if (isset($_GET['id'])) {
                $id = $_GET['id'];
                $userData = $user->getUserById($id);
                if ($userData) {
                    echo json_encode([
                        "status" => "success",
                        "user" => $userData
                    ]);
                } else {
                    echo json_encode([
                        "status" => "error",
                        "message" => "User not found"
                    ]);
                }
            } else {
                $users = $user->getAllUsers();
                echo json_encode([
                    "status" => "success",
                    "users" => $users
                ]);
            }
            break;

        // POST: Create new user
        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);
            
            // Create User
            if (isset($data['firstname'], $data['lastname'], $data['email'], $data['password'])) {
                $result = $user->createUser($data['firstname'], $data['lastname'], $data['email'], $data['password']);
                
                if ($result) {
                    echo json_encode([
                        "status" => "success",
                        "message" => "User created successfully"
                    ]);
                } 
                else {
                    echo json_encode([
                        "status" => "error",
                        "message" => "User creation failed"
                    ]);
                }
            } 
            else {
                echo json_encode([
                    "status" => "error",
                    "message" => "Missing required fields"
                ]);
            }
            break;

        // PUT: Update user
        case 'PUT':
            $data = json_decode(file_get_contents('php://input'), true);
            if (isset($data['id'], $data['firstname'], $data['lastname'], $data['email'])) {
                $result = $user->updateUser($data['id'], $data['firstname'], $data['lastname'], $data['email'], $data['user_profile']);
                echo json_encode([
                    "status" => $result ? "success" : "error",
                    "message" => $result ? "User updated successfully" : "Failed to update user"
                ]);
            } else {
                echo json_encode([
                    "status" => "error",
                    "message" => "Missing required fields"
                ]);
            }
            break;

        // DELETE: Delete user
        case 'DELETE':
            $data = json_decode(file_get_contents('php://input'), true);
            if (isset($data['id'])) {
                $result = $user->deleteUser($data['id']);
                echo json_encode([
                    "status" => $result ? "success" : "error",
                    "message" => $result ? "User deleted successfully" : "Failed to delete user"
                ]);
            } else {
                echo json_encode([
                    "status" => "error",
                    "message" => "User ID is required"
                ]);
            }
            break;

        default:
            // Method Not Allowed
            http_response_code(405);
            echo json_encode([
                "status" => "error", 
                "message" => "Method Not Allowed"
            ]);
            break;
    }
?>
